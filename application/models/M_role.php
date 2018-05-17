<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class M_role extends CI_Model
{
	function get_role_id()
	{
		$query=$this->db->query("SELECT max(ExtractNumber(id)) as max_id from role");
		$result=$query->row_array();
		/*nextid*/
		$role_id="role".($result['max_id']+1);
		return $role_id;	
	}

	function create()
	{
	    $id=$this->db->escape($this->input->post('id'));
		$desc=$this->db->escape($this->input->post('desc'));
		
		$query=$this->db->query("select id from role where id=".$id."");
		if($query -> num_rows() <= 0)
		{
			$query=$this->db->query("insert into role values(".$id.",".$desc.",'Active')");
			if($query)
			{
				return true;
			}
			else
			{
				$message=$this->session->set_flashdata("message", "<span class='rd'>Database error.<span>");
				return $message;    		    
			}
		}
        else
        {
    		$message=$this->session->set_flashdata("message", "<span class='rd'>Role ID is already exist.<span>");
    		return $message;            
        }
	}

	function get_role()
	{
		$query = $this->db->query("select * from role");		
		return $query->result_array();
	}

	function get_role_one($param)
	{
		$query = $this->db->query("select * from role where id='".$param."'");
		if($query -> num_rows() < 1)
		{
    		return true;		
		}
		else
		{
			return $query->row_array();
		}
	}
	
	function suspend($param)
	{
		if(!$this->input->post('suspend'))
		{
			$den="denied";
			return $den;				
		}
		else
		{
			$query= $this->db->query("select id,status from role where id='".$param."'");
			if($query -> num_rows() < 1)
			{
				$not="notexist";
				return $not;		
			}
			else
			{
				$result= $query->row_array();
				if($result['status']=="suspended")
				{
					return true;	
				}
				else
				{
					$query = $this->db->query("update role set status='Suspended' where id='".$param."'");
					$query = $this->db->query("update role_assignment set status='Suspended' where role_id='".$param."'");
					$message=$this->session->set_flashdata("message", "<span class='gr'>Role and its assignment has been successfully suspended.<span>");
					return $message;		
				}
			}
		}
	}

	function reactivate($param)
	{
		if(!$this->input->post('reactivate'))
		{
			$den="denied";
			return $den;				
		}
		else
		{
			$query= $this->db->query("select id,status from role where id='".$param."'");
			if($query -> num_rows() < 1)
			{
				$not="notexist";
				return $not;		
			}
			else
			{
				$result= $query->row_array();
				if($result['status']=="active")
				{
					return true;	
				}
				else
				{
					$query = $this->db->query("update role set status='Active' where id='".$param."'");
					//in theory, reactivate beda dengan suspend.
					//kalo reactivate, tidak serta-merta semua user dg role terkait assignmentnya jadi aktif.
					//kalo rola eassignmentnya mau diaktifin, harus satu2 manual.
					//$query = $this->db->query("update role_assignment set status='Active' where role_id='".$param."'");
					$message=$this->session->set_flashdata("message", "<span class='gr'>Role has been successfully reactivated. Note that you still have to manually assign this role again to affected users.<span>");
					return $message;		
				}
			}
		}
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
			$query= $this->db->query("select * from role where id='".$param."'");
			if($query -> num_rows() < 1)
			{
				$not="notexist";
				return $not;		
			}
			else if($this->input->post('desc'))
			{
				$desc=$this->db->escape($this->input->post('desc'));
				$query= $this->db->query("update role set description=".$desc." where id='".$param."'");
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