<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RiwayatStokBarangDistributor extends Migration
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
					'barang_id' => [
						'type' => 'INT',
						'constraint'     => 11,
						'unsigned'          => TRUE,
					],
					'stok_sekarang'       => [
							'type' => 'INT',
							'constraint'     => 11,
					],
					'stok_perubahan'       => [
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
			]);
			$this->forge->addKey('id', true);
			$this->forge->addForeignKey('barang_id','barang_distributor','id');
			$this->forge->createTable('riwayat_stok_barang_distributor');
	}

	public function down()
	{
			$this->forge->dropTable('riwayat_stok_barang_distributor');
	}
}
