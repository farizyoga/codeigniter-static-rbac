<?php
/**
 * Library Name : Codeigniter Static RBAC
 * Author 		: Fariz Yoga Syahputra <fariz.yoga@gmail.com>
 * Version 		: 1.0.0
 */

defined('BASEPATH') or exit();

class Rbac
{
	protected $CI;
	protected $user;
	protected $role;
	protected $current_page;

	public function __construct()
	{
		$this->CI =& get_instance();
		$this->init_guest_role();
		$this->role = $this->CI->session->userdata('logged_role');
		$this->current_page = $this->get_current_page();
		$this->start();
	}

	public function rules()
	{
		return [
			'ROLE_GUEST' => [
				'home',
				'book',
				'schedule',
				'login',
			],
			'ROLE_ADMIN' => [
				'admin/home'
			]
		];
	}

	public function ignore()
	{
		return [
			'login'
		];
	}

	public function start()
	{
		$this->check();
	}

	public function check()
	{
		$role_access = $this->rules()[$this->role];

		if (!in_array($this->current_page, $role_access) && !in_array($this->current_page, $this->ignore()))
		{
			exit('You are not permitted to access this page');	
		}
	}

	public function get_current_page()
	{
		$directory = $this->CI->router->directory;
		$class = $this->CI->router->class;

		if ($directory)
		{
			return $directory . $class;
		}
		else
		{
			return $class;
		}
	}

	public function init_guest_role()
	{
		$logged_role = $this->CI->session->userdata('logged_role');

		if (NULL === $logged_role)
		{
			$this->CI->session->set_userdata('logged_role', 'ROLE_GUEST');
		}
	}
}