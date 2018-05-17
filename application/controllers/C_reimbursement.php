<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_reimbursement extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('M_reusable');
		$this->load->model('M_reimbursement');
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
			if($this->input->post('rmb_id'))
			{
				$return=$this->M_reimbursement->create();
				if($return=="notassigned")
				{
					$this->session->set_flashdata("message", "<span class='rd'>You are not assigned to approval structure. Contact your administrator.<span>");
	            	redirect('C_reimbursement/create');
				}
				else if($result === "failed")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Upload failed. Please try again<span>");
					redirect('C_reimbursement/create');
				}
				else
				{
					$this->session->set_flashdata("message", "<span class='gr'>Reimbursement request has been submitted. Now add some items to it!</span>");
	            	redirect('C_reimbursement/read_one/'.$return['id']);
				}

			}
			else
			{
				$data['rmbid']=$this->M_reimbursement->get_rmb_id();
				$data['viewpage'] = 'financeservices/V_reimbursement_create';
				$data['title'] = 'Request Reimbursement';
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
			$data['reimbursement']=$this->M_reimbursement->get_reimbursement();
			$data['viewpage'] = 'financeservices/V_reimbursement_readall';
			$data['title'] = 'View All Reimbursements';
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
				$this->session->set_flashdata("message", "<span>Please choose from one of these reimbursements.</span>");
				redirect('C_reimbursement/read_all');
			}
			else
			{
				$result=$this->M_reimbursement->get_header($param);
				if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Reimbursement does not exist.<span>");
					redirect('C_reimbursement/read_all');
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_reimbursement/read_all');
				}
				else
				{
					$data['rmb_header']=$this->M_reimbursement->get_header($param);
					$data['rmb_line']=$this->M_reimbursement->get_reimbursement_line($param);
					$data['creator_only']=$this->M_reimbursement->creator_only($param);
					$data['approval']=$this->M_reimbursement->get_approval_tree($param,$data['rmb_header'],$data['rmb_line']);
					$data['approval_but']=$this->M_reimbursement->get_approval_auth($param,$data['rmb_header'],$data['rmb_line']);
					$data['upload_stat']=$this->M_reimbursement->upload_status($param);
					$data['paid_but']=$this->M_reimbursement->get_payment_auth();
					$data['viewpage'] = 'financeservices/V_reimbursement_readone';
					$data['title'] = 'View Reimbursement '.$param;
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
				$this->session->set_flashdata("message", "<span>Please choose from one of these reimbursements.</span>");
				redirect('C_reimbursement/read_all');
			}
			else
			{
				$result=$this->M_reimbursement->update($param);
				if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Reimbursement does not exist.<span>");
					redirect('C_reimbursement/read_all');
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_reimbursement/read_all');
				}
				else if($result === true)
				{
					$this->session->set_flashdata("message", "<span class='gr'>Reimbursement has been successfully edited.</span>");
	            	redirect('C_reimbursement/read_one/'.$param);				
				}
				else
				{
					$data['reimbursement']=$this->M_reimbursement->get_header($param);
					$data['viewpage'] = 'financeservices/V_reimbursement_update';
					$data['title'] = 'Edit Reimbursement '.$param;
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
				$this->session->set_flashdata("message", "<span>Please choose from one of these reimbursements.</span>");
				redirect('C_reimbursement/read_all');
			}
			else
			{
				$result=$this->M_reimbursement->cancel($param);
				if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Reimbursement does not exist.<span>");
					redirect('C_reimbursement/read_all');
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_reimbursement/read_all');
				}
				else if($result === true)
				{
					$this->session->set_flashdata("message", "<span class='gr'>Reimbursement has been successfully canceled.</span>");
	            	redirect('C_reimbursement/read_one/'.$param);				
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
				$this->session->set_flashdata("message", "<span>Please choose from one of these reimbursements.</span>");
				redirect('C_reimbursement/read_all');
			}
			else
			{
				$result=$this->M_reimbursement->create_ts($param);
				if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Reimbursement does not exist.<span>");
					redirect('C_reimbursement/read_all');
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_reimbursement/read_all');
				}
				else if($result === true)
				{
					$this->session->set_flashdata("message", "<span class='gr'>Item has been successfully added.</span>");
	            	redirect('C_reimbursement/read_one/'.$param);				
				}
				else
				{
					redirect('C_reimbursement/read_one/'.$param);
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
				$this->session->set_flashdata("message", "<span>Please choose from one of these reimbursements.</span>");
				redirect('C_reimbursement/read_all');
			}
			else
			{
				$result=$this->M_reimbursement->delete_ts($param,$param2);
				if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Item does not exist.<span>");
					redirect('C_reimbursement/read_all');
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_reimbursement/read_all');
				}
				else if($result === true)
				{
					$this->session->set_flashdata("message", "<span class='gr'>Item has been successfully deleted.</span>");
	            	redirect('C_reimbursement/read_one/'.$param);				
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
				$this->session->set_flashdata("message", "<span>Please choose from one of these reimbursements.</span>");
				redirect('C_reimbursement/read_all');
			}
			else
			{
				$result=$this->M_reimbursement->update_ts($param,$param2);
				if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Item does not exist.<span>");
					redirect('C_reimbursement/read_all');
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_reimbursement/read_all');
				}
				else if($result === true)
				{
					$this->session->set_flashdata("message", "<span class='gr'>Item has been successfully edited.</span>");
	            	redirect('C_reimbursement/read_one/'.$param);				
				}
				else if($result === "exceed")
				{
					$this->session->set_flashdata("message", "<span class='rd'>Reimbursement limit is excedeed. Try smaller amount.<span>");
	            	redirect('C_reimbursement/read_one/'.$param);				
				}
				else
				{
					$data['reimbursement_list']=$this->M_reimbursement->get_rmb_list($param,$param2);
					$data['viewpage'] = 'financeservices/V_reimbursement_update_ts';
					$data['title'] = 'Edit Reimbursement Item '.$param;
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
				$this->session->set_flashdata("message", "<span>Please choose from one of these reimbursements.</span>");
				redirect('C_reimbursement/read_all');
			}
			else
			{
				$data['rmb_header']=$this->M_reimbursement->get_header($param);
				$data['rmb_line']=$this->M_reimbursement->get_reimbursement_line($param);
				$result=$this->M_reimbursement->approve($param,$data['rmb_header'],$data['rmb_line']);
				if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Reimbursement does not exist.<span>");
					redirect('C_reimbursement/read_all');
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_reimbursement/read_all');
				}
				else if($result === true)
				{
					$this->session->set_flashdata("message", "<span class='gr'>Reimbursement has been successfully approved/rejected.</span>");
	            	redirect('C_reimbursement/read_one/'.$param);				
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
				$this->session->set_flashdata("message", "<span>Please choose from one of these reimbursements.</span>");
				redirect('C_reimbursement/read_all');
			}
			else
			{
				$result=$this->M_reimbursement->pay($param);
				if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Reimbursement does not exist.<span>");
					redirect('C_reimbursement/read_all');
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_reimbursement/read_all');
				}
				else if($result === "failed")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Upload failed. Please try again<span>");
					redirect('C_reimbursement/read_one/'.$param);
				}
				else if($result === "upload")
				{
					$message=$this->session->set_flashdata("message", "<span class='gr'>Payment Document has been successfully uploaded.<span>");
					redirect('C_reimbursement/read_one/'.$param);
				}
				else if($result === "download")
				{
					$message=$this->session->set_flashdata("message", "<span class='gr'>Download will begin shortly.<span>");
					redirect('C_reimbursement/read_one/'.$param);
				}
				else
				{
					$data['rmb']=$this->M_reimbursement->get_header($param);
					$data['viewpage'] = 'financeservices/V_reimbursement_pay';
					$data['title'] = 'Set Reimburse '.$param.' To Paid';
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
	
	public function download()
	{
		$access=$this->M_reusable->get_session();
		if($access === TRUE)
		{
			$param=$this->uri->segment(3);
			if($param=='')
			{
				$this->session->set_flashdata("message", "<span>Please choose from one of these reimbursements.</span>");
				redirect('C_reimbursement/read_all');
			}
			else
			{
				$result=$this->M_reimbursement->download($param);
				if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_reimbursement/read_all');
				}
				else if($result === "download")
				{
					$message=$this->session->set_flashdata("message", "<span class='gr'>Download will begin shortly.<span>");
					redirect('C_reimbursement/read_one/'.$param);
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