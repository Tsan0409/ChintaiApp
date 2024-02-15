<?php

namespace Database\Seeders;

use App\Models\CityCsvFile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

// ファイルデータを挿入
class CityCsvFilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $files = [
            [
                'file_name' => '20240215_kyouto_kyoutoshinakagyouku.csv',
                'city_id' => '1',
                'K' => '1',
                'LDK' => '1',
                'R' => '1',
                'SDK' => '1',
                'SK' => '1',
                'SLDK' => '1',
                'DK' => '1',
                'created_at' => '2024-02-15 01:47:35',
                'updated_at' => '2024-02-15 01:47:35'
            ],
            [
                'file_name' => '20240215_kyouto_kyoutoshikamigyouku.csv',
                'city_id' => '2',
                'K' => '1',
                'LDK' => '1',
                'R' => '1',
                'SDK' => '1',
                'SK' => '1',
                'SLDK' => '1',
                'DK' => '1',
                'created_at' => '2024-02-15 01:47:37',
                'updated_at' => '2024-02-15 01:47:37'
            ],
        ];

        foreach ($files as $file) {
            CityCsvFile::create($file);
        };
    }
}