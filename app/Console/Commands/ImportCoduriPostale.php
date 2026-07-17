<?php

namespace App\Console\Commands;

use App\Models\PostalCode;
use Illuminate\Console\Command;

class ImportCoduriPostale extends Command
{
    protected $signature = 'import:coduri';
    protected $description = 'Importa codurile postale (nivel localitate) din JSON';

    public function handle(): void
    {
        // Stergem importurile oficiale vechi (ca sa nu dublam la re-rulare)
        PostalCode::where('source', 'oficial')->delete();

        // 1. Citim fisierul JSON
        $path = database_path('data/coduri.json');
        $json = json_decode(file_get_contents($path), true);
        $records = $json['records'];

        $this->info('Import ' . count($records) . ' localitati...');

        // 2. Transformam fiecare rand in formatul tabelului
        $now = now();
        $rows = [];
        foreach ($records as $record) {
            $rows[] = [
                'county' => $record[1],   // Judet      (pozitia 1 din rand)
                'city' => $record[2],   // Localitate (pozitia 2)
                'street' => null,         // localitatile n-au strada
                'postal_code' => str_pad((string)$record[3], 6, '0', STR_PAD_LEFT), // 6 cifre
                'source' => 'oficial',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // 3. Inseram IN LOTURI de 500 (nu 13.000 de query-uri separate)
        foreach (array_chunk($rows, 500) as $chunk) {
            PostalCode::insert($chunk);
        }

        $this->info('Gata! ' . count($rows) . ' localitati importate.');
    }
}
