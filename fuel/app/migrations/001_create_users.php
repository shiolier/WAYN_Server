<?php

namespace Fuel\Migrations;

class Create_users
{
	public function up()
	{
		\DBUtil::create_table('users', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'name' => array('constraint' => 100, 'type' => 'varchar', 'null' => true),
			'password' => array('constraint' => 255, 'type' => 'varchar'),
			'latitude' => array('type' => 'double', 'null' => true),
			'longitude' => array('type' => 'double', 'null' => true),
			'altitude' => array('type' => 'double', 'null' => true),
			'updated_location_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('users');
	}
}