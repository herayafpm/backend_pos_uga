<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserForgot extends Migration
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
					'token'       => [
							'type'           => 'VARCHAR',
							'constraint'     => '255',
					],
					'username'       => [
							'type'           => 'VARCHAR',
							'constraint'     => '255',
					],
					'password'       => [
							'type'           => 'VARCHAR',
							'constraint'     => '255',
					],
					'email'       => [
							'type'           => 'VARCHAR',
							'constraint'     => '255',
					],
					'status'       => [
							'type'           => 'INT',
							'constraint'     => 1,
							'default' => 0
					],
					'created_at'       => [
							'type'           => 'DATETIME',
							'default' => date('Y-m-d H:i:s')
					],
			]);
			$this->forge->addKey('id', true);
			$this->forge->createTable('user_forgots');
	}

	public function down()
	{
			$this->forge->dropTable('user_forgots');
	}
}
