<?php

use Fuel\Core\Database_Exception;
use Orm\ValidationFailed;

class Controller_Locations extends Controller_Base {
	public function post_update() {
		if ($user = Model_User::auth(Input::json('id'), Input::json('password'))) {
			try {
				$user->update_location(array(
					'latitude' => Input::json('latitude'),
					'longitude' => Input::json('longitude'),
					'altitude' => Input::json('altitude'),
				));

				$this->result['id'] = $user->id;
				$this->result['location'] = array(
					'latitude' => $user->latitude,
					'longitude' => $user->longitude,
					'altitude' => $user->altitude,
				);
				$this->result['updated_location_at'] = $user->updated_location_at();
				return $this->response($this->result);
			} catch (ValidationFailed $e) {
				$this->result['error'] = array(
					'kind' => 'validation',
					'message' => $e->getMessage(),
				);
				return $this->response($this->result, 400);
			} catch (Database_Exception $e) {
				$this->result['error'] = array(
					'kind' => 'database',
					'message' => $e->message,
				);
				return $this->response($this->result, 400);
			}
		}

		$this->result['error'] = array(
			'kind' => 'authentication',
			'message' => 'Authentication failure',
		);
		return $this->response($this->result, 400);
	}

	public function get_group($group_id = 0) {
		if ($user = Model_User::auth(Input::get('id'), Input::get('password'))) {
			if ($group_id == 0 || ($group = Model_Group::find_by_id($group_id)) == null) {
				$this->result['error'] = array(
					'kind' => 'http',
					'message' => 'Not Found',
				);
				return $this->response($this->result, 404);
			}

			if (!$user->is_participation($group_id)) {
				$this->result['error'] = array(
					'kind' => 'participation',
					'message' => 'Not Participation',
				);
				return $this->response($this->result, 400);
			}

			foreach ($group->users as $participation_user) {
				if ($participation_user->id == $user->id) {
					continue;
				}
				$this->result[] = $participation_user->to_array();
			}
			return $this->response($this->result);
		}

		$this->result['error'] = array(
			'kind' => 'authentication',
			'message' => 'Authentication failure',
		);
		return $this->response($this->result, 400);
	}

	public function get_all() {
		if (Input::get('id') < 10000 && $user = Model_User::auth(Input::get('id'), Input::get('password'))) {
			$users = Model_User::find('all', array(
				'where' => array(
					array('id', '!=', $user->id),
				),
			));

			foreach ($users as $user) {
				$this->result[] = $user->to_array();
			}
			return $this->response($this->result);
		}

		$this->result['error'] = array(
			'kind' => 'authentication',
			'message' => 'Authentication failure',
		);
		return $this->response($this->result, 400);
	}
}