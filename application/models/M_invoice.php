<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class M_invoice extends CI_Model
{
	function get_inv_id()
	{
		$query=$this->db->query("SELECT max(ExtractNumber(id)) as max_id from invoice");
		$result=$query->row_array();
		$inv_id="inv".($result['max_id']+1);
		return $inv_id;		
	}

	function create()
	{
	    $user_id=$this->db->escape($this->session->userdata('username'));
	    $inv_id=$this->db->escape($this->input->post('inv_id'));
		$inv_ref=$this->db->escape($this->input->post('inv_ref'));
		$inv_date=$this->db->escape($this->input->post('inv_date'));
		$due_date=$this->db->escape($this->input->post('due_date'));
		$vendor=$this->db->escape($this->input->post('vendor'));

		$if=$this->db->query("select * from employee where user_id=".$user_id."");
		if($if->num_rows()>0)
		{
			$if=$if->row_array();
			$emp_id=$if['id'];
			$id=$this->input->post('inv_id');
			$config['upload_path'] = './media/invoice/';
			$config['allowed_types'] = 'pdf';
			$config['max_size']	= '2048000';
			$config['file_name'] = $id.'-invoiceref';
			$config['overwrite'] = true;
			$this->load->library('upload', $config);
			if(!$this->upload->do_upload('input_attach'))
			{
				//var_dump($this->upload->display_errors());exit;
				$failed="failed";
				return $failed;
			}
			else
			{	
				$this->db->query("insert into invoice(id,inv_ref,invoice_date,due_date,attachment,status,employee_id,vendor) values(".$inv_id.",".$inv_ref.",".$inv_date.",".$due_date.",'".$config['file_name']."','Accrued','".$emp_id."',".$vendor.")");					
				$query=$this->db->query("select id from invoice where id=".$inv_id."");					
				$result=$query->row_array();
				return $result;
			}
		}
		else
		{
			$message=$this->session->set_flashdata("message", "<span class='rd'>Employee with corresponding user id does not exist.<span>");
			return $message;
		}
	}

	function get_invoice()
    {
		$user_id=$this->db->escape($this->session->userdata('username'));		
		$query=$this->db->query("select * from employee where user_id=".$user_id."");
		$result=$query->row_array();

		$query=$this->db->query("select a.id,b.role_id from employee as a join role_assignment as b on a.user_id=b.user_id where a.user_id=".$user_id." and b.role_id='role6' and b.status='active'");
		$result2=$query->row_array();//cek role AP, satu user udh pasti cuman satu role AP ga mungkin double

		if($query->num_rows()>0 or $result['title_id']==2)//role6='AP', 2 adalah finance head
		{
			//boleh lah kasih sum grand total di sini
			$query = $this->db->query("select * from invoice"); 
			return $query->result_array();
		}
		
		//kasir
		$query=$this->db->query("select a.id,b.role_id from employee as a join role_assignment as b on a.user_id=b.user_id where a.user_id=".$user_id." and b.role_id='role5' and b.status='active'");
		if($query->num_rows()>0)//role5='kasir'
		{
			//boleh lah kasih sum grand total di sini
			$query = $this->db->query("select * from invoice where status='Locked' or status='Paid To Vendor'"); 
			return $query->result_array();
		}
	}

	function get_header($param)
    {
		$user_id=$this->db->escape($this->session->userdata('username'));
		$if=$this->db->query("select * from employee where user_id=".$user_id."");
		$if=$if->row_array();
		$emp_id=$if['id'];
		
		$query=$this->db->query("select a.id,b.role_id from employee as a join role_assignment as b on a.user_id=b.user_id where a.user_id=".$user_id." and b.role_id='role6' and b.status='active'");
		$result2=$query->row_array();//cek role ap, satu user udh pasti cuman satu role ap ga mungkin double
		if($result2['role_id']!=='role6')//kalo dia bukan ap
		{
			//kasir
			$query=$this->db->query("select a.id,b.role_id from employee as a join role_assignment as b on a.user_id=b.user_id where a.user_id=".$user_id." and b.role_id='role5' and b.status='active'");
			if($query->num_rows()>0)//role5='kasir'
			{
				$query = $this->db->query("select * from invoice where id=".$this->db->escape($param)."");
				if($query->num_rows()==0)
				{
					$not="notexist";
					return $not;		
				}
				else
				{
					//boleh lah kasih sum grand total di sini
					$query = $this->db->query("select * from invoice where (status='Locked' or status='Paid To Vendor') and id=".$this->db->escape($param).""); 
					return $query->row_array();
				}
			}
			else
			{
				$den="denied";
				return $den;
			}
		}
		else
		{
			$query = $this->db->query("select * from invoice where id=".$this->db->escape($param)."");
			if($query->num_rows()==0)
			{
				$not="notexist";
				return $not;		
			}
			else
			{
				return $query->row_array();
			}
		}
    }

	function get_invoice_line($param)
    {
		$query = $this->db->query("select * from invoice_list where invoice_id=".$this->db->escape($param)."");
		return $query->result_array();
    }

	function update($param)
	{
		if(!$this->input->post('edit'))
		{
			$den="denied";
			return $den;				
		}
		else
		{
			$query= $this->db->query("select * from invoice where id='".$param."'");
			if($query -> num_rows() < 1)
			{
				$not="notexist";
				return $not;		
			}
			else if($this->input->post('invoice_date') or $this->input->post('due_date') or $this->input->post('vendor'))
			{			
				$invoice_date=$this->db->escape($this->input->post('invoice_date'));
				$due_date=$this->db->escape($this->input->post('due_date'));
				$vendor=$this->db->escape($this->input->post('vendor'));
				$config['upload_path'] = './media/advance/';
				$config['allowed_types'] = 'pdf';
				$config['max_size']	= '2048000';
				$config['file_name'] = $param.'-invoiceref';
				$config['overwrite'] = true;
				$this->load->library('upload', $config);
				if(!$this->upload->do_upload('input_attach'))
				{
					//var_dump($this->upload->display_errors());exit;
					$failed="failed";
					return $failed;
				}
				else
				{	
					$query= $this->db->query("update invoice set invoice_date=".$invoice_date.",attachment='".$config['file_name']."',due_date=".$due_date.", vendor=".$vendor." where id='".$param."'");
					return true;
				}
			}
			else
			{
				$result= $query->row_array();
				return $result;		
			}
		}
	}

	function cancel($param)
	{
		if(!$this->input->post('cancel'))
		{
			$den="denied";
			return $den;				
		}
		else
		{
			$query= $this->db->query("select * from invoice where id='".$param."'");
			if($query -> num_rows() < 1)
			{
				$not="notexist";
				return $not;		
			}
			else
			{
				$query= $this->db->query("update invoice set status='Canceled' where id='".$param."'");
				return true;	
			}
		}
	}

	function create_ts($param)
	{
		if(!$this->input->post('add_inv_li'))
		{
			$den="denied";
			return $den;				
		}
		else
		{
			$query= $this->db->query("select * from invoice where id='".$param."'");
			if($query -> num_rows() < 1)
			{
				$not="notexist";
				return $not;		
			}
			else
			{
				$query=$this->db->query("SELECT max(ExtractNumber(list_id)) as max_id from invoice_list where invoice_id='".$param."'");
				$result=$query->row_array();
				$new_id=$result['max_id']+1;
				$add_desc=$this->db->escape($this->input->post('add_desc'));
				$add_amt=$this->input->post('add_amt');	
				
				$user_id=$this->db->escape($this->session->userdata('username'));
				$if=$this->db->query("select * from employee where user_id=".$user_id."");
				$if=$if->row_array();
				$emp_id=$if['id'];				

				$query= $this->db->query("insert into invoice_list values('".$param."',".$new_id.",".$add_desc.",".$add_amt.",0)");
			}
		}
	}

	function delete_ts($param,$param2)
	{
		if(!$this->input->post('del_inv_li'))
		{
			$den="denied";
			return $den;				
		}
		else
		{
			$query= $this->db->query("select * from invoice_list where invoice_id='".$param."' and list_id='".$param2."'");
			if($query -> num_rows() < 1)
			{
				$not="notexist";
				return $not;		
			}
			else
			{
				$query= $this->db->query("delete from invoice_list where invoice_id='".$param."' and list_id='".$param2."'");
				return true;	
			}
		}
	}

	function get_inv_list($param,$param2)
    {
		$query = $this->db->query("select * from invoice_list where invoice_id=".$this->db->escape($param)." and list_id='".$param2."'");
		return $query->row_array();
    }

	function update_ts($param,$param2)
	{
		if(!$this->input->post('edit_inv_li'))
		{
			$den="denied";
			return $den;				
		}
		else
		{
			$desc=$this->db->escape($this->input->post('desc'));
			$amt_inv=$this->input->post('amt_inv');
			$query= $this->db->query("select * from invoice_list where invoice_id='".$param."' and list_id='".$param2."'");
			if($query -> num_rows() < 1)
			{
				$not="notexist";
				return $not;		
			}
			else if($this->input->post('desc') and $this->input->post('amt_inv'))
			{			
				$query= $this->db->query("update invoice_list set description=".$desc.",amount_invoiced=".$amt_inv." where invoice_id='".$param."' and list_id='".$param2."'");
				return true;
			}
			else
			{
				$result= $query->row_array();
				return $result;	
			}
		}
	}
	
	function creator_only($param)
	{
		$user_id=$this->db->escape($this->session->userdata('username'));		
		$query=$this->db->query("select * from employee where user_id=".$user_id."");
		$result=$query->row_array();
		$query=$this->db->query("select * from invoice where employee_id='".$result['id']."' and id='".$param."'");
		if($query -> num_rows()==0)
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	function get_payment_auth()
	{
		$user_id=$this->db->escape($this->session->userdata('username'));		
		$query=$this->db->query("select * from employee where user_id=".$user_id."");
		$result=$query->row_array();
		$query=$this->db->query("select a.id,b.role_id from employee as a join role_assignment as b on a.user_id=b.user_id where a.user_id=".$user_id." and b.role_id='role5'");
		$result=$query->row_array();//cek role kasir, satu user udh pasti cuman satu role cashier ga mungkin double
		if($result['role_id']=='role5')
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function upload_status($param)
	{	
		$query=$this->db->query("select * from invoice where id='".$param."' and attachment=''");
		if($query -> num_rows()>0)
		{
			return false;
		}
		else
		{
			return true;
		}	
	}
	
	function pay($param)
	{	
		if(!$this->input->post('pay') and !$this->input->post('hiddenid'))
		{
			$den="denied";
			return $den;				
		}
		else
		{
			$query= $this->db->query("select * from invoice where id='".$param."'");
			if($query -> num_rows() < 1)
			{
				$not="notexist";
				return $not;		
			}
			else if($this->input->post('pay_attach'))
			{
				$config['upload_path'] = './media/invoice/';
				$config['allowed_types'] = 'pdf';
				$config['max_size']	= '2048000';
				$config['file_name'] = $param.'-paymentproof';
				$config['overwrite'] = true;
				$this->load->library('upload', $config);
				if(!$this->upload->do_upload('file'))
				{
					//var_dump($this->upload->display_errors());exit;
					$failed="failed";
					return $failed;
				}
				else
				{
					$this->db->query("update invoice set pay_attachment='".$config['file_name']."',status='Paid To Vendor',paid_date='".date('Y-m-d')."' where id='".$param."'");
					$query= $this->db->query("select * from invoice_list where invoice_id='".$param."'");
					$result=$query->result_array();
					foreach($result as $row)
					{
						$query= $this->db->query("update invoice_list set amount_paid=amount_invoiced where invoice_id='".$row['invoice_id']."' and list_id=".$row['list_id']."");
					}
					$upl="upload";
					return $upl;
				}
			}
			else if($this->input->post('lock'))
			{
				$query= $this->db->query("select * from invoice where id='".$param."'");
				if($query -> num_rows() < 1)
				{
					$not="notexist";
					return $not;		
				}
				else
				{
					$this->db->query("update invoice set status='Locked' where id='".$param."'");
					return "lock";
				}
			}
		}
	}

	function download($param)
	{	
		if(!$this->input->post('download_pay') and !$this->input->post('download_inv') and !$this->input->post('lock'))
		{
			$den="denied";
			return $den;				
		}
		else
		{
			if($this->input->post('download_inv'))
			{
				$filename = $param.'-invoiceref.pdf';
				$data = file_get_contents('./media/invoice/'.$filename);
				force_download($filename, $data);
				return "download";
			}
			else if($this->input->post('download_pay'))
			{
				$filename = $param.'-paymentproof.pdf';
				$data = file_get_contents('./media/invoice/'.$filename);
				force_download($filename, $data);
				return "download";
			}
			else if($this->input->post('lock'))
			{
				$query= $this->db->query("select * from invoice where id='".$param."'");
				if($query -> num_rows() < 1)
				{
					$not="notexist";
					return $not;		
				}
				else
				{
					$this->db->query("update invoice set status='Locked' where id='".$param."'");
					return "lock";
				}
			}
		}
	}
}

?>