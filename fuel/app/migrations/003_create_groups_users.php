<?php

namespace Fuel\Migrations;

class Create_groups_users
{
	public function up()
	{
		\DBUtil::create_table('groups_users', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'user_id' => array('constraint' => 11, 'type' => 'int', 'unsigned' => true),
			'group_id' => array('constraint' => 11, 'type' => 'int', 'unsigned' => true),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));

		\DBUtil::create_index('groups_users', array('user_id', 'group_id'), 'user_and_group_id_unique_index', 'UNIQUE');
	}

	public function down()
	{
		\DBUtil::drop_table('groups_users');
	}
}