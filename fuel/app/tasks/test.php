<?php

namespace Fuel\Tasks;

use Fuel\Core\Cli;
use Model_User;

class Test {
	public function run() {
		$this->honkan();
		$this->okubo();
		$this->marusho();
	}

	public function honkan() {
		$user = Model_User::find('first', array(
			'where' => array(
				array('id', '=', 2),
			),
		));

		$user->update_location(array(
			'latitude' => 35.698303,
			'longitude' => 139.698116,
			'altitude' => 0,
		));
	}

	public function okubo() {
		$user = Model_User::find('first', array(
			'where' => array(
				array('id', '=', 3),
			),
		));

		$user->update_location(array(
			'latitude' => 35.700722,
			'longitude' => 139.697358,
			'altitude' => 0,
		));
	}

	public function marusho() {
		$user = Model_User::find('first', array(
			'where' => array(
				array('id', '=', 4),
			),
		));

		$user->update_location(array(
			'latitude' => 35.6980472,
			'longitude' => 139.6964353,
			'altitude' => 0,
		));
	}
}
