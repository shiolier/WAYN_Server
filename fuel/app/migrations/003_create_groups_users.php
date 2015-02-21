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
			'approve' => array('type' => 'bool', 'null' => true),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('groups_users');
	}
}