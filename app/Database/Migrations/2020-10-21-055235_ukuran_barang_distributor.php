<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UkuranBarang extends Migration
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
					'nama' => [
						'type' => 'VARCHAR',
						'constraint'     => "255",
					],
			]);
			$this->forge->addKey('id', true);
			$this->forge->createTable('ukuran_barang');
	}

	public function down()
	{
			$this->forge->dropTable('ukuran_barang');
	}
}
