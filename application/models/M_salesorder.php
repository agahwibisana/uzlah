<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class M_salesorder extends CI_Model
{
	function create()
	{
		$salesorder_id=$this->db->escape($this->input->post('salesorder_id'));
		$date=$this->db->escape($this->input->post('date'));
		$note=$this->db->escape($this->input->post('note'));
		$customer_id=$this->db->escape($this->input->post('customer_id'));
		$user_id=$this->db->escape($this->input->post('user_id'));
		$location_id=$this->db->escape($this->input->post('location_id'));
		$inventory_id=$this->db->escape($this->input->post('inventory_id'));
		$amount=$this->input->post('amount');
		$sellpriceeach=$this->input->post('sellpriceeach');
		$uom=$this->db->escape($this->input->post('uom'));
		$query = $this->db->query("select * from sales_order where id=".$salesorder_id."");
		if($query->num_rows()==1)
		{
			$message=$this->session->set_flashdata("message", "<span class='rd'>Sales order creation failed. Duplicate sales order ID.</span>");
			return $message;
		}
		else
		{
			if($inventory_id)
			{
				$this->db->trans_begin();
				$this->db->query("insert into sales_order values(".$salesorder_id.",".$date.",".$note.", 'drafted', ".$customer_id.",".$user_id.",".$location_id.")");
			}
			else
			{
			$message=$this->session->set_flashdata("message", "<span class='rd'>Sales order creation failed. No items in sales order.</span>");
				return $message;
			}
			for($i=0;$i<count($inventory_id);$i++)
			{
				$data=array("salesorder_id"=>$salesorder_id,"inventory_id"=>$inventory_id[$i],"amount"=>$amount[$i],"sellpriceeach"=>$sellpriceeach[$i],"uom"=>$uom[$i]);
				//cek stock dulu
				$query=$this->db->query("select q.id,q.name,q.description,q.sum_received-q.sum_released-q.sum_awaiting as sum_total,q.sum_received,q.sum_released,q.sum_awaiting,q.location_id from
											(select p.id,p.name,o.description,ifnull(x.sum_received,0) as sum_received,ifnull(y.sum_released,0) as sum_released,ifnull(z.sum_awaiting,0) as sum_awaiting,o.id as location_id
											from
											(select a.inventory_id as id, sum(a.amount) as sum_received, a.location_id from (select * from inventory_receipt as i join inventory_receipt_line as j on i.id=j.inventory_receipt_id) as a group by a.inventory_id,a.location_id) as x
											left join
											(select c.inventory_id, sum(c.amount) as sum_released, c.location_id from (select * from inventory_release as k join inventory_release_line as l on k.id=l.inventory_release_id) as c group by c.inventory_id,c.location_id) as y
											ON x.id=y.inventory_id and x.location_id=y.location_id
											left join
											(select e.inventory_id,sum(e.quantity_sold) as sum_awaiting,e.location_id from (select n.inventory_id,n.quantity_sold,m.location_id,n.status from sales_order as m join sales_order_line as n on m.id=n.sales_order_id) as e where e.status='awaiting_release' group by e.inventory_id,e.location_id) as z
											on x.id=z.inventory_id and x.location_id=z.location_id
											join location as o on x.location_id=o.id
											join inventory as p on x.id=p.id) as q
										where q.id=".$data['inventory_id']." and q.location_id=".$location_id."
										order by q.description asc,length(q.id),q.id");/*will not work if id has different prefix length, trailing zeros, negative number, or decimal*/		
				$result=$query->row_array();
				if($result['sum_total'] < $data['amount'])
				{
					$this->db->trans_rollback();
					$message=$this->session->set_flashdata("message", "<span class='rd'>Sales order creation failed. Stock is insufficient.<span>");
					return $message;
					exit;
				}
				else
				{
					$this->db->query("insert into sales_order_line values(".$data['salesorder_id'].",".$data['inventory_id'].",".$data['amount'].",".$data['sellpriceeach'].",".$data['uom'].", 'awaiting_release')");
				}
			}
			if(!$this->db->trans_status()===FALSE)
			{
				$this->db->trans_commit();
				return true;
			}
			else
			{
				$this->db->trans_rollback();
				$message=$this->session->set_flashdata("message", "<span class='rd'>Sales order creation failed. Duplicate inventory ID in two or more rows.<span>");
				return $message;
			}
		}
	}

	//user can only see their respective location's SO.
	function get_sales_order($status)
	{
		$loct=unserialize(($this->session->userdata('locationlist')));
		$locid=array_column($loct,'location_id');
		$loctopass = join("','",$locid);
		if($status)
		{
			$query = $this->db->query("select d.*,sum(e.quantity_sold*e.selling_price_each) as gt from sales_order_line as e
										join
										(select a.id,a.date,a.note,a.status,c.name,a.user_id,b.description from sales_order as a join location as b on a.location_id=b.id join customer as c on a.customer_id=c.id where a.status=".$this->db->escape($status)." and a.location_id in ('$loctopass')) as d
										on e.sales_order_id=d.id
										group by d.id
										order by length(d.id),d.id"); /*will not work if id has different prefix length, trailing zeros, negative number, or decimal*/		
		}
		else
		{
			$query = $this->db->query("select d.*,sum(e.quantity_sold*e.selling_price_each) as gt from sales_order_line as e
										join
										(select a.id,a.date,a.note,a.status,c.name,a.user_id,b.description from sales_order as a join location as b on a.location_id=b.id join customer as c on a.customer_id=c.id where a.location_id in ('$loctopass')) as d
										on e.sales_order_id=d.id
										group by d.id
										order by length(d.id),d.id"); /*will not work if id has different prefix length, trailing zeros, negative number, or decimal*/		
		}
		return $query->result_array();
	}

	//user can only see their respective location's SO.
	function get_local($param)
	{
		$loct=unserialize(($this->session->userdata('locationlist')));
		$locid=array_column($loct,'location_id');
		$loctopass = join("','",$locid);
		$query = $this->db->query("select a.id from sales_order as a where a.location_id in ('$loctopass') and a.id=".$this->db->escape($param)."");
		if($query->num_rows()>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function get_sales_order_header($param)
	{
		$query = $this->db->query("select a.id,a.date,a.note,a.status,c.id as customer_id, c.name,a.user_id,b.id as location_id, b.description from sales_order as a join location as b on a.location_id=b.id join customer as c on a.customer_id=c.id where a.id=".$this->db->escape($param)."");
		return $query->row_array();
	}

	function get_sales_order_line($param)
	{
		$query = $this->db->query("SELECT d.*,ifnull(e.released,0) as released,ifnull(h.awaiting,0) as awaiting from
								(select a.*,c.name as inv_name from sales_order_line as a join sales_order as b on a.sales_order_id=b.id join inventory as c on a.inventory_id=c.id where a.sales_order_id=".$this->db->escape($param).") as d
								left join (select f.sales_order_id,f.inventory_id,f.inventory_release_id, sum(f.amount) as released from inventory_release_line as f where f.sales_order_id=".$this->db->escape($param)." group by f.sales_order_id,f.inventory_id) as e
								on e.sales_order_id=d.sales_order_id and e.inventory_id=d.inventory_id
								left join (select g.sales_order_id,g.inventory_id,sum(g.quantity_sold) as awaiting from sales_order_line as g where g.sales_order_id=".$this->db->escape($param)." and g.status='awaiting_release' group by g.sales_order_id,g.inventory_id) as h
								on h.sales_order_id=d.sales_order_id and h.inventory_id=d.inventory_id");
		return $query->result_array();
	}

	function update()
	{
		$salesorder_id=$this->db->escape($this->input->post('salesorder_id'));
		$date=$this->db->escape($this->input->post('date'));
		$note=$this->db->escape($this->input->post('note'));
		$customer_id=$this->db->escape($this->input->post('customer_id'));
		$user_id=$this->db->escape($this->input->post('user_id'));
		$location_id=$this->db->escape($this->input->post('location_id'));
		$inventory_id=$this->db->escape($this->input->post('inventory_id'));
		$amount=$this->input->post('amount');
		$sellpriceeach=$this->input->post('sellpriceeach');
		$uom=$this->db->escape($this->input->post('uom'));
		$this->db->trans_begin();
		$this->db->query("update sales_order set date=".$date.",note=".$note.", customer_id=".$customer_id.",user_id=".$user_id.",location_id=".$location_id." where id=".$salesorder_id."");
		for($i=0;$i<count($inventory_id);$i++)
		{
			$data=array("salesorder_id"=>$salesorder_id,"inventory_id"=>$inventory_id[$i],"amount"=>$amount[$i],"sellpriceeach"=>$sellpriceeach[$i],"uom"=>$uom[$i]);
			//cek stock dulu
			$query=$this->db->query("select q.id,q.name,q.description,q.sum_received-q.sum_released-q.sum_awaiting as sum_total,q.sum_received,q.sum_released,q.sum_awaiting,q.location_id from
										(select p.id,p.name,o.description,ifnull(x.sum_received,0) as sum_received,ifnull(y.sum_released,0) as sum_released,ifnull(z.sum_awaiting,0) as sum_awaiting,o.id as location_id
										from
										(select a.inventory_id as id, sum(a.amount) as sum_received, a.location_id from (select * from inventory_receipt as i join inventory_receipt_line as j on i.id=j.inventory_receipt_id) as a group by a.inventory_id,a.location_id) as x
										left join
										(select c.inventory_id, sum(c.amount) as sum_released, c.location_id from (select * from inventory_release as k join inventory_release_line as l on k.id=l.inventory_release_id) as c group by c.inventory_id,c.location_id) as y
										ON x.id=y.inventory_id and x.location_id=y.location_id
										left join
										(select e.inventory_id,sum(e.quantity_sold) as sum_awaiting,e.location_id from (select n.inventory_id,n.quantity_sold,m.location_id,n.status from sales_order as m join sales_order_line as n on m.id=n.sales_order_id) as e where e.status='awaiting_release' group by e.inventory_id,e.location_id) as z
										on x.id=z.inventory_id and x.location_id=z.location_id
										join location as o on x.location_id=o.id
										join inventory as p on x.id=p.id) as q
									where q.id=".$data['inventory_id']." and q.location_id=".$location_id."
									order by q.description asc,length(q.id),q.id");/*will not work if id has different prefix length, trailing zeros, negative number, or decimal*/				
			$result=$query->row_array();
			$query2=$this->db->query("select e.inventory_id,sum(e.quantity_sold) as sum_awaiting,e.location_id,e.sales_order_id from (select n.inventory_id,n.quantity_sold,m.location_id,n.status,n.sales_order_id from sales_order as m join sales_order_line as n on m.id=n.sales_order_id) as e where e.status='awaiting_release' and e.sales_order_id=".$data['salesorder_id']." and e.inventory_id=".$data['inventory_id']." group by e.inventory_id,e.location_id");
			$result2=$query2->row_array();
			//cek apakah row ini row baru atau ngga? dan cek apakah inventory ID duplikat apa ngga
			$query=$this->db->query("select sales_order_id,inventory_id from sales_order_line where sales_order_id=".$data['salesorder_id']." and inventory_id=".$data['inventory_id']."");
			if($query->num_rows() == 1)
			{
				if($i>0)
				{
					$previous=$inventory_id[$i-1];//get ID in previous row
					if($data['inventory_id']==$previous)
					{
						$this->db->trans_rollback();
						$message=$this->session->set_flashdata("message", "<span class='rd'>Sales order creation failed. Duplicate inventory ID.<span>");
						return $message;
						exit;
					}
					else
					{
						$this->db->query("update sales_order_line set quantity_sold=".$data['amount'].", selling_price_each=".$data['sellpriceeach'].", unit_of_measurement=".$data['uom']." where sales_order_id=".$data['salesorder_id']." and inventory_id=".$data['inventory_id']."");
					}
				}
				else
				{
					$this->db->query("update sales_order_line set quantity_sold=".$data['amount'].", selling_price_each=".$data['sellpriceeach'].", unit_of_measurement=".$data['uom']." where sales_order_id=".$data['salesorder_id']." and inventory_id=".$data['inventory_id']."");
				}
			}
			else
			{
				$this->db->query("insert into sales_order_line values(".$data['salesorder_id'].",".$data['inventory_id'].",".$data['amount'].",".$data['sellpriceeach'].",".$data['uom'].", 'awaiting_release')");
			}
		}
		if(!$this->db->trans_status()===FALSE)
		{
			$this->db->trans_commit();
			return true;
		}
		else
		{
			$this->db->trans_rollback();
			$message=$this->session->set_flashdata("message", "<span class='rd'>Sales order creation failed. Database error occurred.<span>");
			return $message;
		}
	}
	
	function delete_md()
	{
		$salesorder_id=$this->db->escape($this->input->post('salesorder_id'));
		$inventory_id=$this->db->escape($this->input->post('inventory_id'));
		$gt=$this->input->post('gt');
		//cek clearance level user terkait (foreach role yg di session, each ngecek ke table approval_structure).
		$this->load->model('M_home');
		$role=$this->M_home->get_menu(unserialize(($this->session->userdata('menu'))),'role_id');
		$rolid=array_column($role,'role_id');
		$rolidtopass = join("','",$rolid);
		$query=$this->db->query("select max(clearance_level) as clearance_level from approval_structure where status='active' and amount<=".$gt." and operation_id=4 and object_id=1 and role_id in ('$rolidtopass')");
		$clearance=$query->result_array();
		$query=$this->db->query("select max(a.clearance_level) as max from approval as a where a.docnum=".$salesorder_id."");
		$result=$query->result_array();
		if($clearance[0]['clearance_level']>=$result[0]['max'])
		{
			$this->db->trans_begin();
			for($i=0;$i<count($inventory_id);$i++)
			{
				$data=array("salesorder_id"=>$salesorder_id,"inventory_id"=>$inventory_id[$i]);
				$this->db->query("update sales_order_line set status='canceled' where sales_order_id=".$data['salesorder_id']." and inventory_id=".$data['inventory_id']."");
			}
			$this->db->query("update sales_order set status='canceled' where id=".$data['salesorder_id']."");
			if(!$this->db->trans_status()===FALSE)
			{
				$this->db->trans_commit();
				return true;
			}
			else
			{
				$this->db->trans_rollback();
				$message=$this->session->set_flashdata("message", "<span class='rd'>Sales order cancelation failed. Database error occurred.<span>");
				return $message;
			}
		}
		else
		{
			$message=$this->session->set_flashdata("message", "<span class='rd'>You don't have enough permission to cancel this document.<span>");
			return $message;		
		}
	}

	function delete_ts()
	{
		$salesorder_id=$this->db->escape($this->input->post('salesorder_id'));
		$inventory_id=$this->db->escape($this->input->post('inventory_id'));
		$query=$this->db->query("delete from sales_order_line where sales_order_id=".$salesorder_id." and inventory_id=".$inventory_id."");
		if($query)
		{
			return true;
		}
		else
		{
			$this->db->trans_rollback();
			$message=$this->session->set_flashdata("message", "<span class='rd'>Item deletion failed. Database error occurred.<span>");
			return $message;
		}
	}

	function approve()
	{
		//cek butuh approval siapa aja based on amount
		$salesorder_id=$this->db->escape($this->input->post('salesorder_id'));
		$gt=$this->input->post('gt');
		$query=$this->db->query("select * from approval_structure where status='active' and operation_id=4 and object_id=1 and amount<=".$gt."");// 4 adalah approval, id 1 adalah untuk dokumen sales order
		$structure=$query->result_array();
		if($query->num_rows()>0)
		{
			//cek apakah role ini perlu approve, lalu cek clearance level user terkait (foreach role yg di session, each ngecek ke table approval_structure).
			$this->load->model('M_home');
			$role=$this->M_home->get_menu(unserialize(($this->session->userdata('menu'))),'role_id');
			$rolid=array_column($role,'role_id');
			$rolidtopass = join("','",$rolid);
			$query=$this->db->query("select * from approval_structure where status='active' and amount<=".$gt." and operation_id=4 and object_id=1 and role_id in ('$rolidtopass') order by clearance_level asc");
			$clearance=$query->result_array();
			if(count($clearance)>0)//CEK FOREACH ROLE DAN APPROVE SEMUA KALO DIA MULTI ROLE
			{
				for($i=0;$i<count($clearance);$i++)
				{
					//cek apakah dokumen terkait udh pernah di-approve apa belum sama role ini
					$query=$this->db->query("select id from approval where operation_id=4 and object_id=1 and clearance_level=".$clearance[$i]['clearance_level']." and docnum=".$salesorder_id." and role_id=".$this->db->escape($clearance[$i]['role_id'])."");
					if($query->num_rows() == 0)
					{
						//cek apakah jenjang persis sebelumnya udah approve (count nya pake count clearance level)
						if($clearance[$i]['clearance_level'] > 1)
						{
							$query=$this->db->query("select id from approval where operation_id=4 and object_id=1 and clearance_level=".($clearance[$i]['clearance_level']-1)." and docnum=".$salesorder_id."");
							if($query->num_rows()==1)
							{
								$multi=1;
								$single=0;
							}
							else
							{
								$multi=0;
								$single=1;
							}
						}
						else
						{
							$query=$this->db->query("select id from approval where operation_id=4 and object_id=1 and clearance_level=".$clearance[$i]['clearance_level']." and docnum=".$salesorder_id."");
							if($query->num_rows()==0)
							{
								$multi=1;
								$single=0;
							}
							else
							{
								$multi=0;
								$single=1;
							}
						}
						if($multi==1 or $single==0)//multi level dan single level beda kondisi
						{
							//insert approval sesuai status kelengkapan
							$username = $this->db->escape($this->session->userdata('username'));
							$query=$this->db->query("select ifnull(max(id),0) as id from approval");
							$getappid=$query->row_array();
							$query=$this->db->query("insert into approval values(".($getappid['id']+1).",curdate(),".$salesorder_id.",".$clearance[$i]['clearance_level'].",".$this->db->escape($clearance[$i]['role_id']).",4,1,".$username.")");
						}
					}
					else
					{
						continue;
					}
				}
				//cek apakah approval udh lengkap, untuk nentuin status partially atau fully approved
				$query=$this->db->query("select *
											from (select max(clearance_level) as max from approval where docnum=".$salesorder_id.") as a 
											join (select max(clearance_level) as refmax from approval_structure where status='active' and object_id=1 and amount<=".$gt.") as b
											on a.max=b.refmax");
				if($query->num_rows() == 0)//partially approved
				{
					$this->db->query("update sales_order set status='partially_approved' where id=".$salesorder_id."");
				}
				else//fully approved
				{
					$this->db->query("update sales_order set status='fully_approved' where id=".$salesorder_id."");
				}
				return true;
			}
			else
			{
				$message=$this->session->set_flashdata("message", "<span class='rd'>You don't have approval permission for this object.<span>");
				return $message;			
			}
		}
		else
		{
			$message=$this->session->set_flashdata("message", "<span class='rd'>No approval structure has been set for this object. Contact your administrator.<span>");
			return $message;
		}
	}
}

?>