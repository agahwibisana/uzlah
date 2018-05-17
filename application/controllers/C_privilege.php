<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_privilege extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('M_reusable');
		$this->load->model('M_operation');
		$this->load->model('M_object');
		$this->load->model('M_privilege');
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
			$data['privilege']=$this->M_privilege->get_privilege();
			$data['viewpage'] = 'development/V_privilege_readall';
			$data['title'] = 'View All Privileges';
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
				$this->session->set_flashdata("message", "<span>Please choose from one of these objects.</span>");
				redirect('C_object/read_all');
			}
			else
			{
				$result=$this->M_privilege->create($param);
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
					$this->session->set_flashdata("message", "<span class='gr'>Object has been successfully assigned.</span>");
	            	redirect('C_object/read_one/'.$param);				
				}
				else if($result === "dupl")
				{
					$this->session->set_flashdata("message", "<span class='rd'>Object has already been assigned. Choose another operation.</span>");
	            	redirect('C_object/read_one/'.$param);				
				}
				else
				{
					$data['privilege']=$this->M_privilege->get_privilege_one($param);
					$data['operation']=$this->M_privilege->get_active_operation();
					$data['object']=$param;
					$data['viewpage'] = 'development/V_privilege_create';
					$data['title'] = 'Assign Operation for Object '.$param;
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
				$this->session->set_flashdata("message", "<span>Please choose from one of these objects.</span>");
				redirect('C_object/read_all');
			}
			else
			{
				$result=$this->M_privilege->update($param);
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
					$this->session->set_flashdata("message", "<span class='gr'>Privilege has been successfully edited except for suspended objects.</span>");
	            	redirect('C_object/read_one/'.$param);				
				}
				else
				{
					$data['privilege']=$this->M_privilege->get_privilege_one($param);
					$data['object']=$param;
					$data['viewpage'] = 'development/V_privilege_update';
					$data['title'] = 'Edit Operation for Object '.$param;
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