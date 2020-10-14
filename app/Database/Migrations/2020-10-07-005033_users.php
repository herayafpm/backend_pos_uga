<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Users extends Migration
{
	public function up()
	{
			$this->forge->addField([
					'id'          => [
							'type'           => 'INT',
							'constraint'     => 11,
							'unsigned'       => true,
							'auto_increment' => true,
					],
					'nik'       => [
							'type'           => 'VARCHAR',
							'constraint'     => '255',
							'unique' 		 => true,
					],
					'nama'       => [
							'type'           => 'VARCHAR',
							'constraint'     => '255',
					],
					'tempat'       => [
							'type'           => 'VARCHAR',
							'constraint'     => '255',
					],
					'tanggal'       => [
							'type'           => 'VARCHAR',
							'constraint'     => '255',
					],
					'jenis'       => [
						'type'           => 'INT',
						'constraint'     => 11,
					],
					'no_telp'       => [
							'type'           => 'VARCHAR',
							'constraint'     => '255',
					],
					'desa'       => [
							'type'           => 'VARCHAR',
							'constraint'     => '255',
					],
					'rt'       => [
							'type'           => 'VARCHAR',
							'constraint'     => '10',
					],
					'rw'       => [
							'type'           => 'VARCHAR',
							'constraint'     => '10',
					],
					'kecamatan'       => [
							'type'           => 'VARCHAR',
							'constraint'     => '255',
					],
					'kabupaten'       => [
							'type'           => 'VARCHAR',
							'constraint'     => '255',
					],
					'provinsi'       => [
							'type'           => 'VARCHAR',
							'constraint'     => '255',
					],
					'status'       => [
							'type'           => 'INT',
							'constraint'     => 1,
							'default' => 0
					],
					'password'       => [
							'type'           => 'VARCHAR',
							'constraint'     => '255',
					],
					'created_at'       => [
							'type'           => 'DATETIME',
					],
					'updated_at'       => [
							'type'           => 'DATETIME',
					],
			]);
			$this->forge->addKey('id', true);
			$this->forge->createTable('users');
	}

	public function down()
	{
			$this->forge->dropTable('users');
	}
}
