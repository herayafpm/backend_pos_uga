<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Karyawan extends Migration
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
					'toko_id'       => [
							'type' => 'INT',
							'constraint'     => 11,
							'unsigned'          => TRUE,
					],
						'created_at'       => [
							'type'           => 'DATETIME',
							'default' => date('Y-m-d H:i:s')
					],
			]);
			$this->forge->addKey('id', true);
			$this->forge->addForeignKey('user_id','users','id','CASCADE','CASCADE');
			$this->forge->addForeignKey('toko_id','toko','id','CASCADE','CASCADE');
			$this->forge->createTable('karyawan');
	}

	public function down()
	{
			$this->forge->dropTable('karyawan');
	}
}
