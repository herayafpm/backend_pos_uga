<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BarangDistributor extends Migration
{
	public function up()
	{
			$this->db->enableForeignKeyChecks();
			$this->forge->addField([
					'id'          => [
							'type'           => 'INT',
							'constraint'     => 11,
							'unsigned'       => true,
							'auto_increment' => true,
					],
					'foto'       => [
							'type' => 'VARCHAR',
							'constraint'     => "255",
							'default'=> 'kosong.png'
					],
					'nama_barang'       => [
							'type' => 'VARCHAR',
							'constraint'     => "255",
					],
					'jenis_barang_id' => [
						'type' => 'INT',
						'constraint'     => 11,
						'unsigned'          => TRUE,
					],
					'ukuran_barang_id' => [
						'type' => 'INT',
						'constraint'     => 11,
						'unsigned'          => TRUE,
					],
					'stok'       => [
							'type' => 'INT',
							'constraint'     => 11,
					],
					'harga_dasar'       => [
							'type' => 'INT',
							'constraint'     => 11,
					],
					'harga_jual'       => [
							'type' => 'INT',
							'constraint'     => 11,
					],
					'keterangan'       => [
							'type' => 'TEXT',
					],
					'created_at'       => [
							'type'           => 'DATETIME',
							'default' => date('Y-m-d H:i:s')
					],
					'updated_at'       => [
							'type'           => 'DATETIME',
							'default' => date('Y-m-d H:i:s')
					],
			]);
			$this->forge->addKey('id', true);
			$this->forge->addForeignKey('jenis_barang_id','jenis_barang','id');
			$this->forge->addForeignKey('ukuran_barang_id','ukuran_barang','id');
			$this->forge->createTable('barang_distributor');
	}

	public function down()
	{
			$this->forge->dropTable('barang_distributor');
	}
}
