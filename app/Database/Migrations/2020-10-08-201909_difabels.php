<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Difabels extends Migration
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
					'pendaftar_id'       => [
							'type'           => 'INT',
							'constraint'     => 11,
					],
					'nik'       => [
							'type'           => 'VARCHAR',
							'constraint'     => '255',
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
					'no_dtks'       => [
							'type'           => 'VARCHAR',
							'constraint'     => '255',
							'null'			 => true
					],
					'jenis_disabilitas'       => [
						'type'           => 'INT',
						'constraint'     => 11,
					],
					'alat_bantu'       => [
						'type'           => 'INT',
						'constraint'     => 11,
					],
					'fasilitas_kesehatan'       => [
						'type'           => 'INT',
						'constraint'     => 11,
					],
					'keterampilan'       => [
						'type'           => 'INT',
						'constraint'     => 11,
					],
					'organisasi'       => [
						'type'           => 'INT',
						'constraint'     => 11,
					],
					'pekerjaan'       => [
						'type'           => 'INT',
						'constraint'     => 11,
					],
					'kebutuhan_pelatihan'       => [
						'type'           => 'INT',
						'constraint'     => 11,
					],
					'kebutuhan_perawatan'       => [
						'type'           => 'INT',
							'constraint'     => 11,
					],
					'kondisi_difabel'       => [
						'type'           => 'INT',
							'constraint'     => 11,
					],
					'permasalahan'       => [
						'type'           => 'TEXT',
					],
					'foto'       => [
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
					'status'       => [
							'type'           => 'INT',
							'constraint'     => 1,
							'default' => 0
					],
					'kondisi_orang_tua'       => [
						'type'           => 'INT',
						'constraint'     => 11,
					],
					'created_at'       => [
							'type'           => 'DATETIME',
					],
					'updated_at'       => [
							'type'           => 'DATETIME',
					],
			]);
			$this->forge->addKey('id', true);
			$this->forge->createTable('difabels');
	}

	public function down()
	{
			$this->forge->dropTable('difabels');
	}
}
