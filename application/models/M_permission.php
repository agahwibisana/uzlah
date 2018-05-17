<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class M_permission extends CI_Model
{

	function get_permission()
	{
		$query = $this->db->query("select f.*,g.description from (select a.role_id,a.object_id,a.operation_id,a.status,e.name1,e.name2 from permission as a join (select d.object_id,d.operation_id,b.friendly_name as name2,c.friendly_name as name1 from privilege as d join object as c on c.id=d.object_id join operation as b on b.id=d.operation_id) as e on a.object_id=e.object_id and a.operation_id=e.operation_id) as f join role as g on f.role_id=g.id  order by f.role_id,f.object_id,f.operation_id");		
		return $query->result_array();
	}

	function get_permission_one($param)
	{
		$query = $this->db->query("select f.*,g.description from (select a.role_id,a.object_id,a.operation_id,a.status,e.name1,e.name2 from permission as a join (select d.object_id,d.operation_id,b.friendly_name as name2,c.friendly_name as name1 from privilege as d join object as c on c.id=d.object_id join operation as b on b.id=d.operation_id) as e on a.object_id=e.object_id and a.operation_id=e.operation_id) as f join role as g on f.role_id=g.id  where f.role_id='".$param."' order by f.role_id,f.object_id,f.operation_id");		
		if($query -> num_rows() < 1)
		{
    		return true;		
		}
		else
		{
			return $query->result_array();
		}
	}

	function get_active_privilege()
	{
		$query = $this->db->query("select a.*,module.name from (select privilege.*,object.friendly_name as name1,operation.friendly_name as name2,object.module_id from privilege join object on object.id=privilege.object_id join operation on operation.id=privilege.operation_id where privilege.status='active') as a join module on module.id=a.module_id order by module.id,a.object_id,a.operation_id");		
		return $query->result_array();
	}

	function create($param)
	{
		if(!$this->input->post('createpermission'))
		{
			$den="denied";
			return $den;				
		}
		else
		{
			$query= $this->db->query("select * from role where id='".$param."'");
			if($query -> num_rows() < 1)
			{
				$not="notexist";
				return $not;		
			}
			else if($this->input->post('object_id') and $this->input->post('operation_id'))
			{				
				$role_id=$this->db->escape($this->input->post('role_id'));
				$object_id=$this->db->escape($this->input->post('object_id'));
				$operation_id=$this->db->escape($this->input->post('operation_id'));
				$query=$this->db->query("select * from object where id=".$object_id."");
				$result=$query->row_array();
				if($result['module_id']!=="2")//2 adalah module dev
				{
					$query= $this->db->query("select * from permission where role_id='".$param."' and operation_id=".$operation_id." and object_id=".$object_id."");
					if($query -> num_rows() < 1)
					{
						$query=$this->db->query("insert into permission(role_id,object_id,operation_id,status) values(".$role_id.",".$object_id.",".$operation_id.",'active')");
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
					$den="denied";
					return $den;					
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
		if(!$this->input->post('assignpermission'))
		{
			$den="denied";
			return $den;				
		}
		else
		{
			$query= $this->db->query("select * from role where id='".$param."'");
			if($query -> num_rows() < 1)
			{
				$not="notexist";
				return $not;		
			}
			else if($this->input->post('status'))
			{
				$role_id=$this->db->escape($this->input->post('role_id'));
				$object_id=$this->db->escape($this->input->post('object_id'));
				$operation_id=$this->db->escape($this->input->post('operation_id'));
				$status=$this->db->escape($this->input->post('status'));
				for($i=0;$i<count($role_id);$i++)
				{
					$data=array("role_id"=>$role_id[$i],"object_id"=>$object_id[$i],"operation_id"=>$operation_id[$i],"status"=>$status[$i]);
					$query = $this->db->query("select * from privilege where object_id=".$data['object_id']." and operation_id=".$data['operation_id']."  and status='active'");		
					if($query->num_rows() > 0)
					{
						$query = $this->db->query("select * from role where id=".$data['role_id']." and status='active'");
						if($query->num_rows() > 0)
						{
							$this->db->query("update permission set status=".$data['status']." where role_id=".$data['role_id']." and object_id=".$data['object_id']." and operation_id=".$data['operation_id']."");
						}
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