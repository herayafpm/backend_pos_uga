<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class KondisiOrangTua extends Migration
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
					'nama'       => [
							'type'           => 'VARCHAR',
							'constraint'     => '255',
					],
			]);
			$this->forge->addKey('id', true);
			$this->forge->createTable('kondisi_orang_tua');
	}

	public function down()
	{
			$this->forge->dropTable('kondisi_orang_tua');
	}
}
