<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PenjualanDistributor extends Migration
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
					'transaksi_penjualan_distributor_id' => [
						'type' => 'INT',
						'constraint'     => 11,
						'unsigned'          => TRUE,
					],
					'barang_distributor_id' => [
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
			]);
			$this->forge->addKey('id', true);
			$this->forge->addForeignKey('transaksi_penjualan_distributor_id','transaksi_penjualan_distributor','id','CASCADE','CASCADE');
			$this->forge->addForeignKey('barang_distributor_id','barang_distributor','id','CASCADE','CASCADE');
			$this->forge->createTable('penjualan_distributor');
	}

	public function down()
	{
			$this->forge->dropTable('penjualan_distributor');
	}
}
