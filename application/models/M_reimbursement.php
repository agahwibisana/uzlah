<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class M_reimbursement extends CI_Model
{
	function get_rmb_id()
	{
		$query=$this->db->query("SELECT max(ExtractNumber(id)) as max_id from reimbursement");
		$result=$query->row_array();
		$rmb_id="rmb".($result['max_id']+1);
		return $rmb_id;		
	}

	function create()
	{
	    $user_id=$this->db->escape($this->session->userdata('username'));
	    $rmb_id=$this->db->escape($this->input->post('rmb_id'));
		$req_date=$this->db->escape($this->input->post('req_date'));
		$type=$this->db->escape($this->input->post('type'));
		$pay_to=$this->db->escape($this->input->post('pay_to'));

		$if=$this->db->query("select * from employee where user_id=".$user_id."");
		if($if->num_rows()>0)
		{
			$if=$if->row_array();
			$emp_id=$if['id'];
			$query=$this->db->query("select * from approval_structure where object_id=12 and approver='".$emp_id."'");
			if($query->num_rows()==0)
			{
				$not="notassigned";
				return $not;
			}
			else
			{
				$id=$this->input->post('rmb_id');
				$config['upload_path'] = './media/reimbursement/';
				$config['allowed_types'] = 'pdf';
				$config['max_size']	= '2048000';
				$config['file_name'] = $id.'-reimbursementproof';
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
					$this->db->query("insert into reimbursement(id,request_date,type,pay_to,status,employee_id,attachment) values(".$rmb_id.",".$req_date.",".$type.",".$pay_to.", 'Draft','".$emp_id."','".$config['file_name']."')");					
					$query=$this->db->query("select id from reimbursement where id=".$rmb_id."");					
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

	function get_reimbursement()
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
			$query = $this->db->query("select * from reimbursement where status in('Fully Approved','Paid To Employee')"); 
			return $query->result_array();
		}
		else if($result['title_id']==7)//title 7 adalah CHCO, ini sama cashier harusnya ga boleh dependent sama static var sih
		{
			//boleh lah kasih sum grand total di sini
			$query = $this->db->query("select * from reimbursement where employee_id in('".$array."') and (type='Medical' or type='Business Trip')"); 
			return $query->result_array();
		}
		else
		{
			//boleh lah kasih sum grand total di sini
			$query = $this->db->query("select * from reimbursement where employee_id in('".$array."')"); 
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
		if($result2['role_id']!=='role5')//kalo dia bukan cashier
		{
			$query=$this->db->query("select * from approval_structure where object_id=12 and approver='".$emp_id."'");
			if($query->num_rows()==0)
			{
				$den="denied";
				return $den;
			}
			else
			{
				if($if['title_id']==7)//kalo dia CHCO
				{
					$query = $this->db->query("select * from reimbursement where (type='Medical' or type='Business Trip') and id=".$this->db->escape($param)."");
					if($query->num_rows()==0)
					{
						$query = $this->db->query("select * from reimbursement where id=".$this->db->escape($param)."");
						if($query->num_rows()==0)
						{
							$not="notexist";
							return $not;		
						}
						else
						{
							$den="denied";
							return $den;		
						}
					}
					else
					{
						return $query->row_array();
					}
				}
				else
				{
					$query = $this->db->query("select * from reimbursement where id=".$this->db->escape($param)."");
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
		}
		else
		{
			$query = $this->db->query("select * from reimbursement where id=".$this->db->escape($param)."");
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

	function get_reimbursement_line($param)
    {
		$query = $this->db->query("select * from reimbursement_list where reimbursement_id=".$this->db->escape($param)."");
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
			$query= $this->db->query("select * from reimbursement where id='".$param."'");
			if($query -> num_rows() < 1)
			{
				$not="notexist";
				return $not;		
			}
			else if($this->input->post('pay_to'))
			{			
				$pay_to=$this->db->escape($this->input->post('pay_to'));
				$config['upload_path'] = './media/reimbursement/';
				$config['allowed_types'] = 'pdf';
				$config['max_size']	= '2048000';
				$config['file_name'] = $param.'-reimbursementproof';
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
					$query= $this->db->query("update reimbursement set pay_to=".$pay_to.",attachment='".$config['file_name']."' where id='".$param."'");
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
			$query= $this->db->query("select * from reimbursement where id='".$param."'");
			if($query -> num_rows() < 1)
			{
				$not="notexist";
				return $not;		
			}
			else
			{
				$query= $this->db->query("update reimbursement set status='Canceled' where id='".$param."'");
				return true;	
			}
		}
	}

	function create_ts($param)
	{
		if(!$this->input->post('add_rmb_li'))
		{
			$den="denied";
			return $den;				
		}
		else
		{
			$query= $this->db->query("select * from reimbursement where id='".$param."'");
			if($query -> num_rows() < 1)
			{
				$not="notexist";
				return $not;		
			}
			else
			{
				$query=$this->db->query("SELECT max(ExtractNumber(list_id)) as max_id from reimbursement_list where reimbursement_id='".$param."'");
				$result=$query->row_array();
				$new_id=$result['max_id']+1;
				$add_desc=$this->db->escape($this->input->post('add_desc'));
				$add_amt=$this->input->post('add_amt');
				
				//cek type reimbursement
				$query=$this->db->query("select * from reimbursement where id='".$param."'");
				$type=$query->row_array();

				if($type['type']=='Medical')
				{
					//cek berapa banyak medical yg udah consumed oleh employee
					$query=$this->db->query("select * from reimbursement where type='Medical' and paid_date>=DATE_FORMAT(NOW(),'%Y-01-01') and paid_date<=DATE_FORMAT(NOW(),'%Y-12-31')");
				}
				if($type['type']=='Business Trip')
				{
					//cek berapa banyak bt yg udah consumed oleh employee
					$query=$this->db->query("select * from reimbursement where type='Business Trip' and paid_date>=DATE_FORMAT(NOW(),'%Y-01-01') and paid_date<=DATE_FORMAT(NOW(),'%Y-12-31')");
				}		

				$result=$query->result_array();
				$query=$this->db->query("select sum(amount_actual) as current from reimbursement_list where reimbursement_id in('".$result['id']."')");
				$current=$query->row_array();
				
				$user_id=$this->db->escape($this->session->userdata('username'));
				$if=$this->db->query("select * from employee where user_id=".$user_id."");
				$if=$if->row_array();
				$emp_id=$if['id'];				
				//cek limit
				if($type['type']=='Medical')
				{				
					$query=$this->db->query("SELECT max(max_amount) as max from approval_structure where object_id=12 and status='active' and flag='Medical' and employee_id='".$emp_id."'");
					$limit=$query->row_array();
				}
				if($type['type']=='Business Trip')
				{				
					$query=$this->db->query("SELECT max(max_amount) as max from approval_structure where object_id=12 and status='active' and flag='Business Trip' and employee_id='".$emp_id."'");
					$limit=$query->row_array();
				}
				if($type['type']=='Operational')
				{				
					$query=$this->db->query("SELECT max(max_amount) as max from approval_structure where object_id=12 and status='active' and flag='Operational' and employee_id='".$emp_id."'");
					$limit=$query->row_array();
				}
				//cek request terkait
				$query=$this->db->query("SELECT sum(amount_requested) as req from reimbursement_list where reimbursement_id='".$param."'");
				$amtot=$query->row_array();

				if($type['type']=='Medical' or $type['type']=='Business Trip')
				{					
					if(($current['current']+$amtot['req']+$add_amt)>$limit['max'])
					{
						$message=$this->session->set_flashdata("message", "<span class='rd'>Yearly reimbursement limit is excedeed. Try smaller amount.<span>");
						return $message;
					}
					$query= $this->db->query("insert into reimbursement_list values('".$param."',".$new_id.",".$add_desc.",".$add_amt.",0,'')");
					return true;
				}
				if($type['type']=='Operational')
				{					
					if(($amtot['req']+$add_amt)>$limit['max'])
					{
						$message=$this->session->set_flashdata("message", "<span class='rd'>Yearly reimbursement limit is excedeed. Try smaller amount.<span>");
						return $message;
					}
					$query= $this->db->query("insert into reimbursement_list values('".$param."',".$new_id.",".$add_desc.",".$add_amt.",0,'')");
					return true;
				}
			}
		}
	}

	function delete_ts($param,$param2)
	{
		if(!$this->input->post('del_rmb_li'))
		{
			$den="denied";
			return $den;				
		}
		else
		{
			$query= $this->db->query("select * from reimbursement_list where reimbursement_id='".$param."' and list_id='".$param2."'");
			if($query -> num_rows() < 1)
			{
				$not="notexist";
				return $not;		
			}
			else
			{
				$query= $this->db->query("delete from reimbursement_list where reimbursement_id='".$param."' and list_id='".$param2."'");
				return true;	
			}
		}
	}

	function get_rmb_list($param,$param2)
    {
		$query = $this->db->query("select * from reimbursement_list where reimbursement_id=".$this->db->escape($param)." and list_id='".$param2."'");
		return $query->row_array();
    }

	function update_ts($param,$param2)
	{
		if(!$this->input->post('edit_rmb_li'))
		{
			$den="denied";
			return $den;				
		}
		else
		{
			$query= $this->db->query("select * from reimbursement_list where reimbursement_id='".$param."' and list_id='".$param2."'");
			if($query -> num_rows() < 1)
			{
				$not="notexist";
				return $not;		
			}
			else if($this->input->post('desc') and $this->input->post('amt_req'))
			{
				//cek type reimbursement
				$query=$this->db->query("select * from reimbursement where id='".$param."'");
				$type=$query->row_array();

				if($type['type']=='Medical')
				{
					//cek berapa banyak medical yg udah consumed oleh employee
					$query=$this->db->query("select * from reimbursement where type='Medical' and paid_date>=DATE_FORMAT(NOW(),'%Y-01-01') and paid_date<=DATE_FORMAT(NOW(),'%Y-12-31')");
				}
				if($type['type']=='Business Trip')
				{
					//cek berapa banyak bt yg udah consumed oleh employee
					$query=$this->db->query("select * from reimbursement where type='Business Trip' and paid_date>=DATE_FORMAT(NOW(),'%Y-01-01') and paid_date<=DATE_FORMAT(NOW(),'%Y-12-31')");
				}

				$result=$query->result_array();
				$query=$this->db->query("select sum(amount_actual) as current from reimbursement_list where reimbursement_id in('".$result['id']."')");
				$current=$query->row_array();
				
				$user_id=$this->db->escape($this->session->userdata('username'));
				$if=$this->db->query("select * from employee where user_id=".$user_id."");
				$if=$if->row_array();
				$emp_id=$if['id'];				
				//cek limit
				if($type['type']=='Medical')
				{				
					$query=$this->db->query("SELECT max(max_amount) as max from approval_structure where object_id=12 and status='active' and flag='Medical' and employee_id='".$emp_id."'");
					$limit=$query->row_array();
				}
				if($type['type']=='Business Trip')
				{				
					$query=$this->db->query("SELECT max(max_amount) as max from approval_structure where object_id=12 and status='active' and flag='Business Trip' and employee_id='".$emp_id."'");
					$limit=$query->row_array();
				}
				if($type['type']=='Operational')
				{				
					$query=$this->db->query("SELECT max(max_amount) as max from approval_structure where object_id=12 and status='active' and flag='Operational' and employee_id='".$emp_id."'");
					$limit=$query->row_array();
				}
				//cek request terkait
				$query=$this->db->query("SELECT sum(amount_requested) as req from reimbursement_list where reimbursement_id='".$param."'");
				$amtot=$query->row_array();
				$amt_req=$this->input->post('amt_req');
				$desc=$this->db->escape($this->input->post('desc'));
				if($type['type']=='Medical' or $type['type']=='Business Trip')
				{				
					if(($current['current']+$amtot['req']+$amt_req)>$limit['max'])
					{
						return "exceed";
					}
					$query= $this->db->query("update reimbursement_list set description=".$desc.",amount_requested=".$amt_req." where reimbursement_id='".$param."' and list_id='".$param2."'");
					return true;
				}
				if($type['type']=='Operational')
				{				
					if(($amtot['req']+$amt_req)>$limit['max'])
					{
						return "exceed";
					}
					$query= $this->db->query("update reimbursement_list set description=".$desc.",amount_requested=".$amt_req." where reimbursement_id='".$param."' and list_id='".$param2."'");
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
	
	function creator_only($param)
	{
		$user_id=$this->db->escape($this->session->userdata('username'));		
		$query=$this->db->query("select * from employee where user_id=".$user_id."");
		$result=$query->row_array();
		$query=$this->db->query("select * from reimbursement where employee_id='".$result['id']."' and id='".$param."'");
		if($query -> num_rows()==0)
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	function get_approval_tree($param,$rmb_header,$rmb_line)
	{
		$query=$this->db->query("select type from reimbursement where id='".$param."'");
		$result=$query->row_array();
		$type=$result['type'];
		
		$aq=array_column($rmb_line,'amount_requested');
		$subtotalreq = array();
		foreach ($aq as $key=>$aq)
		{
			$subtotalreq[] = $aq;
		}
		$gt_req=array_sum($subtotalreq);
		$query=$this->db->query("select c.*,d.name,d.description from (select a.approver,ifnull(b.notes,'-') as approved from approval_structure as a
									left join (select * from approval where docnum=".$this->db->escape($param).") as b
									on a.approver=b.approver and a.object_id=b.object_id
									where a.employee_id='".$rmb_header['employee_id']."' and a.status='active' and a.object_id=12 and a.min_amount<=".$gt_req." and flag='".$type."') as c
									join (select employee.id,employee.name,title.description from employee join title on title.id=employee.title_id) as d on c.approver=d.id");
		$result=$query->result_array();
		return $result;
				
	}

	function get_approval_auth($param,$rmb_header,$rmb_line)
	{
		$query=$this->db->query("select type from reimbursement where id='".$param."'");
		$result=$query->row_array();
		$type=$result['type'];
		
		$aq=array_column($rmb_line,'amount_requested');
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
									where a.employee_id='".$rmb_header['employee_id']."' and a.status='active' and a.object_id=12 and a.min_amount<=".$gt_req." and flag='".$type."') as c where c.approver='".$result['id']."'");
		$result=$query->row_array();
		if($result['approved']=='-' and ($rmb_header['status']!=='Rejected' or $rmb_header['status']!=='Canceled' ))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	function approve($param,$rmb_header,$rmb_line)
	{
		if(!$this->input->post('approve') and !$this->input->post('reject'))
		{
			$den="denied";
			return $den;				
		}
		else
		{
			$query= $this->db->query("select * from reimbursement where id='".$param."'");
			if($query -> num_rows() < 1)
			{
				$not="notexist";
				return $not;		
			}
			if($this->input->post('approve') or $this->input->post('reject'))
			{
				$this->load->model('M_reimbursement');
				$result = $this->M_reimbursement->get_approval_auth($param,$rmb_header,$rmb_line);
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
						$this->db->query("insert into approval values(".$app_id.",12,'".$result['id']."','".$param."','".date('Y-m-d')."','approved')");
						//tentuin count nya untuk partial atau fully approved
						$result = $this->M_reimbursement->get_approval_tree($param,$rmb_header,$rmb_line);
						$x=count($result);
						$query=$this->db->query("select * from approval where docnum='".$param."'");
						$result=$query->result_array();
						$y=count($result);
						if($x==$y)//full
						{
							$this->db->query("update reimbursement set status='Fully Approved' where id='".$param."'");
						}
						else//partial
						{
							$this->db->query("update reimbursement set status='Partially Approved' where id='".$param."'");
						}
						
					}
					if($this->input->post('reject'))
					{
						$this->db->query("insert into approval values(".$app_id.",12,'".$result['id']."','".$param."','".date('Y-m-d')."','rejected')");
						$this->db->query("update reimbursement set status='Rejected' where id='".$param."'");
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

	function upload_status($param)
	{	
		$query=$this->db->query("select * from reimbursement where id='".$param."' and attachment=''");
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
		if(!$this->input->post('pay') and !$this->input->post('hiddenid') and !$this->input->post('download_req') and !$this->input->post('download_pay'))
		{
			$den="denied";
			return $den;				
		}
		else
		{
			$query= $this->db->query("select * from reimbursement where id='".$param."'");
			if($query -> num_rows() < 1)
			{
				$not="notexist";
				return $not;		
			}
			else if($this->input->post('pay_attach'))
			{
				$config['upload_path'] = './media/reimbursement/';
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
					$this->db->query("update reimbursement set pay_attachment='".$config['file_name']."',status='Paid To Employee',paid_date='".date('Y-m-d')."' where id='".$param."'");
					$query= $this->db->query("select * from reimbursement_list where reimbursement_id='".$param."'");
					$result=$query->result_array();
					foreach($result as $row)
					{
						$query= $this->db->query("update reimbursement_list set amount_actual=amount_requested where reimbursement_id='".$row['reimbursement_id']."' and list_id=".$row['list_id']."");
					}
					$upl="upload";
					return $upl;
				}
			}
			else if($this->input->post('download_req'))
			{
				$filename = $param.'-reimbursementproof.pdf';
				$data = file_get_contents('./media/reimbursement/'.$filename);
				force_download($filename, $data);
				return "download";
			}
			else if($this->input->post('download_pay'))
			{
				$filename = $param.'-paymentproof.pdf';
				$data = file_get_contents('./media/reimbursement/'.$filename);
				force_download($filename, $data);
				return "download";
			}
		}
	}

	function download($param)
	{	
		if(!$this->input->post('download_pay') and !$this->input->post('download_req'))
		{
			$den="denied";
			return $den;				
		}
		else
		{
			if($this->input->post('download_req'))
			{
				$filename = $param.'-reimbursementproof.pdf';
				$data = file_get_contents('./media/reimbursement/'.$filename);
				force_download($filename, $data);
				return "download";
			}
			else if($this->input->post('download_pay'))
			{
				$filename = $param.'-paymentproof.pdf';
				$data = file_get_contents('./media/reimbursement/'.$filename);
				force_download($filename, $data);
				return "download";
			}
		}
	}
}

?>