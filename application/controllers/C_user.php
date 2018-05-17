<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_user extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('M_reusable');
		$this->load->model('M_user');
		$this->load->model('M_roleassignment');
	}

	public function index()
	{
		redirect('C_home');
    }

	public function create()
	{
		$access=$this->M_reusable->get_session();
		if($access === TRUE)
		{
			if($this->input->post('user_id'))
			{
				$return=$this->M_user->create();
				if($return === TRUE)
				{
					$this->session->set_flashdata("message", "<span class='gr'>User has been successfully created.</span>");
	            	redirect('C_user/read_all');
				}
				else
				{
	            	redirect('C_user/read_all');
				}
			}
			else
			{
				$data['viewpage'] = 'administration/V_user_create';
				$data['title'] = 'Create User';
				$data['message'] = $this->session->flashdata('message');
				$this->load->view('V_template',$data);
			}
		}
		else
		{
			$data['title'] = 'Unauthorized Access';
			$data['viewpage'] = 'V_notauthorized';
			$data['message'] = $this->session->flashdata('message');
			$this->load->view('V_template',$data);
		}
	}

	public function read_all()
	{
		$access=$this->M_reusable->get_session();
		if($access === TRUE)
		{
			$data['user']=$this->M_user->get_user();
			$data['viewpage'] = 'administration/V_user_readall';
			$data['title'] = 'View All Users';
			$data['message'] = $this->session->flashdata('message');
			$this->load->view('V_template',$data);
		}
		else
		{
			$data['title'] = 'Unauthorized Access';
			$data['message'] = $this->session->flashdata('message');
			$data['viewpage'] = 'V_notauthorized';
			$this->load->view('V_template',$data);
		}
	}

	public function read_one()
	{
		$access=$this->M_reusable->get_session();
		if($access === TRUE)
		{
			$param=$this->uri->segment(3);
			if($param=='')
			{
				$this->session->set_flashdata("message", "<span>Please choose from one of these users.</span>");
				redirect('C_user/read_all');
			}
			else
			{
				$result=$this->M_user->get_user_one($param);
				if($result === TRUE)
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>User ID does not exist.<span>");
					redirect('C_user/read_all');
				}
				else
				{
					$data['user']=$this->M_user->get_user_one($param);
					$data['roleassignment']=$this->M_roleassignment->get_roleassignment_one($param);
					$data['viewpage'] = 'administration/V_user_readone';
					$data['title'] = 'View User '.$param;
					$data['message'] = $this->session->flashdata('message');
					$this->load->view('V_template',$data);
				}
			}
		}
		else
		{
			$data['title'] = 'Unauthorized Access';
			$data['viewpage'] = 'V_notauthorized';
			$data['message'] = $this->session->flashdata('message');
			$this->load->view('V_template',$data);
		}
	}

	public function suspend()
	{
		$access=$this->M_reusable->get_session();
		if($access === TRUE)
		{
			$param=$this->uri->segment(3);
			if($param=='')
			{
				$this->session->set_flashdata("message", "<span>Please choose from one of these users.</span>");
				redirect('C_user/read_all');
			}
			else
			{
				$result=$this->M_user->suspend($param);
				if($result === TRUE)
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>User is already suspended.<span>");
					redirect('C_user/read_all');
				}
				else if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>User does not exist.<span>");
					redirect('C_user/read_all');
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_user/read_all');
				}
				else
				{
					$data['user']=$this->M_user->get_user_one($param);
					redirect('C_user/read_one/'.$param);
				}
			}
		}
		else
		{
			$this->session->set_flashdata("message", "<span class='rd'>Unauthorized access.</span>");
			redirect('C_home');
		}
	}

	public function reactivate()
	{
		$access=$this->M_reusable->get_session();
		if($access === TRUE)
		{
			$param=$this->uri->segment(3);
			if($param=='')
			{
				$this->session->set_flashdata("message", "<span>Please choose from one of these users.</span>");
				redirect('C_user/read_all');
			}
			else
			{
				$result=$this->M_user->reactivate($param);
				if($result === TRUE)
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>User is already active.<span>");
					redirect('C_user/read_all');
				}
				else if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>User does not exist.<span>");
					redirect('C_user/read_all');
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_user/read_all');
				}
				else
				{
					$data['user']=$this->M_user->get_user_one($param);
					redirect('C_user/read_one/'.$param);
				}
			}
		}
		else
		{
			$this->session->set_flashdata("message", "<span class='rd'>Unauthorized access.</span>");
			redirect('C_home');
		}
	}

	public function update()
	{
		$access=$this->M_reusable->get_session();
		if($access === TRUE)
		{
			$param=$this->uri->segment(3);
			if($param=='')
			{
				$this->session->set_flashdata("message", "<span>Please choose from one of these users.</span>");
				redirect('C_user/read_all');
			}
			else
			{
				$result=$this->M_user->update($param);
				if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>User does not exist.<span>");
					redirect('C_user/read_all');
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_user/read_all');
				}
				else if($result === true)
				{
					$this->session->set_flashdata("message", "<span class='gr'>User has been successfully edited.</span>");
	            	redirect('C_user/read_one/'.$param);				
				}
				else
				{
					$data['user']=$this->M_user->get_user_one($param);
					$data['viewpage'] = 'administration/V_user_update';
					$data['title'] = 'Edit User '.$param;
					$data['message'] = $this->session->flashdata('message');
					$this->load->view('V_template',$data);
				}
			}
		}
		else
		{
			$this->session->set_flashdata("message", "<span class='rd'>Unauthorized access.</span>");
			redirect('C_home');
		}
	}
}

?>