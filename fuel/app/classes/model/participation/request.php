<?php

class Model_Participation_Request extends Model_Base {
	protected static $_properties = array(
		'id',
		'user_id',
		'group_id',
		'approve',
		'created_at',
		'updated_at',
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_update'),
			'mysql_timestamp' => false,
		),
	);

	protected static $_table_name = 'participation_requests';

}
