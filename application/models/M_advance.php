<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class M_advance extends CI_Model
{
	function get_adv_id()
	{
		$query=$this->db->query("SELECT max(ExtractNumber(id)) as max_id from advance");
		$result=$query->row_array();
		$adv_id="adv".($result['max_id']+1);
		return $adv_id;		
	}

	function create()
	{
	    $user_id=$this->db->escape($this->session->userdata('username'));
	    $adv_id=$this->db->escape($this->input->post('adv_id'));
		$req_date=$this->db->escape($this->input->post('req_date'));
		$need_date=$this->db->escape($this->input->post('need_date'));
		if($need_date<$req_date)
		{
			$message=$this->session->set_flashdata("message", "<span class='rd'>Needed Date must be greater than Request Date.<span>");
			return $message;
		}
		$pay_to=$this->db->escape($this->input->post('pay_to'));

		$if=$this->db->query("select * from employee where user_id=".$user_id."");
		if($if->num_rows()>0)
		{
			$if=$if->row_array();
			$emp_id=$if['id'];
			$query=$this->db->query("select * from approval_structure where object_id=10 and approver='".$emp_id."'");
			if($query->num_rows()==0)
			{
				$not="notassigned";
				return $not;
			}
			else
			{
				$id=$this->input->post('adv_id');
				$config['upload_path'] = './media/advance/';
				$config['allowed_types'] = 'pdf';
				$config['max_size']	= '2048000';
				$config['file_name'] = $id.'-requestattachment';
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
					$this->db->query("insert into advance(id,request_date,usage_date,pay_to,status,employee_id,attachment) values(".$adv_id.",".$req_date.",".$need_date.",".$pay_to.", 'Draft','".$emp_id."','".$id."-requestattachment')");					
					$query=$this->db->query("select id from advance where id=".$adv_id."");					
					$result=$query->row_array();
					return $result;
				}
			}
		}
		else
		{
			$message=$this->session->set_flashdata("message", "<span class='rd'>Employee with corresponding user id does not exist.<span>");
			return $message;
		}
	}

	function get_advance()
    {
		$user_id=$this->db->escape($this->session->userdata('username'));		
		$query=$this->db->query("select * from employee where user_id=".$user_id."");
		$result=$query->row_array();

		$query = $this->db->query("select * from approval_structure where approver='".$result['id']."'");
		$result1=$query->result_array();		

		$query=$this->db->query("select a.id,b.role_id from employee as a join role_assignment as b on a.user_id=b.user_id where a.user_id=".$user_id." and b.role_id='role5' and b.status='active'");
		$result2=$query->row_array();//cek role kasir, satu user udh pasti cuman satu role cashier ga mungkin double
		
		$array=array_column($result1,'employee_id');
		$array = implode("','",$array);
		
		if($query->num_rows()>0)//role5='Cahier'
		{
			//boleh lah kasih sum grand total di sini
			$query = $this->db->query("select * from advance where status in('Fully Approved','Paid To Employee','Settled')"); 
			return $query->result_array();		
		}
		else
		{
			//boleh lah kasih sum grand total di sini
			$query = $this->db->query("select * from advance where employee_id in('".$array."')"); 
			return $query->result_array();
		}
	}

	function get_header($param)
    {
		$user_id=$this->db->escape($this->session->userdata('username'));
		$if=$this->db->query("select * from employee where user_id=".$user_id."");
		$if=$if->row_array();
		$emp_id=$if['id'];
		
		$query=$this->db->query("select a.id,b.role_id from employee as a join role_assignment as b on a.user_id=b.user_id where a.user_id=".$user_id." and b.role_id='role5' and b.status='active'");
		$result2=$query->row_array();//cek role kasir, satu user udh pasti cuman satu role cashier ga mungkin double
		if($result2['role_id']!=='role5')
		{
			$query=$this->db->query("select * from approval_structure where object_id=10 and approver='".$emp_id."'");
			if($query->num_rows()==0)
			{
				$den="denied";
				return $den;
			}
			else
			{
				$query = $this->db->query("select * from advance where id=".$this->db->escape($param)."");
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
		else
		{
			$query = $this->db->query("select * from advance where id=".$this->db->escape($param)."");
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

	function get_advance_line($param)
    {
		$query = $this->db->query("select * from advance_list where advance_id=".$this->db->escape($param)."");
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
			$query= $this->db->query("select * from advance where id='".$param."'");
			if($query -> num_rows() < 1)
			{
				$not="notexist";
				return $not;		
			}
			else if($this->input->post('need_date') and $this->input->post('pay_to'))
			{
				$result=$query->row_array();
				$need_date=$this->input->post('need_date');
				if($need_date<$result['request_date'])
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Needed Date must be greater than Request Date.<span>");
					return $message;
				}				
				$pay_to=$this->db->escape($this->input->post('pay_to'));
				$config['upload_path'] = './media/advance/';
				$config['allowed_types'] = 'pdf';
				$config['max_size']	= '2048000';
				$config['file_name'] = $param.'-requestattachment';
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
					$query= $this->db->query("update advance set usage_date='".$need_date."', pay_to=".$pay_to.",attachment='".$config['file_name']."' where id='".$param."'");
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
			$query= $this->db->query("select * from advance where id='".$param."'");
			if($query -> num_rows() < 1)
			{
				$not="notexist";
				return $not;		
			}
			else
			{
				$query= $this->db->query("update advance set status='Canceled' where id='".$param."'");
				return true;	
			}
		}
	}

	function create_ts($param)
	{
		if(!$this->input->post('add_adv_li'))
		{
			$den="denied";
			return $den;				
		}
		else
		{
			$query= $this->db->query("select * from advance where id='".$param."'");
			if($query -> num_rows() < 1)
			{
				$not="notexist";
				return $not;		
			}
			else
			{
				$query= $this->db->query("select sum(amount_requested) as amount_requested from advance_list where advance_id='".$param."'");
				$result=$query->row_array();
				$req_total=$result['amount_requested'];
				$query=$this->db->query("SELECT max(ExtractNumber(list_id)) as max_id from advance_list where advance_id='".$param."'");
				$result=$query->row_array();
				$new_id=$result['max_id']+1;
				$add_desc=$this->db->escape($this->input->post('add_desc'));
				$add_amt=$this->input->post('add_amt');
				$newreqamt=$req_total+$add_amt;
				$query=$this->db->query("SELECT max(max_amount) as max from approval_structure where object_id=10 and status='active'");
				$result=$query->row_array();				
				if($newreqamt>$result['max'])
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Advance limit is excedeed. Try smaller amount.<span>");
					return $message;				
				}
				$query= $this->db->query("insert into advance_list values('".$param."',".$new_id.",".$add_desc.",".$add_amt.",0,0,'')");
				return true;	
			}
		}
	}

	function delete_ts($param,$param2)
	{
		if(!$this->input->post('del_adv_li'))
		{
			$den="denied";
			return $den;				
		}
		else
		{
			$query= $this->db->query("select * from advance_list where advance_id='".$param."' and list_id='".$param2."'");
			if($query -> num_rows() < 1)
			{
				$not="notexist";
				return $not;		
			}
			else
			{
				$query= $this->db->query("delete from advance_list where advance_id='".$param."' and list_id='".$param2."'");
				return true;	
			}
		}
	}

	function get_adv_list($param,$param2)
    {
		$query = $this->db->query("select * from advance_list where advance_id=".$this->db->escape($param)." and list_id='".$param2."'");
		return $query->row_array();
    }

	function update_ts($param,$param2)
	{
		if(!$this->input->post('edit_adv_li'))
		{
			$den="denied";
			return $den;				
		}
		else
		{
			$query= $this->db->query("select * from advance_list where advance_id='".$param."' and list_id='".$param2."'");
			if($query -> num_rows() < 1)
			{
				$not="notexist";
				return $not;		
			}
			else if($this->input->post('desc') and $this->input->post('amt_req'))
			{
				$query= $this->db->query("select sum(amount_requested) as amount_requested from advance_list where advance_id='".$param."'");
				$result=$query->row_array();
				$req_total=$result['amount_requested'];
				$amt_req=$this->input->post('amt_req');
				$desc=$this->db->escape($this->input->post('desc'));
				$newreqamt=$req_total+$amt_req;
				$query=$this->db->query("SELECT max(max_amount) as max from approval_structure where object_id=10 and status='active'");
				$result=$query->row_array();				
				if($newreqamt>$result['max'])
				{
					$message=$this->session->set_flashdata("message", "<span class='rd'>Advance limit is excedeed. Try smaller amount.<span>");
					return $message;				
				}
				$query= $this->db->query("update advance_list set description=".$desc.",amount_requested=".$amt_req." where advance_id='".$param."' and list_id='".$param2."'");
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
		$query=$this->db->query("select * from advance where employee_id='".$result['id']."' and id='".$param."'");
		if($query -> num_rows()==0)
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	function get_approval_tree($param,$adv_header,$adv_line)
	{
		$aq=array_column($adv_line,'amount_requested');
		$subtotalreq = array();
		foreach ($aq as $key=>$aq)
		{
			$subtotalreq[] = $aq;
		}
		$gt_req=array_sum($subtotalreq);
		$query=$this->db->query("select c.*,d.name,d.description from (select a.approver,ifnull(b.notes,'-') as approved from approval_structure as a
									left join (select * from approval where docnum=".$this->db->escape($param).") as b 
									on a.approver=b.approver and a.object_id=b.object_id
									where a.employee_id='".$adv_header['employee_id']."' and a.status='active' and a.object_id=10 and a.min_amount<=".$gt_req.") as c
									join (select employee.id,employee.name,title.description from employee join title on title.id=employee.title_id) as d on c.approver=d.id");
		$result=$query->result_array();
		return $result;
				
	}

	function get_approval_auth($param,$adv_header,$adv_line)
	{
		$aq=array_column($adv_line,'amount_requested');
		$subtotalreq = array();
		foreach ($aq as $key=>$aq)
		{
			$subtotalreq[] = $aq;
		}
		$gt_req=array_sum($subtotalreq);

		$user_id=$this->db->escape($this->session->userdata('username'));		
		$query=$this->db->query("select * from employee where user_id=".$user_id."");
		$result=$query->row_array();

		$query=$this->db->query("select c.* from (select a.approver,ifnull(b.notes,'-') as approved from approval_structure as a
									left join (select * from approval where docnum=".$this->db->escape($param).") as b 
									on a.approver=b.approver and a.object_id=b.object_id
									where a.employee_id='".$adv_header['employee_id']."' and a.status='active' and a.object_id=10 and a.min_amount<=".$gt_req.") as c where c.approver='".$result['id']."'");
		$result=$query->row_array();
		if($result['approved']=='-' and ($adv_header['status']!=='Rejected' or $rmb_header['status']!=='Canceled' ))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	function approve($param,$adv_header,$adv_line)
	{
		if(!$this->input->post('approve') and !$this->input->post('reject'))
		{
			$den="denied";
			return $den;				
		}
		else
		{
			$query= $this->db->query("select * from advance where id='".$param."'");
			if($query -> num_rows() < 1)
			{
				$not="notexist";
				return $not;		
			}
			if($this->input->post('approve') or $this->input->post('reject'))
			{
				$this->load->model('M_advance');
				$result = $this->M_advance->get_approval_auth($param,$adv_header,$adv_line);
				if($result==false)//means it can approve
				{
					$query=$this->db->query("SELECT max(ExtractNumber(id)) as max_id from approval");
					$result=$query->row_array();
					$app_id=$result['max_id']+1;
					$user_id=$this->db->escape($this->session->userdata('username'));		
					$query=$this->db->query("select * from employee where user_id=".$user_id."");
					$result=$query->row_array();
					if($this->input->post('approve'))
					{
						$this->db->query("insert into approval values(".$app_id.",10,'".$result['id']."','".$param."','".date('Y-m-d')."','approved')");
						//tentuin count nya untuk partial atau fully approved
						$result = $this->M_advance->get_approval_tree($param,$adv_header,$adv_line);
						$x=count($result);
						$query=$this->db->query("select * from approval where docnum='".$param."'");
						$result=$query->result_array();
						$y=count($result);
						if($x==$y)//full
						{
							$this->db->query("update advance set status='Fully Approved' where id='".$param."'");
						}
						else//partial
						{
							$this->db->query("update advance set status='Partially Approved' where id='".$param."'");
						}
						
					}
					if($this->input->post('reject'))
					{
						$this->db->query("insert into approval values(".$app_id.",10,'".$result['id']."','".$param."','".date('Y-m-d')."','rejected')");
						$this->db->query("update advance set status='Rejected' where id='".$param."'");
					}
					return true;
				}
			}
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

	function pay($param)
	{
		if(!$this->input->post('pay'))
		{
			$den="denied";
			return $den;				
		}
		else
		{
			$query= $this->db->query("select * from advance where id='".$param."'");
			if($query -> num_rows() < 1)
			{
				$not="notexist";
				return $not;		
			}
			else
			{
				$query= $this->db->query("select * from advance_list where advance_id='".$param."'");
				$result=$query->result_array();
				foreach($result as $row)
				{
					$query= $this->db->query("update advance_list set amount_committed=amount_requested where advance_id='".$row['advance_id']."' and list_id=".$row['list_id']."");
				}
				$this->db->query("update advance set status='Paid To Employee',paid_date='".date('Y-m-d')."' where id='".$param."'");
				return true;	
			}
		}		
	}

	function return($param,$param2)
	{
		if(!$this->input->post('return') and !$this->input->post('download') and !$this->input->post('download_settle') and !$this->input->post('download_attach'))
		{
			$den="denied";
			return $den;				
		}
		else if($this->input->post('download_settle'))
		{
			$filename = $param.'-settlementproof.pdf';
			$data = file_get_contents('./media/advance/'.$filename);
			force_download($filename, $data);
			$download="download";
			return $download;
		}
		else if($this->input->post('download_attach'))
		{
			$filename = $param.'-requestattachment.pdf';
			$data = file_get_contents('./media/advance/'.$filename);
			force_download($filename, $data);
			$download="download";
			return $download;
		}
		else
		{
			$query= $this->db->query("select * from advance_list where advance_id='".$param."' and list_id='".$param2."'");
			if($query -> num_rows() < 1)
			{
				$not="notexist";
				return $not;		
			}
			else if($this->input->post('hiddenid'))
			{
				$config['upload_path'] = './media/advance/';
				$config['allowed_types'] = 'pdf';
				$config['max_size']	= '2048000';
				$config['file_name'] = $param.'-'.$param2.'-proof';
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
					$this->db->query("update advance_list set list_attachment='".$param."-".$param2."-proof"."' where advance_id='".$param."' and list_id='".$param2."'");
					return true;
				}	
			}
			else if($this->input->post('download'))
			{
				$filename = $param.'-'.$param2.'-proof.pdf';
				$data = file_get_contents('./media/advance/'.$filename);
				force_download($filename, $data);
				$download="download";
				return $download;
			}
			else
			{
				$result= $query->row_array();
				return $result;	
			}
		}
	}

	function upload_status($param)
	{	
		$query=$this->db->query("select * from advance_list where advance_id='".$param."' and list_attachment=''");
		if($query -> num_rows()>0)
		{
			return false;
		}
		else
		{
			return true;
		}	
	}
	
	function settle($param)
	{	
		if(!$this->input->post('settle') and !$this->input->post('settle_attach') and !$this->input->post('download'))
		{
			$den="denied";
			return $den;				
		}
		else
		{
			$query= $this->db->query("select * from advance where id='".$param."'");
			if($query -> num_rows() < 1)
			{
				$not="notexist";
				return $not;		
			}
			else if($this->input->post('settle_attach'))
			{
				$config['upload_path'] = './media/advance/';
				$config['allowed_types'] = 'pdf';
				$config['max_size']	= '2048000';
				$config['file_name'] = $param.'-settlementproof';
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
					$this->db->query("update advance set settle_attachment='".$param."-settlementproof"."' where id='".$param."'");
					$upl="upload";
					return $upl;
				}
			}
			else if($this->input->post('settle_amt'))//lanjut di sini,masih belum terpenuhi ini.
			{
				$query= $this->db->query("select * from advance where id='".$param."' and settle_attachment=''");
				if($query->num_rows()>0)
				{
					$nocomplete="notcomplete";
					return $nocomplete;
				}
				else
				{
					//iniforeach list update sesuai dengan name di input post. pake yg update role assignment kali ya?
					$settle_amt=$this->input->post('settle_amt');
					$list_id=$this->input->post('list_id');
					$adv_id=$this->db->escape($this->input->post('adv_id'));
					for($i=0;$i<count($settle_amt);$i++)
					{
						$data=array("adv_id"=>$adv_id[$i],"list_id"=>$list_id[$i],"settle_amt"=>$settle_amt[$i]);
						$this->db->query("update advance_list set amount_actual=".$data['settle_amt']." where advance_id=".$data['adv_id']." and list_id=".$data['list_id']."");
					}
					$this->db->query("update advance set status='Settled',return_date='".date('Y-m-d')."' where id='".$param."'");
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
}

?>