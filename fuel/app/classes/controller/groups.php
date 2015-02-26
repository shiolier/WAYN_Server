<?php

use Fuel\Core\Database_Exception;
use Orm\ValidationFailed;

class Controller_Groups extends Controller_Base {

	public function post_create() {
		if ($user = Model_User::auth(Input::json('id'), Input::json('password'))) {
			try {
				$group = Model_Group::forge(array(
					'name' => Input::json('group_name'),
					'leader_id' => $user->id,
				));
				$group->save();
				$user->groups[] = $group;
				$user->save();

				$this->result['id'] = $group->id;
				$this->result['name'] = $group->name;
				$this->result['leader'] = $user->to_array();
				$this->result['created_at'] = $group->created_at;
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

	public function post_update_name($group_id = 0) {
		if ($user = Model_User::auth(Input::json('id'), Input::json('password'))) {
			if ($group_id <= 0 || ($group = Model_Group::find_by_id($group_id)) == null) {
				$this->result['error'] = array(
					'kind' => 'http',
					'message' => 'Not Found',
				);
				return $this->response($this->result, 404);
			}

			if ($user->id != $group->leader_id) {
				$this->result['error'] = array(
					'kind' => 'authentication',
					'message' => 'Leader only',
				);
				return $this->response($this->result, 400);
			}

			$group->name = Input::json('group_name');
			$this->result['result'] = $group->save();
			return $this->response($this->result);
		}

		$this->result['error'] = array(
			'kind' => 'authentication',
			'message' => 'Authentication failure',
		);
		return $this->response($this->result, 400);
	}

	public function get_belong() {
		if ($user = Model_User::auth(Input::get('id'), Input::get('password'))) {
			foreach ($user->groups as $group) {
				$group_array = array(
					'id' => $group->id,
					'name' => $group->name,
					'leader' => Model_User::find_by_id($group->leader_id)->to_array(),
					'created_at' => $group->created_at,
				);
				$this->result[] = $group_array;
			}
			return $this->response($this->result);
		}

		$this->result['error'] = array(
			'kind' => 'authentication',
			'message' => 'Authentication failure',
		);
		return $this->response($this->result, 400);
	}
	
	public function post_request($group_id = 0) {
		if ($user = Model_User::auth(Input::json('id'), Input::json('password'))) {
			if ($group_id <= 0 || ($group = Model_Group::find_by_id($group_id)) == null) {
				$this->result['error'] = array(
					'kind' => 'http',
					'message' => 'Not Found',
				);
				return $this->response($this->result, 404);
			}

			if ($user->is_participation($group_id)) {
				$this->result['error'] = array(
					'kind' => 'participation',
					'message' => 'Already participate',
				);
				return $this->response($this->result, 400);
			}
			if ($user->is_request($group_id)) {
				$this->result['error'] = array(
					'kind' => 'participation',
					'message' => 'Already requested',
				);
				return $this->response($this->result, 400);
			}

			$user->requests[] = Model_Participation_Request::forge(array('group_id' => $group_id));
			$this->result['result'] = $user->save();
			return $this->response($this->result);
		}

		$this->result['error'] = array(
			'kind' => 'authentication',
			'message' => 'Authentication failure',
		);
		return $this->response($this->result, 400);
	}

	public function get_request($group_id = 0) {
		if ($user = Model_User::auth(Input::get('id'), Input::get('password'))) {
			if ($group_id <= 0 || ($group = Model_Group::find_by_id($group_id)) == null) {
				$this->result['error'] = array(
					'kind' => 'http',
					'message' => 'Not Found',
				);
				return $this->response($this->result, 404);
			}

			if ($user->id != $group->leader_id) {
				$this->result['error'] = array(
					'kind' => 'authentication',
					'message' => 'Leader only',
				);
				return $this->response($this->result, 400);
			}

			$requests = Model_Participation_Request::find('all', array(
				'where' => array(
					array('group_id', '=', $group_id),
				),
			));

			foreach ($requests as $request) {
				$this->result[] = array(
					'id' => $request->id,
					'user' => $request->user->to_array(),
					'request_time' => $request->created_at,
				);
			}
			return $this->response($this->result);
		}

		$this->result['error'] = array(
			'kind' => 'authentication',
			'message' => 'Authentication failure',
		);
		return $this->response($this->result, 400);
	}

	public function post_approve($request_id = 0) {
		if ($user = Model_User::auth(Input::json('id'), Input::json('password'))) {
			if ($request_id <= 0 || ($request = Model_Participation_Request::find_by_id($request_id)) == null) {
				$this->result['error'] = array(
					'kind' => 'http',
					'message' => 'Not Found',
				);
				return $this->response($this->result, 404);
			}

			$group = Model_Group::find_by_id($request->group_id);

			if ($user->id != $group->leader_id) {
				$this->result['error'] = array(
					'kind' => 'authentication',
					'message' => 'Leader only',
				);
				return $this->response($this->result, 400);
			}

			$request_user = Model_User::find_by_id($request->user_id);
			$request_user->groups[] = $group;
			$this->result['result'] = $request_user->save();
			$request->delete();
			return $this->response($this->result);
		}

		$this->result['error'] = array(
			'kind' => 'authentication',
			'message' => 'Authentication failure',
		);
		return $this->response($this->result, 400);
	}

	public function post_reject($request_id = 0) {
		if ($user = Model_User::auth(Input::json('id'), Input::json('password'))) {
			if ($request_id <= 0 || ($request = Model_Participation_Request::find_by_id($request_id)) == null) {
				$this->result['error'] = array(
					'kind' => 'http',
					'message' => 'Not Found',
				);
				return $this->response($this->result, 404);
			}

			$group = Model_Group::find_by_id($request->group_id);

			if ($user->id != $group->leader_id) {
				$this->result['error'] = array(
					'kind' => 'authentication',
					'message' => 'Leader only',
				);
				return $this->response($this->result, 400);
			}

			$this->result['result'] = $request->delete();
			return $this->response($this->result);
		}

		$this->result['error'] = array(
			'kind' => 'authentication',
			'message' => 'Authentication failure',
		);
		return $this->response($this->result, 400);
	}

	public function post_leave($group_id = 0) {
		if ($user = Model_User::auth(Input::json('id'), Input::json('password'))) {
			if ($group_id <= 0 || ($group = Model_Group::find_by_id($group_id)) == null) {
				$this->result['error'] = array(
					'kind' => 'http',
					'message' => 'Not Found',
				);
				return $this->response($this->result, 404);
			}

			if (!$user->is_participation($group_id)) {
				$this->result['error'] = array(
					'kind' => 'authentication',
					'message' => 'Member only',
				);
				return $this->response($this->result, 400);
			}

			if ($user->id == $group->leader_id) {
				$this->result['error'] = array(
					'kind' => 'authentication',
					'message' => 'Leader can not leave',
				);
				return $this->response($this->result, 400);
			}

			unset($group->users[$user->id]);
			$this->result['result'] = $group->save();
			
			return $this->response($this->result);
		}

		$this->result['error'] = array(
			'kind' => 'authentication',
			'message' => 'Authentication failure',
		);
		return $this->response($this->result, 400);
	}

	public function get_info($group_id = 0) {
		if ($user = Model_User::auth(Input::get('id'), Input::get('password'))) {
			if ($group_id <= 0 || ($group = Model_Group::find_by_id($group_id)) == null) {
				$this->result['error'] = array(
					'kind' => 'http',
					'message' => 'Not Found',
				);
				return $this->response($this->result, 404);
			}

			$this->result = array(
				'id' => $group->id,
				'name' => $group->name,
				'leader' => Model_User::find_by_id($group->leader_id)->to_array(),
				'created_at' => $group->created_at,
			);
			
			return $this->response($this->result);
		}

		$this->result['error'] = array(
			'kind' => 'authentication',
			'message' => 'Authentication failure',
		);
		return $this->response($this->result, 400);
	}
}