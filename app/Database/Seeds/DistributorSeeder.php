<?php namespace App\Database\Seeds;

class DistributorSeeder extends \CodeIgniter\Database\Seeder
{
  public function run()
  {
    $initDistributor = [
            [
                    "user_id" => 1,
                    "nama_distributor" => "distributor heraya",
            ],
    ];
    $this->db->table('distributor')->insertBatch($initDistributor);
  }
}