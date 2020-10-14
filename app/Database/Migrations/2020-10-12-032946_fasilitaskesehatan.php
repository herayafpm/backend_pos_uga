<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FasilitasKesehatan extends Migration
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
			$this->forge->createTable('fasilitas_kesehatan');
	}

	public function down()
	{
			$this->forge->dropTable('fasilitas_kesehatan');
	}
}
