<?php

namespace Fuel\Tasks;

use Fuel\Core\Cli;
use Model_User;

class User {

	public function run() {
		Cli::write(
			"\n===========================================" .
			"\nUser List" .
			"\n===========================================\n\n"
		);

		$users = Model_User::find('all');
		foreach ($users as $user) {
			$this->export($user);
		}
	}

	public function create() {
		Cli::write(
			"\n===========================================" .
			"\nUser Create" .
			"\n===========================================\n\n"
		);

		$user = Model_User::forge();
		$user->name = Cli::prompt('name');
		$user->password = sha1(Cli::prompt('password'));

		$save = $user->save();
		Cli::write($save);
		if ($save) {
			$this->export($user);
		}
	}

	public function update() {
		Cli::write(
			"\n===========================================" .
			"\nUser Update" .
			"\n===========================================\n\n"
		);

		$id = Cli::prompt('id');
		$user = Model_User::find('first', array('where' => array(array('id', '=', $conditions))));
		$this->export($user);

		$key = Cli::prompt('key');
		$value = Cli::prompt('value');
		if ($key == 'password') {
			$value = Model_User::password_to_hash($value);
		}

		if(empty($key)) {
			return false;
		}
		$data = array(
			$key => $value,
		);

		$user->set($data);
		$save = $user->save();
		Cli::write($save);
		if ($save) {
			$this->export($user);
		}
	}

	public function delete() {
		Cli::write(
			"\n===========================================" .
			"\nUser Delete" .
			"\n===========================================\n\n"
		);

		$id = Cli::prompt('id');
		$user = Model_User::find('first', array('where' => array(array('id', '=', $conditions))));
		$this->export($user);

		while(true) {
			$is_delete = Cli::prompt('Really? [y/N]');
			if ($is_delete == 'y') {
				$result = $user->delete();
				if ($result) {
					$this->export($user);
				}
				return;
			} else if ($is_delete == 'N') {
				return;
			} else {
				Cli::write('y or N');
			}
		}
	}

	public function all_delete($auto_increment = 10001) {
		while(true) {
			$is_delete = Cli::prompt('Really? [y/N]');
			if ($is_delete == 'y') {
				$delete_row = \DB::delete('users')->execute();
				echo "削除行: " . $delete_row . "\n";
				DB::query('ALTER TABLE ' . self::$_table_name . " AUTO_INCREMENT=${auto_increment}, ALGORITHM=COPY;")->execute();
				return;
			} else if ($is_delete == 'N') {
				return;
			} else {
				Cli::write('y or N');
			}
		}
	}

	private function export($user) {
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
