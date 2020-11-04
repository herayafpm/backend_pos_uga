<?php namespace App\Database\Seeds;

class JenisBarangSeeder extends \CodeIgniter\Database\Seeder
{
  public function run()
  {
    $initData = [
        [
          "nama" => "sepeda lipat",
        ],
        [
          "nama" => "sepeda bmx",
        ],
    ];
    $this->db->table('jenis_barang')->insertBatch($initData);
  }
}