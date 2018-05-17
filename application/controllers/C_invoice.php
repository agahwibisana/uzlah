<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_invoice extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('M_reusable');
		$this->load->model('M_invoice');
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
			if($this->input->post('inv_id'))
			{
				$result=$this->M_invoice->create();
				if($result === "failed")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Upload failed. Please try again<span>");
					redirect('C_invoice/create');
				}
				else
				{
					$message=$this->session->set_flashdata("message", "<span class='gr'>Invoice has been submitted. Now add some items to it! (hint: if an invoice contains so many items, summarize and input it as a few items)</span>");
	            	redirect('C_invoice/read_one/'.$return['id']);
				}

			}
			else
			{
				$data['invid']=$this->M_invoice->get_inv_id();
				$data['viewpage'] = 'finance&accounting/V_invoice_create';
				$data['title'] = 'Input Invoice Data';
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
			$data['invoice']=$this->M_invoice->get_invoice();
			$data['viewpage'] = 'finance&accounting/V_invoice_readall';
			$data['title'] = 'View All Invoices';
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
				$this->session->set_flashdata("message", "<span>Please choose from one of these invoices.</span>");
				redirect('C_invoice/read_all');
			}
			else
			{
				$result=$this->M_invoice->get_header($param);
				if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Invoice does not exist.<span>");
					redirect('C_invoice/read_all');
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_invoice/read_all');
				}
				else
				{
					$data['inv_header']=$this->M_invoice->get_header($param);
					$data['inv_line']=$this->M_invoice->get_invoice_line($param);
					$data['creator_only']=$this->M_invoice->creator_only($param);
					$data['upload_stat']=$this->M_invoice->upload_status($param);
					$data['paid_but']=$this->M_invoice->get_payment_auth();
					$x=$this->M_invoice->get_header($param);
					$data['viewpage'] = 'finance&accounting/V_invoice_readone';
					$data['title'] = 'View Invoice '.$param.' (Ref: '.$x['inv_ref'].')';
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
				$this->session->set_flashdata("message", "<span>Please choose from one of these invoices.</span>");
				redirect('C_invoice/read_all');
			}
			else
			{
				$result=$this->M_invoice->update($param);
				if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Invoice does not exist.<span>");
					redirect('C_invoice/read_all');
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_invoice/read_all');
				}
				else if($result === true)
				{
					$this->session->set_flashdata("message", "<span class='gr'>Invoice has been successfully edited.</span>");
	            	redirect('C_invoice/read_one/'.$param);				
				}
				else
				{
					$data['invoice']=$this->M_invoice->get_header($param);
					$data['viewpage'] = 'finance&accounting/V_invoice_update';
					$data['title'] = 'Edit Invoice '.$param;
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
				$this->session->set_flashdata("message", "<span>Please choose from one of these invoices.</span>");
				redirect('C_invoice/read_all');
			}
			else
			{
				$result=$this->M_invoice->cancel($param);
				if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Invoice does not exist.<span>");
					redirect('C_invoice/read_all');
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_invoice/read_all');
				}
				else if($result === true)
				{
					$this->session->set_flashdata("message", "<span class='gr'>Invoice has been successfully canceled.</span>");
	            	redirect('C_invoice/read_one/'.$param);				
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
				$this->session->set_flashdata("message", "<span>Please choose from one of these invoices.</span>");
				redirect('C_invoice/read_all');
			}
			else
			{
				$result=$this->M_invoice->create_ts($param);
				if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Invoice does not exist.<span>");
					redirect('C_invoice/read_all');
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_invoice/read_all');
				}
				else if($result === true)
				{
					$message=$this->session->set_flashdata("message", "<span class='gr'>Item has been successfully added.</span>");
	            	redirect('C_invoice/read_one/'.$param);				
				}
				else
				{
					redirect('C_invoice/read_one/'.$param);
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
				$this->session->set_flashdata("message", "<span>Please choose from one of these invoices.</span>");
				redirect('C_invoice/read_all');
			}
			else
			{
				$result=$this->M_invoice->delete_ts($param,$param2);
				if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Item does not exist.<span>");
					redirect('C_invoice/read_all');
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_invoice/read_all');
				}
				else if($result === true)
				{
					$this->session->set_flashdata("message", "<span class='gr'>Item has been successfully deleted.</span>");
	            	redirect('C_invoice/read_one/'.$param);				
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
				$this->session->set_flashdata("message", "<span>Please choose from one of these invoices.</span>");
				redirect('C_invoice/read_all');
			}
			else
			{
				$result=$this->M_invoice->update_ts($param,$param2);
				if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Item does not exist.<span>");
					redirect('C_invoice/read_all');
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_invoice/read_all');
				}
				else if($result === true)
				{
					$this->session->set_flashdata("message", "<span class='gr'>Item has been successfully edited.</span>");
	            	redirect('C_invoice/read_one/'.$param);				
				}
				else
				{
					$data['invoice_list']=$this->M_invoice->get_inv_list($param,$param2);
					$data['viewpage'] = 'finance&accounting/V_invoice_update_ts';
					$data['title'] = 'Edit Invoice Item '.$param;
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

	public function pay()
	{
		$access=$this->M_reusable->get_session();
		if($access === TRUE)
		{
			$param=$this->uri->segment(3);
			if($param=='')
			{
				$this->session->set_flashdata("message", "<span>Please choose from one of these invoices.</span>");
				redirect('C_invoice/read_all');
			}
			else
			{
				$result=$this->M_invoice->pay($param);
				if($result === "notexist")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Invoice does not exist.<span>");
					redirect('C_invoice/read_all');
				}
				else if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_invoice/read_all');
				}
				else if($result === "failed")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Upload failed. Please try again<span>");
					redirect('C_invoice/read_one/'.$param);
				}
				else if($result === "upload")
				{
					$message=$this->session->set_flashdata("message", "<span class='gr'>Payment Document has been successfully uploaded.<span>");
					redirect('C_invoice/read_one/'.$param);
				}
				else
				{
					$data['inv']=$this->M_invoice->get_header($param);
					$data['viewpage'] = 'finance&accounting/V_invoice_pay';
					$data['title'] = 'Set Invoice '.$param.' To Paid';
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
				$this->session->set_flashdata("message", "<span>Please choose from one of these invoices.</span>");
				redirect('C_invoice/read_all');
			}
			else
			{
				$result=$this->M_invoice->download($param);
				if($result === "denied")
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Access denied.<span>");
					redirect('C_invoice/read_all');
				}
				else if($result === "download")
				{
					$message=$this->session->set_flashdata("message", "<span class='gr'>Download will begin shortly.<span>");
					redirect('C_invoice/read_one/'.$param);
				}
				else if($result === "lock")
				{
					$message=$this->session->set_flashdata("message", "<span class='gr'>Invoice has been finalized.<span>");
					redirect('C_invoice/read_one/'.$param);
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