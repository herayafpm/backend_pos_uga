<?php namespace App\Database\Seeds;

class BarangDistributorSeeder extends \CodeIgniter\Database\Seeder
{
  public function run()
  {
    $initDistributor = [
        [
          "nama_barang" => "sepeda bmx 2020",
          "jenis_barang_id" => 1,
          "ukuran_barang_id" => 1,
          "stok"=> 30,
          "harga_dasar"=> 100000,
          "harga_jual"=> 150000,
          "keterangan"=> "ready gan",
        ],
        [
          "nama_barang" => "sepeda lipat 2020",
          "jenis_barang_id" => 1,
          "ukuran_barang_id" => 1,
          "stok"=> 30,
          "harga_dasar"=> 100000,
          "harga_jual"=> 150000,
          "keterangan"=> "ready gan",
        ],
    ];
    $this->db->table('barang_distributor')->insertBatch($initDistributor);
  }
}