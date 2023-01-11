<?php

class User {

	private $_db,
			$_data,
			$_sessionName,
			$_isLoggedIn;

	public function __construct($user = null) {
		$this->_db = DB::getInstance();

		$this->_sessionName = Config::get('session/session_name');

		if(!$user) {
			if(Session::exists($this->_sessionName)) {
				$user = Session::get($this->_sessionName);
				if($this->find($user)) {
					$this->_isLoggedIn = true;
				} else {
					$this->logout();
				}
			}
		} else {
			$this->find($user);
		}
	}
	public function create($fields = array()) {
		if(!$this->_db->insert('korisnik', $fields)) {
			throw new Exception('There was a problem creating this account.');
		}
	}

	public function update($fields = array(), $id = null) {

		if(!$id && $this->isLoggedIn()) {
			$id = $this->data()->id;
		}

		if(!$this->_db->update('user', $id, $fields)) {
			throw new Exception('There was a problem updating.');
		}
	}

	public function find($user = null) {
		if($user) {
			// if user had a numeric username this FAILS...
			$field = (is_numeric($user)) ? 'korisnik_id' : 'email'; 
			$data = $this->_db->get('korisnik', array($field, '=', $user));

			if($data->count()) {
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}

	public function login($email = null, $password = null) {

		// check if username has been defined 
		if(!$email && !$password && $this->exists()) {
			Session::put($this->_sessionName, $this->data()->korisnik_id);
		}else {
			$user = $this->find($email);

			if($user) {
				if(password_verify($password, $this->data()->password)) {
					Session::put($this->_sessionName, $this->data()->korisnik_id);
					return true;
				}
			}
		}

		return false;
	}

	public function hasPermission($key) {
		$group = $this->_db->get('groups', array('id', '=', $this->data()->group));
		if($group->count()) {
			$permissions = json_decode($group->first()->permissions, true);
			if($permissions[$key] == true) {
				return true;
			}
		}
		return false;
	}

	public function exists() {
		return (!empty($this->_data)) ? true : false;
	}

	public function logout() {
		$this->_isLoggedIn=false;
		Session::delete($this->_sessionName);
	}

	public function data() {
		return $this->_data;
	}

	public function isLoggedIn() {
		return $this->_isLoggedIn;
	}	

}