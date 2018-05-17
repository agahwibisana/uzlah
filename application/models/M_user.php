<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class M_user extends CI_Model 
{
	function create()
	{
	    $user_id=$this->db->escape($this->input->post('user_id'));
		$pwd=$this->db->escape(md5($this->input->post('pwd')));
		$name=$this->db->escape($this->input->post('name'));
		
		$query=$this->db->query("select id from user where id=".$user_id."");
		if($query -> num_rows() <= 0)
		{
			$query=$this->db->query("insert into user values(".$user_id.",".$pwd.",".$name.",'Active')");
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
    		$message=$this->session->set_flashdata("message", "<span class='rd'>User ID is already exist.<span>");
    		return $message;            
        }
	}

	function get_user()
	{
		$query = $this->db->query("select * from user");		
		return $query->result_array();
	}

	function get_user_one($param)
	{
		$query = $this->db->query("select * from user where id='".$param."'");
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
			$query= $this->db->query("select id,status from user where id='".$param."'");
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
					$query = $this->db->query("update user set status='Suspended' where id='".$param."'");
					$message=$this->session->set_flashdata("message", "<span class='gr'>User has been successfully suspended.<span>");
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
			$query= $this->db->query("select id,status from user where id='".$param."'");
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
					$query = $this->db->query("update user set status='Active' where id='".$param."'");
					$message=$this->session->set_flashdata("message", "<span class='gr'>User has been successfully reactivated.<span>");
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
			$query= $this->db->query("select * from user where id='".$param."'");
			if($query -> num_rows() < 1)
			{
				$not="notexist";
				return $not;		
			}
			else if($this->input->post('name'))
			{
				$name=$this->db->escape($this->input->post('name'));
				$query= $this->db->query("update user set friendly_name=".$name." where id='".$param."'");
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