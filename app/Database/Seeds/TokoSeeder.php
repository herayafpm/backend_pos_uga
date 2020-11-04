<?php namespace App\Database\Seeds;

class TokoSeeder extends \CodeIgniter\Database\Seeder
{
  public function run()
  {
    // Toko
    $initToko = [
        [
                "user_id" => 2,
                "nama_toko"=>"toko sukajadi",
                "email"=>"toko@gmail.com",
                "alamat"=>"desa sukajadi 02 03",
                "no_telp"=>"089819898213"
        ],
    ];
    $this->db->table('toko')->insertBatch($initToko);
  }
}