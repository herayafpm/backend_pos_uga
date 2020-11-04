<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TransaksiPenjualanDistributor extends Migration
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
					'total_bayar'       => [
              'type' => 'INT',
							'constraint' => 11,
							'default' => 0
					],
					'bayar'       => [
              'type' => 'INT',
							'constraint' => 11,
							'default' => 0
					],
					'status'       => [
              'type' => 'INT',
							'constraint' => 1,
							'default' => 0
					],
					'created_at'       => [
							'type'           => 'DATETIME',
							'default' => date('Y-m-d H:i:s')
					],
			]);
			$this->forge->addKey('id', true);
			$this->forge->addForeignKey('toko_id','toko','id');
			$this->forge->createTable('transaksi_penjualan_distributor');
	}

	public function down()
	{
			$this->forge->dropTable('transaksi_penjualan_distributor');
	}
}
