<?php namespace App\Database\Seeds;

class UkuranBarangSeeder extends \CodeIgniter\Database\Seeder
{
  public function run()
  {
    $initData = [
        [
          "nama" => "S",
        ],
        [
          "nama" => "200 x 200",
        ],
        [
          "nama" => "Youth",
        ],
    ];
    $this->db->table('ukuran_barang')->insertBatch($initData);
  }
}