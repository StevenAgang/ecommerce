<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

	private $status;
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user');
		$this->status = $this->session->userdata('is_logged_in');
		$this->output->enable_profiler(FALSE);
	}
	
	public function base_data($title,$content): array{
		$view_data['title'] = $title;
		$view_data['content'] = $content;
		$view_data['csrf'] =  array('name' => $this->security->get_csrf_token_name(), 'hash' => $this->security->get_csrf_hash());
		$view_data['error'] = $this->session->flashdata('error');

		return $view_data;
	}
	public function login_user()
	{
		if($this->session->userdata('is_logged_in') !== null)
		{
			if($this->status === false)
			{
				$view_data = $this->base_data('Login','Login to order');
				$this->load->view('users/login',$view_data);
			}
			else
			{
				redirect('register');
			}
		}
		else
		{	
			$view_data = $this->base_data('Login','Login to order');
			$this->load->view('users/login',$view_data);
		}
	}
	public function register()
	{
		if($this->session->userdata('is_logged_in') !== null)
		{
			if($this->status === false)
			{
				$view_data = $this->base_data('Register','Create Account');
				$this->load->view('users/register',$view_data);
			}
			else
			{
				redirect('dashboard');
			}
		}
		else
		{
			$view_data = $this->base_data('Login','Login to order');
			$this->load->view('users/register',$view_data);
		}
	}
	public function forgot_password()
	{
		$view_data = $this->base_data('Forgot Password','Recover your account');
		$this->load->view('users/forgotpassword',$view_data);
	}
	// THIS FUNCTION IS FOR CREATING THE USER ACCOUNT
	public function create_account()
	{
		if($this->user->validation($this->input->post(NULL,TRUE),'register') === true)
		{
			if($this->user->add_account($this->input->post(null,true)) === false)
			{
				redirect(base_url('register'));
			}
			else
			{
				redirect(base_url(''));
			}
		}
		else
		{
			redirect(base_url('register'));
		}
	}
	// THIS FUNCTION IS FOR LOGIN
	public function login()
	{
		if($this->user->validation($this->input->post(NULL,TRUE),'login') === true)
		{
			if($this->user->user_login($this->input->post(NULL,TRUE)) === true)
			{
				redirect(base_url('dashboard'));
			}
			else
			{
				redirect(base_url('login'));
			}
		}
	}
	public function logout()
	{
		$this->session->set_userdata('is_logged_in',false);
		$this->session->sess_destroy('user_data');
		redirect(base_url('login'));
	}
	// THIS FUNCTION IS FOR GUEST LOGIN
	public function main()
	{
		$this->load->view('dashboard/main');
	}
	public function	status()
	{
		$this->load->view('users/status');
	}
}