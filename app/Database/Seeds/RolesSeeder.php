<?php namespace App\Database\Seeds;

class RolesSeeder extends \CodeIgniter\Database\Seeder
{
        public function run()
        {       
          // Roles
          $initRoles = [
                  ["nama" => "distributor"],
                  ["nama" => "pemilik toko"],
                  ["nama" => "karyawan"],
          ];
          $this->db->table('roles')->insertBatch($initRoles);
        }
}