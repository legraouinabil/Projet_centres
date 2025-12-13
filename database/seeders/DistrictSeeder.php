<?php
// database/seeders/DistrictSeeder.php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    public function run()
    {
        $districts = [
            ['nom_district_ar' => 'الرباط', 'nom_district_fr' => 'Rabat', 'region' => 'Rabat-Salé-Kénitra'],
            ['nom_district_ar' => 'الدار البيضاء', 'nom_district_fr' => 'Casablanca', 'region' => 'Casablanca-Settat'],
            ['nom_district_ar' => 'مراكش', 'nom_district_fr' => 'Marrakech', 'region' => 'Marrakech-Safi'],
            ['nom_district_ar' => 'فاس', 'nom_district_fr' => 'Fès', 'region' => 'Fès-Meknès'],
            ['nom_district_ar' => 'طنجة', 'nom_district_fr' => 'Tanger', 'region' => 'Tanger-Tétouan-Al Hoceïma'],
        ];

        foreach ($districts as $district) {
            District::create($district);
        }
    }
}