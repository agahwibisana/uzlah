<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_module extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('M_reusable');
		$this->load->model('M_module');
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
				$return=$this->M_module->create();
				if($return === TRUE)
				{
					$this->session->set_flashdata("message", "<span class='gr'>Module has been successfully created.</span>");
	            	redirect('C_module/read_all');
				}
				else
				{
	            	redirect('C_module/read_all');
				}
			}
			else
			{
				$data['module_id']=$this->M_module->get_module_id();
				$data['sort']=$this->M_module->get_sort();
				$data['viewpage'] = 'development/V_module_create';
				$data['title'] = 'Create Module';
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
			$data['module']=$this->M_module->get_module();
			$data['viewpage'] = 'development/V_module_readall';
			$data['title'] = 'View All Modules';
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
				$this->session->set_flashdata("message", "<span>Please choose from one of these modules.</span>");
				redirect('C_module/read_all');
			}
			else
			{
				$result=$this->M_module->get_module_one($param);
				if($result === TRUE)
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Module ID does not exist.<span>");
					redirect('C_module/read_all');
				}
				else
				{
					$data['module']=$this->M_module->get_module_one($param);				    		
					$data['viewpage'] = 'development/V_module_readone';
					$data['title'] = 'View Module '.$param;
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
				$this->session->set_flashdata("message", "<span>Please choose from one of these modules.</span>");
				redirect('C_module/read_all');
			}
			else
			{
				$result=$this->M_module->suspend($param);
				if($result === TRUE)
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Module is already suspended.<span>");
					redirect('C_module/read_all');
				}
				else if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Module does not exist.<span>");
					redirect('C_module/read_all');
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_module/read_all');
				}
				else
				{
					$data['module']=$this->M_module->get_module_one($param);
					redirect('C_module/read_one/'.$param);
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
				$this->session->set_flashdata("message", "<span>Please choose from one of these modules.</span>");
				redirect('C_module/read_all');
			}
			else
			{
				$result=$this->M_module->reactivate($param);
				if($result === TRUE)
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Module is already active.<span>");
					redirect('C_module/read_all');
				}
				else if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Module does not exist.<span>");
					redirect('C_module/read_all');
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_module/read_all');
				}
				else
				{
					$data['module']=$this->M_module->get_module_one($param);
					redirect('C_module/read_one/'.$param);
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
				$this->session->set_flashdata("message", "<span>Please choose from one of these modules.</span>");
				redirect('C_module/read_all');
			}
			else
			{
				$result=$this->M_module->update($param);
				if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Module does not exist.<span>");
					redirect('C_module/read_all');
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_module/read_all');
				}
				else if($result === true)
				{
					$this->session->set_flashdata("message", "<span class='gr'>Module has been successfully edited.</span>");
	            	redirect('C_module/read_one/'.$param);				
				}
				else
				{
					$data['module']=$this->M_module->get_module_one($param);
					$data['viewpage'] = 'development/V_module_update';
					$data['title'] = 'Edit Module '.$param;
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