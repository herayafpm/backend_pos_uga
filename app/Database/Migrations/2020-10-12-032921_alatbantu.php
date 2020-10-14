<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlatBantu extends Migration
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
			$this->forge->createTable('alat_bantu');
	}

	public function down()
	{
			$this->forge->dropTable('alat_bantu');
	}
}
