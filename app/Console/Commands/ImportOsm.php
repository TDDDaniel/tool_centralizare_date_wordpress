<?php

namespace App\Console\Commands;

use App\Models\CityPostalCode;
use App\Support\AddressNormalizer as A;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ImportOsm extends Command
{
    protected $signature = 'import:osm
                            {--oras=* : Doar orasele astea (implicit: toate)}
                            {--pauza=5 : Secunde intre cereri}';

    protected $description = 'Importa strazi + coduri postale din OpenStreetMap (Overpass API)';

    private const OVERPASS = 'https://overpass-api.de/api/interpreter';

    /** Overpass raspunde 406 clientilor fara User-Agent identificabil. */
    private const USER_AGENT = 'Conflux/1.0 (tool intern)';

    /** Resedintele de judet - acolo unde datele publice nu ne dau strazi. */
    private const ORASE = [
        'Cluj' => 'Cluj-Napoca',
        'Bucureşti' => 'Bucureşti',
        'Timiş' => 'Timişoara',
        'Iaşi' => 'Iaşi',
        'Constanţa' => 'Constanţa',
        'Braşov' => 'Braşov',
        'Dolj' => 'Craiova',
        'Galaţi' => 'Galaţi',
        'Prahova' => 'Ploieşti',
        'Bihor' => 'Oradea',
        'Brăila' => 'Brăila',
        'Arad' => 'Arad',
        'Argeş' => 'Piteşti',
        'Sibiu' => 'Sibiu',
        'Bacău' => 'Bacău',
        'Mureş' => 'Târgu Mureş',
        'Maramureş' => 'Baia Mare',
        'Buzău' => 'Buzău',
        'Botoşani' => 'Botoşani',
        'Satu Mare' => 'Satu Mare',
        'Vâlcea' => 'Râmnicu Vâlcea',
        'Suceava' => 'Suceava',
        'Neamţ' => 'Piatra Neamţ',
        'Mehedinţi' => 'Drobeta-Turnu Severin',
        'Dâmboviţa' => 'Târgovişte',
        'Vrancea' => 'Focşani',
        'Gorj' => 'Târgu Jiu',
        'Tulcea' => 'Tulcea',
        'Caraş-Severin' => 'Reşiţa',
        'Bistriţa-Năsăud' => 'Bistriţa',
        'Olt' => 'Slatina',
        'Vaslui' => 'Vaslui',
        'Giurgiu' => 'Giurgiu',
        'Hunedoara' => 'Deva',
        'Sălaj' => 'Zalău',
        'Covasna' => 'Sfântu Gheorghe',
        'Alba' => 'Alba Iulia',
        'Ialomiţa' => 'Slobozia',
        'Harghita' => 'Miercurea Ciuc',
        'Teleorman' => 'Alexandria',
        'Călăraşi' => 'Călăraşi',
        'Ilfov' => 'Buftea',
    ];

    public function handle(): int
    {
        $pauza = (int)$this->option('pauza');
        $filtru = $this->option('oras');

        $orase = self::ORASE;
        if ($filtru) {
            $orase = array_filter($orase, fn($o) => in_array($o, $filtru, true));
        }

        if (empty($orase)) {
            $this->error('Niciun oras de importat. Verifica --oras.');

            return self::FAILURE;
        }

        $this->info('Import din OpenStreetMap: ' . count($orase) . ' orase');
        $this->newLine();

        $totalRanduri = 0;

        foreach ($orase as $judet => $oras) {
            // === RELUARE: daca l-am importat deja, sarim.
            // Baza de date E memoria progresului - nu ne trebuie alt tabel.
            $existe = CityPostalCode::where('city', $oras)
                ->where('source', 'osm')
                ->exists();

            if ($existe) {
                $this->line(sprintf('  <fg=gray>%-24s sarit (deja importat)</>', $oras));

                continue;
            }

            $adrese = $this->interogheaza($oras);

            if ($adrese === null) {
                $this->line(sprintf('  <fg=red>%-24s EROARE - reiau la urmatoarea rulare</>', $oras));
                sleep($pauza);

                continue;
            }

            $randuri = $this->construiesteRanduri($judet, $oras, $adrese);

            foreach (array_chunk($randuri, 500) as $chunk) {
                CityPostalCode::insert($chunk);
            }

            $totalRanduri += count($randuri);

            $this->line(sprintf(
                '  <fg=green>%-24s %5d adrese OSM -> %4d randuri</>',
                $oras, count($adrese), count($randuri)
            ));

            sleep($pauza);   // pauza fixa - suntem politicosi cu un serviciu gratuit
        }

        $this->newLine();
        $this->info("Gata. {$totalRanduri} randuri noi in city_postal_codes.");

        return self::SUCCESS;
    }

    /**
     * Un request la Overpass, cu backoff exponential DOAR pe eroare.
     *
     * In Romania OSM: admin_level 4 = JUDET, 8 = oras/comuna.
     * Folosim doar 8, altfel "Sibiu" prinde judetul (verificat: 6209 adrese
     * din tot judetul in loc de orasul Sibiu).
     */
    private function interogheaza(string $oras): ?array
    {
        $numeOsm = strtr($oras, [
            'ş' => 'ș', 'Ş' => 'Ș',
            'ţ' => 'ț', 'Ţ' => 'Ț',
        ]);

        $query = <<<OVERPASS
[out:json][timeout:180];
area["name"="{$numeOsm}"]["boundary"="administrative"]["admin_level"="8"]->.zona;
nwr["addr:street"]["addr:postcode"](area.zona);
out center tags;
OVERPASS;

        $asteptare = 5;

        for ($incercare = 1; $incercare <= 4; $incercare++) {
            try {
                $r = Http::timeout(200)
                    ->withHeaders(['User-Agent' => self::USER_AGENT])
                    ->asForm()
                    ->post(self::OVERPASS, ['data' => $query]);

                if ($r->successful()) {
                    return $r->json('elements') ?? [];
                }

                // 429 = prea multe cereri, 5xx = problema la ei -> merita reincercat.
                // Restul (400, 406...) nu se repara prin reincercare - iesim imediat.
                if ($r->status() !== 429 && $r->status() < 500) {
                    $this->error("    HTTP {$r->status()}: " . substr($r->body(), 0, 200));

                    return null;
                }

                $this->warn("    HTTP {$r->status()}, reincerc peste {$asteptare}s...");
            } catch (\Throwable $e) {
                // NU inghitim eroarea - o afisam, altfel debughezi in orb.
                $this->warn('    ' . get_class($e) . ': ' . $e->getMessage());
            }

            sleep($asteptare);
            $asteptare += 1;      // 5, 10, 20, 40
        }

        return null;
    }

    /**
     * Din adresele OSM -> randuri pentru tabel.
     * Strazi cu UN cod -> 1 rand (toata strada). Cu mai multe -> 1 rand per casa.
     */
    private function construiesteRanduri(string $judet, string $oras, array $adrese): array
    {
        $cityNorm = A::normalize($oras);
        $acum = now();

        // Grupam adresele pe strada
        $strazi = [];
        foreach ($adrese as $el) {
            $tags = $el['tags'] ?? [];
            $strada = $tags['addr:street'] ?? null;
            $cod = $tags['addr:postcode'] ?? null;

            // Codul postal romanesc are exact 6 cifre. Restul e gunoi.
            // In OSM scrie oricine orice - filtram la poarta.
            if (!$strada || !preg_match('/^\d{6}$/', (string)$cod)) {
                continue;
            }

            $cheie = A::normalizeStreet($strada);
            if ($cheie === '') {
                continue;
            }

            $strazi[$cheie]['nume'] = $strada;   // scriere "frumoasa" pentru afisare
            $strazi[$cheie]['case'][] = [
                'nr' => A::parseNumber($tags['addr:housenumber'] ?? null),
                'cod' => $cod,
            ];
        }

        $randuri = [];

        foreach ($strazi as $cheie => $date) {
            $coduri = array_unique(array_column($date['case'], 'cod'));
            $parti = A::splitStreet($date['nume']);
            $baza = [
                'county' => $judet,
                'city' => $oras,
                'city_normalized' => $cityNorm,
                'street_type' => $parti['tip'],
                'street_name' => $date['nume'],
                'street_normalized' => $cheie,
                'source' => 'osm',
                'created_at' => $acum,
                'updated_at' => $acum,
            ];

            // CAZ 1: strada are un singur cod -> o regula pentru TOATA strada.
            // Asa acoperim si numerele care nu sunt mapate in OSM.
            if (count($coduri) === 1) {
                $randuri[] = $baza + [
                        'number_from' => null,
                        'number_to' => null,
                        'parity' => 'all',
                        'postal_code' => reset($coduri),
                    ];

                continue;
            }

            // CAZ 2: mai multe coduri -> un rand per numar (fara duplicate)
            $vazute = [];
            foreach ($date['case'] as $casa) {
                if ($casa['nr'] === null) {
                    continue;
                }

                $dedup = $casa['nr'] . '|' . $casa['cod'];
                if (isset($vazute[$dedup])) {
                    continue;
                }
                $vazute[$dedup] = true;

                $randuri[] = $baza + [
                        'number_from' => $casa['nr'],
                        'number_to' => $casa['nr'],
                        'parity' => A::parity($casa['nr']),
                        'postal_code' => $casa['cod'],
                    ];
            }
        }

        return $randuri;
    }
}
