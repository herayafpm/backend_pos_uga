<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Distributor extends Migration
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
					'user_id' => [
						'type' => 'INT',
						'constraint'     => 11,
						'unsigned'          => TRUE,
					],
					'nama_distributor'       => [
							'type' => 'VARCHAR',
							'constraint'     => "255",
					],
					'created_at'       => [
							'type'           => 'DATETIME',
							'default' => date('Y-m-d H:i:s')
					],
					'updated_at'       => [
							'type'           => 'DATETIME',
							'default' => date('Y-m-d H:i:s')
					],
			]);
			$this->forge->addKey('id', true);
			$this->forge->addForeignKey('user_id','users','id');
			$this->forge->createTable('distributor');
	}

	public function down()
	{
			$this->forge->dropTable('distributor');
	}
}
