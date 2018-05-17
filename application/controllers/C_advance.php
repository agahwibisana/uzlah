<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_advance extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('M_reusable');
		$this->load->model('M_advance');
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
			if($this->input->post('adv_id'))
			{
				$return=$this->M_advance->create();
				if($return=="notassigned")
				{
					$this->session->set_flashdata("message", "<span class='rd'>You are not assigned to approval structure. Contact your administrator.<span>");
	            	redirect('C_advance/create');
				}
				else if($result === "failed")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Upload failed. Please try again<span>");
					redirect('C_advance/create');
				}
				else
				{
					$this->session->set_flashdata("message", "<span class='gr'>Advance request has been submitted. Now add some items to it!</span>");
	            	redirect('C_advance/read_one/'.$return['id']);
				}

			}
			else
			{
				$data['advid']=$this->M_advance->get_adv_id();
				$data['viewpage'] = 'financeservices/V_advance_create';
				$data['title'] = 'Request Employee Advance';
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
			$data['advance']=$this->M_advance->get_advance();
			$data['viewpage'] = 'financeservices/V_advance_readall';
			$data['title'] = 'View All Employee Advances';
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
				$this->session->set_flashdata("message", "<span>Please choose from one of these employee advances.</span>");
				redirect('C_advance/read_all');
			}
			else
			{
				$result=$this->M_advance->get_header($param);
				if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Advance does not exist.<span>");
					redirect('C_advance/read_all');
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_advance/read_all');
				}
				else
				{
					$data['adv_header']=$this->M_advance->get_header($param);
					$data['adv_line']=$this->M_advance->get_advance_line($param);
					$data['creator_only']=$this->M_advance->creator_only($param);
					$data['approval']=$this->M_advance->get_approval_tree($param,$data['adv_header'],$data['adv_line']);
					$data['approval_but']=$this->M_advance->get_approval_auth($param,$data['adv_header'],$data['adv_line']);
					$data['upload_stat']=$this->M_advance->upload_status($param);
					$data['paid_but']=$this->M_advance->get_payment_auth();
					$data['viewpage'] = 'financeservices/V_advance_readone';
					$data['title'] = 'View Employee Advance '.$param;
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
				$this->session->set_flashdata("message", "<span>Please choose from one of these advances.</span>");
				redirect('C_advance/read_all');
			}
			else
			{
				$result=$this->M_advance->update($param);
				if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Advance does not exist.<span>");
					redirect('C_advance/read_all');
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_advance/read_all');
				}
				else if($result === true)
				{
					$this->session->set_flashdata("message", "<span class='gr'>Advance has been successfully edited.</span>");
	            	redirect('C_advance/read_one/'.$param);				
				}
				else
				{
					$data['advance']=$this->M_advance->get_header($param);
					$data['viewpage'] = 'financeservices/V_advance_update';
					$data['title'] = 'Edit Employee Advance '.$param;
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

	public function cancel()
	{
		$access=$this->M_reusable->get_session();
		if($access === TRUE)
		{
			$param=$this->uri->segment(3);
			if($param=='')
			{
				$this->session->set_flashdata("message", "<span>Please choose from one of these advances.</span>");
				redirect('C_advance/read_all');
			}
			else
			{
				$result=$this->M_advance->cancel($param);
				if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Advance does not exist.<span>");
					redirect('C_advance/read_all');
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_advance/read_all');
				}
				else if($result === true)
				{
					$this->session->set_flashdata("message", "<span class='gr'>Advance has been successfully canceled.</span>");
	            	redirect('C_advance/read_one/'.$param);				
				}
			}
		}
		else
		{
			$this->session->set_flashdata("message", "<span class='rd'>Unauthorized access.</span>");
			redirect('C_home');
		}
	}

	public function create_ts()
	{
		$access=$this->M_reusable->get_session();
		if($access === TRUE)
		{
			$param=$this->uri->segment(3);
			if($param=='')
			{
				$this->session->set_flashdata("message", "<span>Please choose from one of these advances.</span>");
				redirect('C_advance/read_all');
			}
			else
			{
				$result=$this->M_advance->create_ts($param);
				if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Advance does not exist.<span>");
					redirect('C_advance/read_all');
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_advance/read_all');
				}
				else if($result === true)
				{
					$this->session->set_flashdata("message", "<span class='gr'>Item has been successfully added.</span>");
	            	redirect('C_advance/read_one/'.$param);				
				}
				else
				{
					redirect('C_advance/read_one/'.$param);
				}
			}
		}
		else
		{
			$this->session->set_flashdata("message", "<span class='rd'>Unauthorized access.</span>");
			redirect('C_home');
		}
	}

	public function delete_ts()
	{
		$access=$this->M_reusable->get_session();
		if($access === TRUE)
		{
			$param=$this->uri->segment(3);
			$param2=$this->uri->segment(4);
			if($param=='' or $param2=='')
			{
				$this->session->set_flashdata("message", "<span>Please choose from one of these advances.</span>");
				redirect('C_advance/read_all');
			}
			else
			{
				$result=$this->M_advance->delete_ts($param,$param2);
				if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Item does not exist.<span>");
					redirect('C_advance/read_all');
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_advance/read_all');
				}
				else if($result === true)
				{
					$this->session->set_flashdata("message", "<span class='gr'>Item has been successfully deleted.</span>");
	            	redirect('C_advance/read_one/'.$param);				
				}
			}
		}
		else
		{
			$this->session->set_flashdata("message", "<span class='rd'>Unauthorized access.</span>");
			redirect('C_home');
		}
	}

	public function update_ts()
	{
		$access=$this->M_reusable->get_session();
		if($access === TRUE)
		{
			$param=$this->uri->segment(3);
			$param2=$this->uri->segment(4);
			if($param=='' or $param2=='')
			{
				$this->session->set_flashdata("message", "<span>Please choose from one of these advances.</span>");
				redirect('C_advance/read_all');
			}
			else
			{
				$result=$this->M_advance->update_ts($param,$param2);
				if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Item does not exist.<span>");
					redirect('C_advance/read_all');
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_advance/read_all');
				}
				else if($result === true)
				{
					$this->session->set_flashdata("message", "<span class='gr'>Item has been successfully edited.</span>");
	            	redirect('C_advance/read_one/'.$param);				
				}
				else
				{
					$data['advance_list']=$this->M_advance->get_adv_list($param,$param2);
					$data['viewpage'] = 'financeservices/V_advance_update_ts';
					$data['title'] = 'Edit Employee Advance Item '.$param;
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

	public function approve()
	{
		$access=$this->M_reusable->get_session();
		if($access === TRUE)
		{
			$param=$this->uri->segment(3);
			if($param=='')
			{
				$this->session->set_flashdata("message", "<span>Please choose from one of these advances.</span>");
				redirect('C_advance/read_all');
			}
			else
			{
				$data['adv_header']=$this->M_advance->get_header($param);
				$data['adv_line']=$this->M_advance->get_advance_line($param);
				$result=$this->M_advance->approve($param,$data['adv_header'],$data['adv_line']);
				if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Advance does not exist.<span>");
					redirect('C_advance/read_all');
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_advance/read_all');
				}
				else if($result === true)
				{
					$this->session->set_flashdata("message", "<span class='gr'>Advance has been successfully approved/rejected.</span>");
	            	redirect('C_advance/read_one/'.$param);				
				}
			}
		}
		else
		{
			$this->session->set_flashdata("message", "<span class='rd'>Unauthorized access.</span>");
			redirect('C_home');
		}
	}

	public function pay()
	{
		$access=$this->M_reusable->get_session();
		if($access === TRUE)
		{
			$param=$this->uri->segment(3);
			if($param=='')
			{
				$this->session->set_flashdata("message", "<span>Please choose from one of these advances.</span>");
				redirect('C_advance/read_all');
			}
			else
			{
				$result=$this->M_advance->pay($param);
				if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Advance does not exist.<span>");
					redirect('C_advance/read_all');
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_advance/read_all');
				}
				else if($result === true)
				{
					$this->session->set_flashdata("message", "<span class='gr'>Advance has been successfully set to paid.</span>");
	            	redirect('C_advance/read_one/'.$param);				
				}
			}
		}
		else
		{
			$this->session->set_flashdata("message", "<span class='rd'>Unauthorized access.</span>");
			redirect('C_home');
		}
	}

	public function return()
	{
		$access=$this->M_reusable->get_session();
		if($access === TRUE)
		{
			$param=$this->uri->segment(3);
			$param2=$this->uri->segment(4);
			if($param=='' or $param2=='')
			{
				$this->session->set_flashdata("message", "<span>Please choose from one of these advances.</span>");
				redirect('C_advance/read_all');
			}
			else
			{
				$result=$this->M_advance->return($param,$param2);
				if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Item does not exist.<span>");
					redirect('C_advance/read_one/'.$param);
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_advance/read_all');
				}
				else if($result === "failed")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Upload failed. Please try again<span>");
					redirect('C_advance/read_one/'.$param);
				}
				else if($result === "download")
				{
					$message=$this->session->set_flashdata("message", "<span class='gr'>File will be downloaded in a moment.<span>");
					redirect('C_advance/read_one/'.$param);
				}				
				else if($result === true)
				{
					$this->session->set_flashdata("message", "<span class='gr'>Item has been successfully requested for settlement.</span>");
	            	redirect('C_advance/read_one/'.$param);				
				}
				else
				{
					$data['advance_list']=$this->M_advance->get_adv_list($param,$param2);
					$data['viewpage'] = 'financeservices/V_advance_return';
					$data['title'] = 'Request to Settle Item '.$param2.' on Employee Advance '.$param;
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

	public function settle()
	{
		$access=$this->M_reusable->get_session();
		if($access === TRUE)
		{
			$param=$this->uri->segment(3);
			if($param=='')
			{
				$this->session->set_flashdata("message", "<span>Please choose from one of these advances.</span>");
				redirect('C_advance/read_all');
			}
			else
			{
				$result=$this->M_advance->settle($param);
				if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Advance does not exist.<span>");
					redirect('C_advance/read_all');
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_advance/read_all');
				}
				else if($result === "failed")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Upload failed. Please try again<span>");
					redirect('C_advance/read_one/'.$param);
				}
				else if($result === "upload")
				{
					$message=$this->session->set_flashdata("message", "<span class='gr'>Settlement Document has been successfully uploaded.<span>");
					redirect('C_advance/read_one/'.$param);
				}
				else if($result === "notcomplete")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>You can't settle the amount. Upload settlement document first.<span>");
					redirect('C_advance/read_one/'.$param);
				}
				else if($result === true)
				{
					$this->session->set_flashdata("message", "<span class='gr'>Advance has been successfully settled.</span>");
	            	redirect('C_advance/read_one/'.$param);				
				}
				else
				{
					$data['advance_list']=$this->M_advance->get_advance_line($param);
					$data['viewpage'] = 'financeservices/V_advance_settle';
					$data['title'] = 'Settle Employee Advance '.$param;
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