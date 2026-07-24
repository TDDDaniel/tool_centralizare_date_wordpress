<?php

namespace App\Http\Controllers;

use App\Services\PostalCodeLookup;
use App\Models\PostalCode;
use App\Support\AddressNormalizer as A;
use Illuminate\Http\Request;

/**
 * Endpoint-uri de CITIRE pentru formularul de comanda (AJAX).
 * Nu salveaza nimic - doar raspund cu JSON, ca sa completeze pagina fara reincarcare.
 */
class AddressLookupController extends Controller
{

    public function codPostal(Request $request, PostalCodeLookup $lookup)
    {
        $rezultat = $lookup->cauta(
            $request->query('judet', ''),
            $request->query('oras', ''),
            $request->query('strada'),
            $request->query('numar'),
        );

        // pentru frontend: din zeci de randuri (unul per numar) -> cateva variante pe cod
        if ($rezultat['status'] === PostalCodeLookup::AMBIGUU) {
            $rezultat['variante'] = $this->grupeazaVariante($rezultat['candidati']);
        }

        return response()->json($rezultat);
    }

    /**
     * Grupeaza candidatii pe cod postal si calculeaza intervalul de numere pentru fiecare.
     * 17 randuri Zorilor -> 3 variante: {cod, de_la, pana_la}.
     */
    private function grupeazaVariante(array $candidati): array
    {
        $peCod = [];
        foreach ($candidati as $c) {
            $peCod[$c['cod']][] = $c;
        }

        $variante = [];
        foreach ($peCod as $cod => $randuri) {
            $deLa = array_filter(array_column($randuri, 'de_la'), fn($n) => $n !== null);
            $panaLa = array_filter(array_column($randuri, 'pana_la'), fn($n) => $n !== null);

            $variante[] = [
                'cod' => (string)$cod,
                'judet' => $randuri[0]['judet'],
                'oras' => $randuri[0]['oras'],
                'de_la' => $deLa ? min($deLa) : null,
                'pana_la' => $panaLa ? max($panaLa) : null,
            ];
        }

        return $variante;
    }

    /**
     * GET /cauta/strazi?judet=&oras=&q=
     * Sugestii de strazi, TOLERANT: potriveste pe cuvinte + similaritate (typo-uri).
     * "steagul rosu" -> Steagul Rosu + Turnu Rosu (cuvant comun); "semaforuli" -> Semaforului.
     */
    public function strazi(Request $request)
    {
        $judetN = A::normalize($request->query('judet', ''));
        $orasN  = A::normalize($request->query('oras', ''));
        $qN     = A::normalizeStreet($request->query('q', ''));

        // asteptam minim 3 litere si sa stim localitatea (strazile sunt per-oras)
        if ($orasN === '' || strlen($qN) < 3) {
            return response()->json([]);
        }

        // toate strazile distincte din localitate (mic - o singura localitate)
        $strazi = PostalCode::where('city_normalized', $orasN)
            ->when($judetN !== '', fn($q) => $q->where('county_normalized', $judetN))
            ->distinct()
            ->get(['street_name', 'street_normalized']);

        // cuvintele din ce a tastat (ignoram cele foarte scurte)
        $cuvinte = array_filter(explode(' ', $qN), fn($w) => strlen($w) >= 3);

        return response()->json(
            $strazi->map(function ($s) use ($qN, $cuvinte) {
                $n = $s->street_normalized;
                $scor = 0;
                $areCuvant = false;

                if (str_starts_with($n, $qN)) $scor += 200;   // incepe exact cu ce a scris
                if (str_contains($n, $qN))    $scor += 100;   // contine sirul intreg

                foreach ($cuvinte as $w) {
                    if (str_contains($n, $w)) {               // cuvant comun (ex. "rosu")
                        $scor += 30;
                        $areCuvant = true;
                    }
                }

                similar_text($qN, $n, $procent);              // 0..100, prinde typo-urile
                $scor += $procent;

                // pastram doar ce e relevant: cuvant comun SAU destul de asemanator
                $relevant = $areCuvant || $procent >= 55;

                return ['nume' => $s->street_name, 'scor' => $scor, 'ok' => $relevant];
            })
            ->filter(fn($x) => $x['ok'])
            ->sortByDesc('scor')
            ->take(10)
            ->pluck('nume')
            ->unique()
            ->values()
        );
    }
}
