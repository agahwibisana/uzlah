<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_home extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if($this->session->userdata('username'))
		{
			$data['viewpage'] = 'V_home';
			$data['title'] = 'Home';
			$data['message'] = $this->session->flashdata('message');
			$this->load->view('V_template',$data);
		}
		else
		{
			$data['viewpage'] = 'V_login';
			$data['title'] = 'Login';
			$data['message'] = $this->session->flashdata('message');
			$this->load->view('V_template',$data);
		}
	}

	public function login_process()
	{
		$this->load->model('M_home');
		$authen=$this->M_home->user_authentication();//authenticate user
		if($authen)
		{
			//store id, friendly name, and location into session
			$userdata=$this->M_home->get_userdata();
			$this->session->set_userdata('username',$userdata[0]['id']);//to identify user id throughout the software
			$this->session->set_userdata('friendly_username',$userdata[0]['friendly_name']);
			$author=$this->M_home->user_authorization();
			if(! $author === FALSE)
			{
				//fetch columns into session variable
				$this->session->set_userdata('menu',serialize($author));
				$module=$this->M_home->get_menu(unserialize(($this->session->userdata('menu'))),'module_id');
				$object=$this->M_home->get_menu(unserialize(($this->session->userdata('menu'))),'object_id');
				$obop=$this->M_home->get_menu(unserialize(($this->session->userdata('menu'))),'obop');
				if($module && $object && $obop)
				{
					//fetch module and menu header into session variable
					$this->session->set_userdata('module',serialize($module));
					$this->session->set_userdata('object',serialize($object));
					$this->session->set_userdata('obop',serialize($obop));
					redirect('C_home');
				}
			}
			else
			{
				$this->session->set_flashdata("message", "<span class='rd'>Attention! Failed to authorize user. Your access is limited.</span>");
				redirect('C_home');
			}
		}
		else
		{
			$this->session->set_flashdata("message", "<span class='rd'>Failed to authenticate user. Please check your username and/or password.</span>");
			redirect('C_home');
		}
	}
	
	//form action at V_header.php
	public function logout_process()
	{
		$this->session->sess_destroy();
		redirect('C_home');
	}
}

?>