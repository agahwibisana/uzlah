<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_object extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('M_reusable');
		$this->load->model('M_object');
		$this->load->model('M_privilege');
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
				$return=$this->M_object->create();
				if($return === TRUE)
				{
					$this->session->set_flashdata("message", "<span class='gr'>Object has been successfully created.</span>");
	            	redirect('C_object/read_all');
				}
				else
				{
	            	redirect('C_object/read_all');
				}
			}
			else
			{
				$data['object_id']=$this->M_object->get_object_id();
				$data['sort']=$this->M_object->get_sort();
				$data['module']=$this->M_object->get_active_module();
				$data['viewpage'] = 'development/V_object_create';
				$data['title'] = 'Create Object';
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
			$data['object']=$this->M_object->get_object();
			$data['viewpage'] = 'development/V_object_readall';
			$data['title'] = 'View All Objects';
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
				$this->session->set_flashdata("message", "<span>Please choose from one of these objects.</span>");
				redirect('C_object/read_all');
			}
			else
			{
				$result=$this->M_object->get_object_one($param);
				if($result === TRUE)
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Object ID does not exist.<span>");
					redirect('C_object/read_all');
				}
				else
				{
					$data['object']=$this->M_object->get_object_one($param);
					$data['privilege']=$this->M_privilege->get_privilege_one($param);
					$data['viewpage'] = 'development/V_object_readone';
					$data['title'] = 'View Object '.$param;
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
				$this->session->set_flashdata("message", "<span>Please choose from one of these objects.</span>");
				redirect('C_object/read_all');
			}
			else
			{
				$result=$this->M_object->suspend($param);
				if($result === TRUE)
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Object is already suspended.<span>");
					redirect('C_object/read_all');
				}
				else if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Object does not exist.<span>");
					redirect('C_object/read_all');
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_object/read_all');
				}
				else
				{
					$data['object']=$this->M_object->get_object_one($param);
					redirect('C_object/read_one/'.$param);
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
				$this->session->set_flashdata("message", "<span>Please choose from one of these objects.</span>");
				redirect('C_object/read_all');
			}
			else
			{
				$result=$this->M_object->reactivate($param);
				if($result === TRUE)
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Object is already active.<span>");
					redirect('C_object/read_all');
				}
				else if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Object does not exist.<span>");
					redirect('C_object/read_all');
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_object/read_all');
				}
				else
				{
					$data['object']=$this->M_object->get_object_one($param);
					redirect('C_object/read_one/'.$param);
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
				$this->session->set_flashdata("message", "<span>Please choose from one of these objects.</span>");
				redirect('C_object/read_all');
			}
			else
			{
				$result=$this->M_object->update($param);
				if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Object does not exist.<span>");
					redirect('C_object/read_all');
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_object/read_all');
				}
				else if($result === true)
				{
					$this->session->set_flashdata("message", "<span class='gr'>Object has been successfully edited.</span>");
	            	redirect('C_object/read_one/'.$param);				
				}
				else
				{
					$data['object']=$this->M_object->get_object_one($param);
					$data['module']=$this->M_object->get_active_module();
					$data['viewpage'] = 'development/V_object_update';
					$data['title'] = 'Edit Object '.$param;
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