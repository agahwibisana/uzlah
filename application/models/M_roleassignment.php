<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class M_roleassignment extends CI_Model
{

	function get_roleassignment()
	{
		$query = $this->db->query("select a.user_id,a.role_id,a.status,role.description,user.friendly_name from role_assignment as a join role on role.id=a.role_id join user on user.id=a.user_id order by user_id,role_id");		
		return $query->result_array();
	}

	function get_roleassignment_one($param)
	{
		$query = $this->db->query("select a.user_id,a.role_id,a.status,role.description,user.friendly_name from role_assignment as a join role on role.id=a.role_id join user on user.id=a.user_id where a.user_id='".$param."' order by user_id,role_id");
		if($query -> num_rows() < 1)
		{
    		return true;		
		}
		else
		{
			return $query->result_array();
		}
	}

	function get_active_role()
	{
		$query = $this->db->query("select * from role where status='active' order by id");		
		return $query->result_array();
	}

	function create($param)
	{
		if(!$this->input->post('createrole'))
		{
			$den="denied";
			return $den;				
		}
		else
		{
			$query= $this->db->query("select * from user where id='".$param."'");
			if($query -> num_rows() < 1)
			{
				$not="notexist";
				return $not;		
			}
			else if($this->input->post('role_id'))
			{				
				$user_id=$this->db->escape($this->input->post('user_id'));
				$role_id=$this->db->escape($this->input->post('role_id'));
				if($role_id=="'role1'" or $user_id=="'superuser'") //ga bisa assign role dev dan ga bisa assign superuser. harusnya sih superuser bsia assign diri sendiri
				{
					$den="denied";
					return $den;
				}
				else
				{
					$query= $this->db->query("select * from role_assignment where user_id='".$param."' and role_id=".$role_id."");
					if($query -> num_rows() < 1)
					{
						$query=$this->db->query("insert into role_assignment(user_id,role_id,status) values(".$user_id.",".$role_id.",'active')");
						return true;
					}
					else
					{
						$dupl="dupl";
						return $dupl;
					}					
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
		if(!$this->input->post('assignrole'))
		{
			$den="denied";
			return $den;				
		}
		else
		{
			$query= $this->db->query("select * from user where id='".$param."'");
			if($query -> num_rows() < 1)
			{
				$not="notexist";
				return $not;		
			}
			else if($this->input->post('status'))
			{
				$user_id=$this->db->escape($this->input->post('user_id'));
				$role_id=$this->db->escape($this->input->post('role_id'));
				$status=$this->db->escape($this->input->post('status'));
				for($i=0;$i<count($user_id);$i++)
				{
					$data=array("user_id"=>$user_id[$i],"role_id"=>$role_id[$i],"status"=>$status[$i]);
					$query = $this->db->query("select * from role where id=".$data['role_id']." and status='active'");		
					if($query->num_rows() > 0)
					{
						$query = $this->db->query("select * from user where id=".$data['user_id']." and status='active'");
						if($query->num_rows() > 0)
						{
							$this->db->query("update role_assignment set status=".$data['status']." where user_id=".$data['user_id']." and role_id=".$data['role_id']."");
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