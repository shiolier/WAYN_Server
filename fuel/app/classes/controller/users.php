<?php

use Fuel\Core\Database_Exception;
use Orm\ValidationFailed;

class Controller_Users extends Controller_Base {

	public function post_regist() {
		try {
			$user = Model_User::forge(array(
				'name' => Input::json('name'),
				'password' => sha1(Input::json('password')),
			));
			$user->name = Input::json('name');
			$user->save();
			$this->result['id'] = $user->id;
			$this->result['created_at'] = $user->created_at();
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

	public function post_update_name() {
		if ($user = Model_User::auth(Input::json('id'), Input::json('password'))) {
			try {
				$user->name = Input::json('name');
				$this->result['result'] = $user->save();
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
}