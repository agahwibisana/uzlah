<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_roleassignment extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('M_reusable');
		$this->load->model('M_user');
		$this->load->model('M_role');
		$this->load->model('M_roleassignment');
	}

	public function index()
	{
		redirect('C_home');
    }

	public function read_all()
	{
		$access=$this->M_reusable->get_session();
		if($access === TRUE)
		{
			$data['user']=$this->M_roleassignment->get_roleassignment();
			$data['viewpage'] = 'administration/V_roleassignment_readall';
			$data['title'] = 'View All Role Assignments';
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

	public function create()
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
				$result=$this->M_roleassignment->create($param);
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
					$this->session->set_flashdata("message", "<span class='gr'>Role has been successfully assigned.</span>");
	            	redirect('C_user/read_one/'.$param);				
				}
				else if($result === "dupl")
				{
					$this->session->set_flashdata("message", "<span class='rd'>Role has already been assigned. Choose another role.</span>");
	            	redirect('C_user/read_one/'.$param);				
				}
				else
				{
					$data['roleassignment']=$this->M_roleassignment->get_roleassignment_one($param);
					$data['role']=$this->M_roleassignment->get_active_role();
					$data['user']=$param;
					$data['viewpage'] = 'administration/V_roleassignment_create';
					$data['title'] = 'Assign Role for User '.$param;
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
				$result=$this->M_roleassignment->update($param);
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
					$this->session->set_flashdata("message", "<span class='gr'>Role Assignment has been successfully edited.  However, suspended role cannot be assigned.</span>");
	            	redirect('C_user/read_one/'.$param);				
				}
				else
				{
					$data['roleassignment']=$this->M_roleassignment->get_roleassignment_one($param);
					$data['user']=$param;
					$data['viewpage'] = 'administration/V_roleassignment_update';
					$data['title'] = 'Edit Role Assignment for User '.$param;
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