<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Users extends Migration
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
					'username'       => [
							'type'           => 'VARCHAR',
							'constraint'     => '255',
							'unique' 		 => true,
					],
					'nama'       => [
							'type'           => 'VARCHAR',
							'constraint'     => '255',
					],
					'email'       => [
							'type'           => 'VARCHAR',
							'constraint'     => '255',
							'unique' 		 => true,
					],
					'alamat'       => [
							'type'           => 'TEXT',
					],
					'no_telp'       => [
							'type'           => 'VARCHAR',
							'constraint'     => '255',
					],
					'aktif'       => [
							'type'           => 'INT',
							'constraint'     => 1,
							'default' => 0
					],
					'role_id' => [
						'type' => 'INT',
						'constraint'     => 11,
						'unsigned'          => TRUE,
					],
					'password'       => [
							'type'           => 'VARCHAR',
							'constraint'     => '255',
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
			$this->forge->addForeignKey('role_id','roles','id');
			$this->forge->createTable('users');
	}

	public function down()
	{
			$this->forge->dropTable('users');
	}
}
