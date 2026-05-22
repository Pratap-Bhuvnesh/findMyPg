<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PgImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
		$buildings = [
		  'https://d64gsuwffb70l.cloudfront.net/69fa4d2dbf60531e22bed9b8_1778011574143_5f19e067.jpg',
		  'https://d64gsuwffb70l.cloudfront.net/69fa4d2dbf60531e22bed9b8_1778011580866_48caf9e8.png',
		  'https://d64gsuwffb70l.cloudfront.net/69fa4d2dbf60531e22bed9b8_1778011576417_bb702dd4.jpg',
		  'https://d64gsuwffb70l.cloudfront.net/69fa4d2dbf60531e22bed9b8_1778011581758_d563d06f.png',
		  'https://d64gsuwffb70l.cloudfront.net/69fa4d2dbf60531e22bed9b8_1778011578940_9107005c.jpg',
		  'https://d64gsuwffb70l.cloudfront.net/69fa4d2dbf60531e22bed9b8_1778011584793_71dbd1ce.png',
		];
		$rooms = [
		  'https://d64gsuwffb70l.cloudfront.net/69fa4d2dbf60531e22bed9b8_1778011601215_7402e61f.jpg',
		  'https://d64gsuwffb70l.cloudfront.net/69fa4d2dbf60531e22bed9b8_1778011604461_e0bb529c.jpg',
		  'https://d64gsuwffb70l.cloudfront.net/69fa4d2dbf60531e22bed9b8_1778011626838_027c1d2c.png',
		  'https://d64gsuwffb70l.cloudfront.net/69fa4d2dbf60531e22bed9b8_1778011603566_ae9d1303.jpg',
		  'https://d64gsuwffb70l.cloudfront.net/69fa4d2dbf60531e22bed9b8_1778011607257_b165d0fa.jpg',
		  'https://d64gsuwffb70l.cloudfront.net/69fa4d2dbf60531e22bed9b8_1778011607480_2ce75ae8.jpg',
		];

         $pgs = DB::table('pgs')->get();
			
        foreach ($pgs as $pg) {
				
            DB::table('pg_images')->insert([
                [
                    'pg_id' => $pg->id,
                    'image_path' => $buildings[array_rand($buildings)],
                    'image_type' => 'building',
                    'display_order' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'pg_id' => $pg->id,
                    'image_path' => $rooms[array_rand($rooms)],
                    'image_type' => 'room',
                    'display_order' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }
}
