<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_salesorder extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('M_get');
		$this->load->model('M_reusable');
		$this->load->model('M_salesorder');
	}

	public function index()
	{
		redirect('C_home');
    }

	public function create()
	{
		$access=$this->M_reusable->get_session();
		if($access===TRUE)
		{
			if($this->input->post('salesorder_id') && $this->input->post('inventory_id'))
			{
				$return=$this->M_salesorder->create();
				if($return === TRUE)
				{
					$this->session->set_flashdata("message", "<span class='gr'>Sales Order has been successfully drafted.</span>");
					redirect('C_salesorder/read_all');
				}
				else
				{
					redirect('C_salesorder/create');
				}
			}
			else
			{
				$data['location']=$this->M_get->get_location();
				$data['customer']=$this->M_get->get_customer();
				$data['inventory_stock']=$this->M_get->get_inventory_stock();
				$data['viewpage'] = 'sales/V_salesorder_create';
				$data['title'] = 'Create Sales Order';
				$data['message'] = $this->session->flashdata('message');
				$this->load->view('V_template',$data);
			}
		}
		else
		{
			$data['title'] = 'Unauthorized Access';
			$data['message'] = $this->session->flashdata('message');
			$data['viewpage'] = 'V_notauthorized';
			$this->load->view('V_template',$data);
		}
	}

	public function read_all()
	{
		$access=$this->M_reusable->get_session();
		if($access === TRUE)
		{
			$status=$this->input->get('status');
			$data['sales_order']=$this->M_salesorder->get_sales_order($status);
			$data['viewpage'] = 'sales/V_salesorder_readall';
			$data['title'] = 'View All Sales Orders';
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
				$this->session->set_flashdata("message", "<span>Please choose from one of these sales orders.</span>");
				redirect('C_salesorder/read_all');
			}
			else
			{
				$statreturn=$this->M_salesorder->get_local($param);//get location authorization
				if($statreturn === true)//can only be read within respective location
				{
					$data['sales_order']=$this->M_salesorder->get_sales_order_header($param);
					$data['sales_order_line']=$this->M_salesorder->get_sales_order_line($param);
					$data['approval']=$this->M_get->get_detail_approval($param,$data['sales_order_line']);
					$data['viewpage'] = 'sales/V_salesorder_readone';
					$data['title'] = 'View Sales Order '.$param;
					$data['message'] = $this->session->flashdata('message');
					$this->load->view('V_template',$data);
				}
				else
				{
					$this->session->set_flashdata("message", "<span class='rd'>Cannot view SO. It may not exist or check your location.</span>");
					redirect('C_salesorder/read_all');						
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
			if($this->input->post('salesorder_id') && $this->input->post('inventory_id'))
			{
				$return=$this->M_salesorder->update();
				if($return === TRUE)
				{
					$this->session->set_flashdata("message", "<span class='gr'>Sales Order has been successfully updated.</span>");
					redirect('C_salesorder/read_one/'.$this->uri->segment(3));
				}
				else
				{
					redirect('C_salesorder/read_one/'.$this->uri->segment(3));
				}
			}
			else
			{
				$param=$this->uri->segment(3);
				if($param=='')
				{
					$this->session->set_flashdata("message", "<span>Please choose from one of these sales orders.</span>");
					redirect('C_salesorder/read_all');
				}
				else
				{
					$statreturn=$this->M_get->get_sales_order_status($param);
					if($statreturn === true)//can only be updated where status is drafted
					{
						$data['sales_order']=$this->M_salesorder->get_sales_order_header($param);
						$data['sales_order_line']=$this->M_salesorder->get_sales_order_line($param);
						$data['location']=$this->M_get->get_location();
						$data['customer']=$this->M_get->get_customer();
						$data['inventory_stock']=$this->M_get->get_inventory_stock();
						$data['viewpage'] = 'sales/V_salesorder_update';
						$data['title'] = 'Update Sales Order '.$param;
						$data['message'] = $this->session->flashdata('message');
						$this->load->view('V_template',$data);
					}
					else
					{
						$this->session->set_flashdata("message", "<span class='rd'>Sales Order cannot be updated. It may not exist or it's been proceed.</span>");
						redirect('C_salesorder/read_all');				
					}
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

	public function delete_md()
	{
		$access=$this->M_reusable->get_session();
		if($access === TRUE)
		{
			if($this->input->post('salesorder_id') && $this->input->post('inventory_id'))
			{
				$return=$this->M_salesorder->delete_md();
				if($return === TRUE)
				{
					$this->session->set_flashdata("message", "<span class='gr'>Sales Order has been successfully canceled.</span>");
					redirect('C_salesorder/read_one/'.$this->uri->segment(3));
				}
				else
				{
					redirect('C_salesorder/read_one/'.$this->uri->segment(3));
				}
			}
			else
			{
				$param=$this->uri->segment(3);
				if($param=='')
				{
					$this->session->set_flashdata("message", "<span>Please choose from one of these sales orders.</span>");
					redirect('C_salesorder/read_all');
				}
				else
				{
					$statreturn=$this->M_get->get_sales_order_approval_status($param);
					if($statreturn === true)//can only be canceled where status is drafted or partially_approved
					{
						$data['sales_order']=$this->M_salesorder->get_sales_order_header($param);
						$data['sales_order_line']=$this->M_salesorder->get_sales_order_line($param);
						$data['approval']=$this->M_get->get_detail_approval($param,$data['sales_order_line']);
						$data['viewpage'] = 'sales/V_salesorder_deletemd';
						$data['title'] = 'Cancel Sales Order '.$param;
						$data['message'] = $this->session->flashdata('message');
						$this->load->view('V_template',$data);
					}
					else
					{
						$this->session->set_flashdata("message", "<span class='rd'>SO cannot be canceled. It may not exist or it's been proceed.</span>");
						redirect('C_salesorder/read_all');				
					}
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

	public function delete_ts()
	{
		$access=$this->M_reusable->get_session();
		if($access === TRUE)
		{
			if($this->input->post('salesorder_id') && $this->input->post('inventory_id'))
			{
				$return=$this->M_salesorder->delete_ts();
				if($return === TRUE)
				{
					$this->session->set_flashdata("message", "<span class='gr'>Sales order item have been successfully deleted.</span>");
					redirect('C_salesorder/read_one/'.$this->uri->segment(3));
				}
				else
				{
					redirect('C_salesorder/read_one/'.$this->uri->segment(3));
				}
			}
			else
			{
				$param=$this->uri->segment(3);
				$param2=$this->uri->segment(4);
				if($param=='' or $param2=='')
				{
					$this->session->set_flashdata("message", "<span>Please choose from one of these sales orders.</span>");
					redirect('C_salesorder/read_all');
				}
				else
				{
					$linestat=$this->M_get->get_specific_sales_order_line($param,$param2);
					$statreturn=$this->M_get->get_sales_order_status($param);
					if(!$linestat===false && $statreturn===true)//can only be deleted where sales order status is drafted
					{
						$data['sales_order_line']=$linestat;
						$data['viewpage'] = 'sales/V_salesorder_deletets';
						$data['title'] = 'Cancel Sales Order '.$param.' Item '.$param2;
						$data['message'] = $this->session->flashdata('message');
						$this->load->view('V_template',$data);
					}
					else
					{
						$this->session->set_flashdata("message", "<span class='rd'>Cannot delete item. Item is not found or SO has been proceed.</span>");
						redirect('C_salesorder/read_one/'.$this->uri->segment(3));
					}
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

	public function approve()
	{
		$access=$this->M_reusable->get_session();
		if($access === TRUE)
		{
			if($this->input->post('salesorder_id') && $this->input->post('gt'))
			{
				$return=$this->M_salesorder->approve();
				if($return === TRUE)
				{
					$this->session->set_flashdata("message", "<span class='gr'>Sales order has been adjusted. Check status for remaining approvals.</span>");
					redirect('C_salesorder/read_one/'.$this->uri->segment(3));
				}
				else
				{
					redirect('C_salesorder/read_one/'.$this->uri->segment(3));
				}
			}
			else
			{
				$param=$this->uri->segment(3);
				if($param=='')
				{
					$this->session->set_flashdata("message", "<span>Please choose from one of these sales orders.</span>");
					redirect('C_salesorder/read_all');
				}
				else
				{
					$statreturn=$this->M_get->get_sales_order_approval_status($param);
					if($statreturn === true)//can only be approved where sales order status is drafted or partially_approved
					{
						$data['sales_order']=$this->M_salesorder->get_sales_order_header($param);
						$data['sales_order_line']=$this->M_salesorder->get_sales_order_line($param);
						$data['approval']=$this->M_get->get_detail_approval($param,$data['sales_order_line']);
						$data['viewpage'] = 'sales/V_salesorder_approve';
						$data['title'] = 'Approve Sales Order '.$param;
						$data['message'] = $this->session->flashdata('message');
						$this->load->view('V_template',$data);
					}
					else
					{
						$this->session->set_flashdata("message", "<span class='rd'>SO cannot be approved. It may not exist or not eligible for approval.</span>");
						redirect('C_salesorder/read_all');
					}
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
}

?>