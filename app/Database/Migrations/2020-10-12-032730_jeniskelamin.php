<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class JenisKelamin extends Migration
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
			$this->forge->createTable('jenis_kelamin');
	}

	public function down()
	{
			$this->forge->dropTable('jenis_kelamin');
	}
}
