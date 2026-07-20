<?php

namespace App\Support;

/**
 * Curata textul de adresa ca sa poata fi comparat cu "=".
 *
 * Ideea: NU comparam niciodata ce a scris omul cu ce e in baza.
 * Trecem si una si alta prin aceleasi filtre, si comparam rezultatele.
 */
class AddressNormalizer
{
    /**
     * Diacriticele romanesti -> ASCII.
     *
     * ATENTIE: 's cu virgula' (ș, U+0219) si 's cu sedila' (ş, U+015F)
     * sunt DOUA caractere Unicode diferite, desi arata la fel pe ecran.
     * Tastatura romaneasca scrie ș. Datele oficiale contin adesea ş.
     * Trebuie tratate amandoua, altfel cautarile pica fara motiv aparent.
     */
    private const DIACRITICE = [
        'ă' => 'a', 'Ă' => 'a', 'â' => 'a', 'Â' => 'a',
        'î' => 'i', 'Î' => 'i',
        'ș' => 's', 'Ș' => 's', 'ş' => 's', 'Ş' => 's',   // virgula + sedila
        'ț' => 't', 'Ț' => 't', 'ţ' => 't', 'Ţ' => 't',   // virgula + sedila
    ];

    /** Prefixele de tip strada, in ordine: cele lungi INAINTEA celor scurte. */
    private const TIPURI_STRADA = [
        'bulevardul', 'soseaua', 'intrarea', 'splaiul', 'drumul',
        'aleea', 'strada', 'calea', 'piata', 'fundatura',
        'bdul', 'blvd', 'str', 'bd', 'sos', 'al',
    ];

    /**
     * "  Cluj-Napoca " -> "cluj napoca"
     * "Ştefan cel Mare" -> "stefan cel mare"
     */
    public static function normalize(?string $value): string
    {
        if ($value === null) {
            return '';
        }

        // 1. Diacritice -> ASCII (inainte de lowercase, ca sa prindem si majusculele)
        $value = strtr($value, self::DIACRITICE);

        // 2. Litere mici
        $value = mb_strtolower($value, 'UTF-8');

        // 3. Orice nu e litera sau cifra devine spatiu.
        //    Asa "cluj-napoca", "cluj napoca" si "Str. X" ajung la aceeasi forma.
        $value = preg_replace('/[^a-z0-9]+/', ' ', $value);

        // 4. Spatii multiple -> unul singur, si taiem capetele
        return trim(preg_replace('/\s+/', ' ', $value));
    }

    /**
     * Ca normalize(), dar scoate si prefixul de tip strada.
     * "Str. Ştefan cel Mare" -> "stefan cel mare"
     * "Ştefan cel Mare"      -> "stefan cel mare"   (acelasi rezultat!)
     */
    public static function normalizeStreet(?string $value): string
    {
        $value = self::normalize($value);

        foreach (self::TIPURI_STRADA as $tip) {
            if (str_starts_with($value, $tip . ' ')) {
                return trim(substr($value, strlen($tip) + 1));
            }
        }

        return $value;
    }

    /**
     * Scoate numarul din ce a scris omul: "12A" -> 12, "bl. 4" -> 4.
     * Returneaza null daca nu gaseste nicio cifra.
     */
    public static function parseNumber(?string $value): ?int
    {
        if ($value === null || !preg_match('/\d+/', $value, $m)) {
            return null;
        }

        return (int)$m[0];
    }

    /**
     * 12 -> 'par', 13 -> 'impar'
     *
     * Valorile trebuie sa fie IDENTICE cu enum-ul din migrare
     * (city_postal_codes.parity). ANAF foloseste P/I, de-aia romaneste.
     */
    public static function parity(int $number): string
    {
        return $number % 2 === 0 ? 'par' : 'impar';
    }
}
