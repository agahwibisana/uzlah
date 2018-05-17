<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_role extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('M_reusable');
		$this->load->model('M_role');
		$this->load->model('M_permission');
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
			if($this->input->post('id'))
			{
				$return=$this->M_role->create();
				if($return === TRUE)
				{
					$this->session->set_flashdata("message", "<span class='gr'>Role has been successfully created.</span>");
	            	redirect('C_role/read_all');
				}
				else
				{
	            	redirect('C_role/read_all');
				}
			}
			else
			{
				$data['role_id']=$this->M_role->get_role_id();
				$data['viewpage'] = 'administration/V_role_create';
				$data['title'] = 'Create Role';
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
			$data['user']=$this->M_role->get_role();
			$data['viewpage'] = 'administration/V_role_readall';
			$data['title'] = 'View All Roles';
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
				$this->session->set_flashdata("message", "<span>Please choose from one of these roles.</span>");
				redirect('C_role/read_all');
			}
			else
			{
				$result=$this->M_role->get_role_one($param);
				if($result === TRUE)
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Role ID does not exist.<span>");
					redirect('C_role/read_all');
				}
				else
				{
					$data['role']=$this->M_role->get_role_one($param);
					$data['permission']=$this->M_permission->get_permission_one($param);
					$data['viewpage'] = 'administration/V_role_readone';
					$data['title'] = 'View Role '.$param;
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
				$this->session->set_flashdata("message", "<span>Please choose from one of these roles.</span>");
				redirect('C_role/read_all');
			}
			else
			{
				$result=$this->M_role->suspend($param);
				if($result === TRUE)
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Role is already suspended.<span>");
					redirect('C_role/read_all');
				}
				else if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Role does not exist.<span>");
					redirect('C_role/read_all');
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_role/read_all');
				}
				else
				{
					$data['role']=$this->M_role->get_role_one($param);
					redirect('C_role/read_one/'.$param);
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
				$this->session->set_flashdata("message", "<span>Please choose from one of these roles.</span>");
				redirect('C_role/read_all');
			}
			else
			{
				$result=$this->M_role->reactivate($param);
				if($result === TRUE)
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Role is already active.<span>");
					redirect('C_role/read_all');
				}
				else if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Role does not exist.<span>");
					redirect('C_role/read_all');
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_role/read_all');
				}
				else
				{
					$data['role']=$this->M_role->get_role_one($param);
					redirect('C_role/read_one/'.$param);
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
				$result=$this->M_role->update($param);
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
					$this->session->set_flashdata("message", "<span class='gr'>Role has been successfully edited.</span>");
	            	redirect('C_role/read_one/'.$param);				
				}
				else
				{
					$data['role']=$this->M_role->get_role_one($param);
					$data['viewpage'] = 'administration/V_role_update';
					$data['title'] = 'Edit Role '.$param;
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