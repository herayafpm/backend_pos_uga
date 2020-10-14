<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Parents extends Migration
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
					'no_kk'       => [
							'type'           => 'VARCHAR',
							'constraint'     => '255',
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
					'agama'       => [
						'type'           => 'INT',
						'constraint'     => 11,
					],
					'no_telp'       => [
							'type'           => 'VARCHAR',
							'constraint'     => '255',
					],
					'alamat'       => [
							'type'           => 'TEXT',
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
			]);
			$this->forge->addKey('id', true);
			$this->forge->createTable('parents');
	}

	public function down()
	{
			$this->forge->dropTable('parents');
	}
}
