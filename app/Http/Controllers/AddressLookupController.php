<?php

namespace App\Http\Controllers;

use App\Services\PostalCodeLookup;
use App\Models\CityPostalCode;
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
     * GET /cauta/strazi?oras=&q=
     * Numele de strazi din oras care incep cu q (pentru autocomplete). Maxim 10.
     */
    public function strazi(Request $request)
    {
        $orasN = A::normalize($request->query('oras', ''));
        $qN = A::normalizeStreet($request->query('q', ''));

        // asteptam minim 3 litere si sa stim localitatea (strazile sunt per-oras)
        if ($orasN === '' || strlen($qN) < 3) {
            return response()->json([]);
        }

        return response()->json(
            CityPostalCode::where('city_normalized', $orasN)
                ->where('street_normalized', 'like', $qN . '%')
                ->distinct()
                ->orderBy('street_name')
                ->limit(10)
                ->pluck('street_name')
        );
    }
}
