<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class JenisBarangDistributor extends Migration
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
			$this->forge->createTable('jenis_barang');
	}

	public function down()
	{
			$this->forge->dropTable('jenis_barang');
	}
}
