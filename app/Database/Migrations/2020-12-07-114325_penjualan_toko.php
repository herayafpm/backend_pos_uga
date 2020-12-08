<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PenjualanToko extends Migration
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
			'transaksi_penjualan_toko_id' => [
				'type' => 'INT',
				'constraint'     => 11,
				'unsigned'          => TRUE,
			],
			'barang_toko_id' => [
				'type' => 'INT',
				'constraint'     => 11,
				'unsigned'          => TRUE,
			],
			'jumlah_barang'       => [
				'type' => 'INT',
				'constraint' => 11
			],
			'harga_jual'       => [
				'type' => 'INT',
				'constraint' => 11
			],
			'harga_dasar'       => [
				'type' => 'INT',
				'constraint' => 11
			],
		]);
		$this->forge->addKey('id', true);
		$this->forge->addForeignKey('transaksi_penjualan_toko_id', 'transaksi_penjualan_toko', 'id', 'CASCADE', 'CASCADE');
		$this->forge->addForeignKey('barang_toko_id', 'barang_toko', 'id', 'CASCADE', 'CASCADE');
		$this->forge->createTable('penjualan_toko');
	}

	public function down()
	{
		$this->forge->dropTable('penjualan_toko');
	}
}
