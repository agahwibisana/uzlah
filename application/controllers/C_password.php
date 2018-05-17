<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_password extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('M_reusable');
		$this->load->model('M_password');
	}

	public function index()
	{
		redirect('C_home');
    }

	public function update()
	{
		$access=$this->M_reusable->get_session();
		if($access === TRUE)
		{
			if($this->input->post('old_pwd') && $this->input->post('new_pwd'))
			{
				$return=$this->M_password->update();
				if($return === TRUE)
				{
					$this->session->set_flashdata("message", "<span class='gr'>Your password has been successfully updated.</span>");
	            	redirect('C_password/update');
				}
				else
				{
	            	redirect('C_password/update');
				}
			}
			else
			{
				$data['viewpage'] = 'administration/V_password_update';
				$data['title'] = 'Edit Password';
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
	
	public function approve()
	{
		$access=$this->M_reusable->get_session();
		if($access === TRUE)
		{
			if($this->input->post('id') && $this->input->post('new_pwd'))
			{
				$return=$this->M_password->approve();
				if($return === TRUE)
				{
					$this->session->set_flashdata("message", "<span class='gr'>The corresponding user's password has been successfully reset.</span>");
	            	redirect('C_password/approve');
				}
				else
				{
	            	redirect('C_password/approve');
				}
			}
			else
			{
				$data['viewpage'] = 'administration/V_password_approve';
				$data['title'] = 'Reset Password';
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
}

?>