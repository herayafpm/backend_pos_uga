<?php namespace App\Database\Seeds;

class UsersSeeder extends \CodeIgniter\Database\Seeder
{
  public function run()
  {       
    // Users
    $password = password_hash("123456",PASSWORD_DEFAULT);
    $initUser = [
      [
              'username'       => "distributor1",
              'nama'       => "distributor1",
              'email'       => "distributor1@test.com",
              'alamat'       => "desa gelang",
              'no_telp'       => "0895378036526",
              'role_id'       =>1,
              'aktif'       => 1,
              'password'       => $password,
      ],
      [
              'username'       => "pemiliktoko1",
              'nama'       => "pemiliktoko1",
              'email'       => "pemiliktoko1@test.com",
              'alamat'       => "desa gelang",
              'no_telp'       => "0895378036526",
              'role_id'       =>2,
              'aktif'       => 1,
              'password'       => $password,
      ],
      [
              'username'       => "karyawan1",
              'nama'       => "karyawan1",
              'email'       => "karyawan1@test.com",
              'alamat'       => "desa gelang",
              'no_telp'       => "0895378036526",
              'role_id'       =>3,
              'aktif'       => 1,
              'password'       => $password,
      ],
    ];
    
    $this->db->table('users')->insertBatch($initUser);
  }
}