<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncPostalCodes extends Command
{
    // asa o chemi: php artisan postal:sync   (--db= suprascrie calea catre strazi.db)
    protected $signature = 'postal:sync {--db= : Calea catre fisierul strazi.db}';

    protected $description = 'Importa codurile postale oficiale din strazi.db (SQLite) in tabela postal_codes (MySQL).';

    public function handle()
    {
        // calea implicita catre baza construita de scriptul Python
        $sqlitePath = $this->option('db')
            ?: 'C:\Users\Personal\Desktop\CODURI POSTALE ANAF\strazi.db';

        if (!file_exists($sqlitePath)) {
            $this->error("Nu gasesc fisierul SQLite: {$sqlitePath}");
            return self::FAILURE;
        }

        // 1) deschidem SQLite doar pentru CITIRE (aplicatia nu-l foloseste live)
        $pdo = new \PDO('sqlite:' . $sqlitePath);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

        $total = (int)$pdo->query('SELECT COUNT(*) FROM postal_codes')->fetchColumn();
        $this->info("SQLite contine {$total} randuri.");

        // 2) stergem DOAR randurile oficiale -> corectiile operatorilor (source<>'oficial') raman
        $sterse = DB::table('postal_codes')->where('source', 'oficial')->delete();
        $this->warn("Sterse {$sterse} randuri vechi (source=oficial).");

        // 3) citim in loturi si inseram
        $rows = $pdo->query("SELECT county, county_normalized, city, city_normalized,
            street_type, street_name, street_normalized, number_from, number_to,
            parity, postal_code, source FROM postal_codes");

        $bar = $this->output->createProgressBar($total);
        $now = now();
        $batch = [];

        foreach ($rows as $r) {
            $r['created_at'] = $now;
            $r['updated_at'] = $now;
            $batch[] = $r;

            if (count($batch) >= 500) {
                DB::table('postal_codes')->insert($batch);
                $bar->advance(count($batch));
                $batch = [];
            }
        }
        if ($batch) {
            DB::table('postal_codes')->insert($batch);
            $bar->advance(count($batch));
        }

        $bar->finish();
        $this->newLine();

        $final = DB::table('postal_codes')->count();
        $this->info("Gata. postal_codes are acum {$final} randuri.");

        return self::SUCCESS;
    }
}
