<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_permission extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('M_reusable');
		$this->load->model('M_privilege');
		$this->load->model('M_role');
		$this->load->model('M_permission');
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
			$data['permission']=$this->M_permission->get_permission();
			$data['viewpage'] = 'administration/V_permission_readall';
			$data['title'] = 'View All Permissions';
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
				$this->session->set_flashdata("message", "<span>Please choose from one of these roles.</span>");
				redirect('C_role/read_all');
			}
			else
			{
				$result=$this->M_permission->create($param);
				if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Role does not exist.<span>");
					redirect('C_role/read_all');
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_role/read_all');
				}
				else if($result === true)
				{
					$this->session->set_flashdata("message", "<span class='gr'>Permission has been successfully assigned.</span>");
	            	redirect('C_role/read_one/'.$param);				
				}
				else if($result === "dupl")
				{
					$this->session->set_flashdata("message", "<span class='rd'>Permission has already been assigned. Choose another permission.</span>");
	            	redirect('C_role/read_one/'.$param);				
				}
				else
				{
					$data['permission']=$this->M_permission->get_permission_one($param);
					$data['privilege']=$this->M_permission->get_active_privilege();
					$data['role']=$param;
					$data['viewpage'] = 'administration/V_permission_create';
					$data['title'] = 'Assign Permission for Role '.$param;
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
				$this->session->set_flashdata("message", "<span>Please choose from one of these roles.</span>");
				redirect('C_role/read_all');
			}
			else
			{
				$result=$this->M_permission->update($param);
				if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Role does not exist.<span>");
					redirect('C_role/read_all');
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_role/read_all');
				}
				else if($result === true)
				{
					$this->session->set_flashdata("message", "<span class='gr'>Permission has been successfully edited.  However, suspended privilege cannot be assigned.</span>");
	            	redirect('C_role/read_one/'.$param);				
				}
				else
				{
					$data['permission']=$this->M_permission->get_permission_one($param);
					$data['role']=$param;
					$data['viewpage'] = 'administration/V_permission_update';
					$data['title'] = 'Edit Permission for Role '.$param;
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