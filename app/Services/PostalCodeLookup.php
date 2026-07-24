<?php

namespace App\Services;

use App\Models\PostalCode;
use App\Support\AddressNormalizer as A;
use Illuminate\Support\Collection;

class PostalCodeLookup
{
    public const EXACT = 'exact';
    public const LOCALITATE = 'localitate';
    public const AMBIGUU = 'ambiguu';
    public const FARA_COD = 'fara_cod';   // strada exista, dar n-are cod oficial -> operatorul/API-ul il completeaza
    public const NEGASIT = 'negasit';

    /** Ce a cautat efectiv in baza - pentru panoul de diagnostic. */
    private array $cautat = [];

    public function cauta(string $judet, string $oras, ?string $strada = null, ?string $numar = null): array
    {
        $judetN = A::normalize($judet);
        $orasN = A::normalize($oras);
        $stradaN = $strada ? A::normalizeStreet($strada) : null;
        $nr = A::parseNumber($numar);

        $this->cautat = [
            'judet' => $judetN,
            'oras' => $orasN,
            'strada' => $stradaN,
            'numar' => $nr,
        ];

        // STRAT 1: strada + numar
        if ($stradaN) {
            if ($r = $this->cautaPeStrada($judetN, $orasN, $stradaN, $nr)) {
                return $r;
            }
        }

        // STRAT 2: localitatea are un SINGUR cod (sat/comuna).
        // Tabela contine si randuri de strada, deci returnam codul doar daca
        // localitatea are un singur cod distinct - altfel e oras, nu ghicim.
        $coduri = PostalCode::where('county_normalized', $judetN)
            ->where('city_normalized', 'like', $orasN . '%')
            ->whereNotNull('postal_code')
            ->distinct()->pluck('postal_code');

        if ($coduri->count() === 1) {
            return $this->rezultat(self::LOCALITATE, $coduri->first());
        }

        // STRAT 3: nimic - intreaba operatorul
        return $this->rezultat(self::NEGASIT, null);
    }

    private function cautaPeStrada(string $judetN, string $orasN, string $stradaN, ?int $nr): ?array
    {
        $reguli = PostalCode::where('county_normalized', $judetN)
            ->where('city_normalized', $orasN)
            ->where('street_normalized', 'like', $stradaN . '%')
            ->get();

        if ($reguli->isEmpty()) {
            return null;      // strada nu exista deloc -> cadem la stratul urmator
        }

        // Are strada VREUN cod oficial? Decidem asta INAINTE de filtrarea pe numar,
        // altfel o strada cu intervale (fara numar dat) ar parea gresit "fara cod".
        if ($reguli->pluck('postal_code')->filter()->isEmpty()) {
            // strada exista, dar ANAF n-are niciun cod pentru ea -> operatorul (sau API-ul) il completeaza
            return $this->rezultat(self::FARA_COD, null, $reguli);
        }

        $potrivite = $reguli->filter(fn($r) => $this->acopera($r, $nr));

        // CEL MAI SPECIFIC CASTIGA: o regula pe interval bate una pe toata strada.
        $specifice = $potrivite->filter(fn($r) => $r->number_from !== null);
        $finale = $specifice->isNotEmpty() ? $specifice : $potrivite;

        $coduri = $finale->pluck('postal_code')->filter()->unique()->values();

        if ($coduri->count() === 1) {
            return $this->rezultat(self::EXACT, $coduri->first(), $finale);
        }

        // fie 0 (n-ai dat numar pe o strada cu intervale), fie >1 -> aratam toate variantele
        return $this->rezultat(self::AMBIGUU, null, $reguli);
    }

    private function acopera(PostalCode $r, ?int $nr): bool
    {
        if ($r->number_from === null && $r->number_to === null) {
            return true;      // regula pe toata strada
        }

        if ($nr === null) {
            return false;
        }

        if ($r->parity !== 'all' && A::parity($nr) !== $r->parity) {
            return false;
        }

        return $nr >= $r->number_from && $nr <= $r->number_to;
    }

    private function rezultat(string $status, ?string $cod, ?Collection $candidati = null): array
    {
        return [
            'status' => $status,
            'cod' => $cod,
            'cautat' => $this->cautat,
            'candidati' => $candidati?->map(fn($r) => [
                    'judet' => $r->county,
                    'oras' => $r->city,
                    'cod' => $r->postal_code,
                    'strada' => trim(($r->street_type ?? '') . ' ' . $r->street_name),
                    'de_la' => $r->number_from,
                    'pana_la' => $r->number_to,
                    'paritate' => $r->parity,
                    'sursa' => $r->source,
                ])->values()->all() ?? [],
        ];
    }
}
