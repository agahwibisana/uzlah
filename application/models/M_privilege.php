<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class M_privilege extends CI_Model
{

	function get_privilege()
	{
		$query = $this->db->query("select a.object_id,a.operation_id,a.status,b.controller,b.friendly_name as name1,c.friendly_name as name2,c.method from privilege as a join object as b on b.id=a.object_id join operation as c on c.id=a.operation_id order by object_id,operation_id");		
		return $query->result_array();
	}

	function get_privilege_one($param)
	{
		$query = $this->db->query("select a.object_id,a.operation_id,a.status,b.controller,b.friendly_name as name1,c.friendly_name as name2,c.method from privilege as a join object as b on b.id=a.object_id join operation as c on c.id=a.operation_id where object_id=".$param." order by object_id,operation_id");		
		if($query -> num_rows() < 1)
		{
    		return true;		
		}
		else
		{
			return $query->result_array();
		}
	}

	function get_active_operation()
	{
		$query = $this->db->query("select * from operation where status='active' order by id");		
		return $query->result_array();
	}

	function create($param)
	{
		if(!$this->input->post('createoperation'))
		{
			$den="denied";
			return $den;				
		}
		else
		{
			$query= $this->db->query("select * from object where id='".$param."'");
			if($query -> num_rows() < 1)
			{
				$not="notexist";
				return $not;		
			}
			else if($this->input->post('operation_id'))
			{				
				$object_id=$this->db->escape($this->input->post('object_id'));
				$operation_id=$this->db->escape($this->input->post('operation_id'));
				$query= $this->db->query("select * from privilege where object_id='".$param."' and operation_id=".$operation_id."");
				if($query -> num_rows() < 1)
				{
					$query=$this->db->query("insert into privilege(object_id,operation_id,status,visibility) values(".$object_id.",".$operation_id.",'active',1)");
					return true;
				}
				else
				{
					$dupl="dupl";
					return $dupl;
				}
			}
			else
			{
				$result= $query->row_array();
				return $result;		
			}
		}
	}
	
	function update($param)
	{
		if(!$this->input->post('assignoperation'))
		{
			$den="denied";
			return $den;				
		}
		else
		{
			$query= $this->db->query("select * from object where id='".$param."'");
			if($query -> num_rows() < 1)
			{
				$not="notexist";
				return $not;		
			}
			else if($this->input->post('status'))
			{
				$object_id=$this->db->escape($this->input->post('object_id'));
				$operation_id=$this->db->escape($this->input->post('operation_id'));
				$status=$this->db->escape($this->input->post('status'));
				for($i=0;$i<count($object_id);$i++)
				{
					$data=array("object_id"=>$object_id[$i],"operation_id"=>$operation_id[$i],"status"=>$status[$i]);
					$query = $this->db->query("select * from object where id=".$data['object_id']." and status='active'");		
					if($query->num_rows() > 0)
					{
						$this->db->query("update privilege set status=".$data['status']." where object_id=".$data['object_id']." and operation_id=".$data['operation_id']."");
						//NTAR DICODING
						//kalau dia suspend->maka permission ikut kesuspend.
						//kalau dia activate->maka permission tidak ikut aktif (takut mengoverride permission yg emg di-set suspend manual via menu suspend permission.
					}
				}
				return true;
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