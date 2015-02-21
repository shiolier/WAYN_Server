<?php

namespace Fuel\Tasks;

use Fuel\Core\Cli;
use Model_Group;

class Group {

	public function run() {
		Cli::write(
			"\n===========================================" .
			"\nGroup List" .
			"\n===========================================\n\n"
		);

		$groups = Model_Group::find('all');
		foreach ($groups as $group) {
			$this->export($group);
		}
	}

	public function member($id = 0) {
		Cli::write(
			"\n===========================================" .
			"\nGroup Member" .
			"\n===========================================\n\n"
		);
		
		$id = Cli::prompt('id');
		$group = Model_Group::find('first', array('where' => array(array('id', '=', $id))));
		//$this->export($group);

		foreach ($group->users as $user) {
			$this->export_user($user);
		}
	}

	public function create() {
		Cli::write(
			"\n===========================================" .
			"\nGroup Create" .
			"\n===========================================\n\n"
		);

		$group = Model_Group::forge();
		$group->name = Cli::prompt('name');
		$group->leader_id = Cli::prompt('leader_id');

		$save = $group->save();
		Cli::write($save);
		if ($save) {
			$this->export($group);
		}
	}

	public function update() {
		Cli::write(
			"\n===========================================" .
			"\nGroup Update" .
			"\n===========================================\n\n"
		);

		$id = Cli::prompt('id');
		$group = Model_Group::find('first', array('where' => array(array('id', '=', $id))));
		$this->export($group);

		$key = Cli::prompt('key');
		$value = Cli::prompt('value');
		if ($key == 'password') {
			$value = Model_Group::password_to_hash($value);
		}

		if(empty($key)) {
			return false;
		}
		$data = array(
			$key => $value,
		);

		$group->set($data);
		$save = $group->save();
		Cli::write($save);
		if ($save) {
			$this->export($group);
		}
	}

	public function delete() {
		Cli::write(
			"\n===========================================" .
			"\nGroup Delete" .
			"\n===========================================\n\n"
		);

		$id = Cli::prompt('id');
		$group = Model_Group::find('first', array('where' => array(array('id', '=', $id))));
		$this->export($group);

		while(true) {
			$is_delete = Cli::prompt('Really? [y/N]');
			if ($is_delete == 'y') {
				$result = $group->delete();
				if ($result) {
					$this->export($group);
				}
				return;
			} else if ($is_delete == 'N') {
				return;
			} else {
				Cli::write('y or N');
			}
		}
	}

	private function export($group) {
		Cli::write(
			"**************************************\n" .
			"id:                  {$group->id}\n" .
			"name:                {$group->name}\n" .
			"leader_id:           {$group->leader_id}\n" .
			"created_at:          {$group->created_at} ({$group->created_at()})\n" .
			"updated_at:          {$group->updated_at} ({$group->updated_at()})\n"
		);
	}

	private function export_user($user) {
		Cli::write(
			"**************************************\n" .
			"id:                  {$user->id}\n" .
			"name:                {$user->name}\n" .
			"password:            {$user->password}\n" .
			"latitude:            {$user->latitude}\n" .
			"longitude:           {$user->longitude}\n" .
			"altitude:            {$user->altitude}\n" .
			"updated_location_at: {$user->updated_location_at} ({$user->updated_location_at()})\n" .
			"created_at:          {$user->created_at} ({$user->created_at()})\n" .
			"updated_at:          {$user->updated_at} ({$user->updated_at()})\n"
		);
	}
}