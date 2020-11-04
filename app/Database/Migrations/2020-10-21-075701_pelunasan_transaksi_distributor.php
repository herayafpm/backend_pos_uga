<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PelunasanTransaksiDistributor extends Migration
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
					'transaksi_penjualan_id' => [
						'type' => 'INT',
						'constraint'     => 11,
						'unsigned'          => TRUE,
					],
					'bayar_sebelumnya' => [
						'type' => 'INT',
						'constraint'     => 11,
						'unsigned'          => TRUE,
					],
					'bayar'       => [
              'type' => 'INT',
              'constraint' => 11
					],
					'keterangan'       => [
              'type' => 'TEXT'
					],
					'created_at'       => [
							'type'           => 'DATETIME',
							'default' => date('Y-m-d H:i:s')
					],
			]);
			$this->forge->addKey('id', true);
			$this->forge->addForeignKey('transaksi_penjualan_id','transaksi_penjualan_distributor','id','CASCADE','CASCADE');
			$this->forge->createTable('pelunasan_transaksi_distributor');
	}

	public function down()
	{
			$this->forge->dropTable('pelunasan_transaksi_distributor');
	}
}
