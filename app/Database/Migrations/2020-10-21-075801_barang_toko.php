<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BarangToko extends Migration
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
					'toko_id' => [
						'type' => 'INT',
						'constraint'     => 11,
						'unsigned'          => TRUE,
					],
					'barang_distributor_id' => [
						'type' => 'INT',
						'constraint'     => 11,
						'unsigned'          => TRUE,
					],
					'stok'       => [
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
			$this->forge->addForeignKey('barang_distributor_id','barang_distributor','id');
			$this->forge->addForeignKey('toko_id','toko','id','CASCADE','CASCADE');
			$this->forge->createTable('barang_toko');
	}

	public function down()
	{
			$this->forge->dropTable('barang_toko');
	}
}
