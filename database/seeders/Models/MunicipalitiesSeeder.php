<?php

namespace Database\Seeders\Models;

use App\Models\Municipality;
use Illuminate\Database\Seeder;

class MunicipalitiesSeeder extends Seeder
{
    // file is current/Sources/municipalities.csv
    // id,name,cap,region_code,region_name,created_at,updated_at,nationality
    // 14609,Napoli,80139,NA,Campania,2024-02-06T04:01:47.000000Z,2024-02-06T07:00:05.000000Z,italian
    // 14608,Palermo,98123,PA,Sicilia,2024-02-05T23:16:36.000000Z,2024-02-06T07:00:05.000000Z,italian
    // 14607,Roma,00141,RM,Lazio,2024-02-05T13:01:49.000000Z,2024-02-05T15:13:34.000000Z,
    // CSV TO ARRAY
    public function csvToArray()
    {
        $csv = array_map('str_getcsv', file(database_path('seeders/Sources/municipalities.csv')));
        array_walk($csv, function(&$a) use ($csv) {
            $a = array_combine($csv[0], $a);
        });
        array_shift($csv);

        $filteredCsv = [];
        foreach ($csv as $row) {
            $cap = $row['cap'] ?? null;
            $region_code = $row['region_code'] ?? null;
            if (empty($cap) ||
                empty($region_code) ||
                strlen($cap) !== 5 ||
                strlen($region_code) !== 2
            ) {
                continue;
            }
            $filteredCsv[] = [
                'cap' => $row['cap'],
                'region' => $row['region_name'] ?? '',
                'region_code' => $row['region_code'],
            ];
        }

        return $filteredCsv;
    }

    public function run(): void
    {
        $municipalitiesAsArray = $this->csvToArray();
        foreach ($municipalitiesAsArray as $municipality) {
            Municipality::create($municipality);
        }
    }
}
