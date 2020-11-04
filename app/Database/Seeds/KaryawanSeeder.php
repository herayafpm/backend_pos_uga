<?php namespace App\Database\Seeds;

class KaryawanSeeder extends \CodeIgniter\Database\Seeder
{
  public function run()
  {
    $initKaryawan = [
            [
                    "user_id" => 3,
                    "toko_id" => 1,
            ],
    ];
    $this->db->table('karyawan')->insertBatch($initKaryawan);
  }
}