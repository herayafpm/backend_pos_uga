<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Toko extends Migration
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
					'nama_toko'       => [
							'type'           => 'VARCHAR',
							'constraint'     => '255',
					],
					'email'       => [
							'type'           => 'VARCHAR',
							'constraint'     => '255',
							'nullable' => true
					],
					'alamat'       => [
							'type'           => 'TEXT',
							'nullable' => true
					],
					'no_telp'       => [
							'type'           => 'VARCHAR',
							'constraint'     => '255',
							'nullable' => true
					],
					'aktif'       => [
							'type'           => 'INT',
							'constraint'     => 1,
							'default' => 1
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
			$this->forge->addForeignKey('user_id','users','id','CASCADE','CASCADE');
			$this->forge->createTable('toko');
	}

	public function down()
	{
			$this->forge->dropTable('toko');
	}
}
