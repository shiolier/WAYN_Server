<?php

namespace Fuel\Migrations;

class Create_participation_requests
{
	public function up()
	{
		\DBUtil::create_table('participation_requests', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'user_id' => array('constraint' => 11, 'type' => 'int', 'unsigned' => true),
			'group_id' => array('constraint' => 11, 'type' => 'int', 'unsigned' => true),
			'approve' => array('type' => 'bool', 'null' => true),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));

		\DBUtil::create_index('participation_requests', array('user_id', 'group_id'), 'user_and_group_id_unique_index', 'UNIQUE');
	}

	public function down()
	{
		\DBUtil::drop_table('participation_requests');
	}
}