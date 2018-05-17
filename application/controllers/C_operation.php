<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_operation extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('M_reusable');
		$this->load->model('M_operation');
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
				$return=$this->M_operation->create();
				if($return === TRUE)
				{
					$this->session->set_flashdata("message", "<span class='gr'>Operation has been successfully created.</span>");
	            	redirect('C_operation/read_all');
				}
				else
				{
	            	redirect('C_operation/read_all');
				}
			}
			else
			{
				$data['operation_id']=$this->M_operation->get_operation_id();
				$data['sort']=$this->M_operation->get_sort();
				$data['viewpage'] = 'development/V_operation_create';
				$data['title'] = 'Create Operation';
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
			$data['operation']=$this->M_operation->get_operation();
			$data['viewpage'] = 'development/V_operation_readall';
			$data['title'] = 'View All Operations';
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
				$this->session->set_flashdata("message", "<span>Please choose from one of these operations.</span>");
				redirect('C_operation/read_all');
			}
			else
			{
				$result=$this->M_operation->get_operation_one($param);
				if($result === TRUE)
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Operation ID does not exist.<span>");
					redirect('C_operation/read_all');
				}
				else
				{
					$data['operation']=$this->M_operation->get_operation_one($param);				    		
					$data['viewpage'] = 'development/V_operation_readone';
					$data['title'] = 'View Operation '.$param;
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

	public function update()
	{
		$access=$this->M_reusable->get_session();
		if($access === TRUE)
		{
			$param=$this->uri->segment(3);
			if($param=='')
			{
				$this->session->set_flashdata("message", "<span>Please choose from one of these operations.</span>");
				redirect('C_operation/read_all');
			}
			else
			{
				$result=$this->M_operation->update($param);
				if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Operation does not exist.<span>");
					redirect('C_operation/read_all');
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_operation/read_all');
				}
				else if($result === true)
				{
					$this->session->set_flashdata("message", "<span class='gr'>Operation has been successfully edited.</span>");
	            	redirect('C_operation/read_one/'.$param);				
				}
				else
				{
					$data['operation']=$this->M_operation->get_operation_one($param);
					$data['viewpage'] = 'development/V_operation_update';
					$data['title'] = 'Edit Operation '.$param;
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