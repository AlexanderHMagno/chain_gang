<?php 

class Session {
	private $admin_id;
	public $username;
	private $last_login;
	public const MAX_LOGIN_AGE = 60*60*24;


	public function __construct () {
		session_start();
		$this->check_stored_login();
	}

	public function login ($admin) {
		if ($admin) {
			// prevent session fixation attacks
			session_regenerate_id();
			
			$this->admin_id = $_SESSION['admin_id'] = $admin->id;
			$this->username = $_SESSION['username'] = $admin->username;
			$this->last_login = $_SESSION['last_login'] = time();
		}

		return true;
	}

	public function is_logged_in () {
		return isset($this->admin_id) && $this->last_login_is_correct();
	}

	public function logout () {

		unset($_SESSION['admin_id']);
		unset($_SESSION['username']);
		unset($_SESSION['last_login']);

		unset($this->admin_id);
		unset($this->username);
		unset($this->last_login);

		return true;
	}

	public function message ($msg = "") {
		if (!empty($msg)) {
			//this is where we are gonna set the message; 
			$_SESSION['message'] = $msg;
			return true;
		} else {
			//read the messages;
			return $_SESSION['message'] ?? "";
		}
	}

	public function unset_message () {
		unset($_SESSION['message']);
	}

	private function check_stored_login () {
		if(isset($_SESSION['admin_id'])) {
			$this->admin_id = $_SESSION['admin_id'];
			$this->username = $_SESSION['username'];
			$this->last_login = $_SESSION['last_login'];
		}
	}

	private function last_login_is_correct () {
		if (!isset($this->last_login)) {
			return false;
		} elseif ( ($this->last_login + self::MAX_LOGIN_AGE) < time()) {
			$this->logout();
			return false;
		} else {
			return true;
		}
	}


}

?>