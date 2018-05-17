<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class M_module extends CI_Model
{
	function get_module_id()
	{
		$query=$this->db->query("SELECT max(ExtractNumber(id)) as max_id from module");
		$result=$query->row_array();
		/*nextid*/
		$module_id=$result['max_id']+1;
		return $module_id;	
	}

	function get_sort()
	{
		$query=$this->db->query("SELECT max(ExtractNumber(sort)) as max_sort from module");
		$result=$query->row_array();
		/*nextsort*/
		$sort=$result['max_sort']+1;
		return $sort;	
	}

	function create()
	{
	    $id=$this->db->escape($this->input->post('id'));
		$name=$this->db->escape($this->input->post('name'));
		$sort=$this->input->post('sort');
		
		$query=$this->db->query("select id from module where id=".$id."");
		if($query -> num_rows() <= 0)
		{
			$query=$this->db->query("insert into module values(".$id.",".$name.",'Active',".$sort.")");
			if($query)
			{
				return true;
			}
			else
			{
				$message=$this->session->set_flashdata("message", "<span class='rd'>Database error.</span>");
				return $message;    		    
			}
		}
        else
        {
    		$message=$this->session->set_flashdata("message", "<span class='rd'>Module ID is already exist.</span>");
    		return $message;            
        }
	}

	function get_module()
	{
		$query = $this->db->query("select * from module");		
		return $query->result_array();
	}

	function get_module_one($param)
	{
		$query = $this->db->query("select * from module where id='".$param."'");
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
			$query= $this->db->query("select id,status from module where id='".$param."'");
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
					$query = $this->db->query("update module set status='Suspended' where id='".$param."'");
					$message=$this->session->set_flashdata("message", "<span class='gr'>Module has been successfully suspended.</span>");
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
			$query= $this->db->query("select id,status from module where id='".$param."'");
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
					$query = $this->db->query("update module set status='Active' where id='".$param."'");
					$message=$this->session->set_flashdata("message", "<span class='gr'>Module has been successfully reactivated.</span>");
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
			$query= $this->db->query("select * from module where id='".$param."'");
			if($query -> num_rows() < 1)
			{
				$not="notexist";
				return $not;		
			}
			else if($this->input->post('sort') and $this->input->post('name'))
			{
				$name=$this->db->escape($this->input->post('name'));
				$sort=$this->input->post('sort');
				$query= $this->db->query("update module set name=".$name.",sort=".$sort." where id='".$param."'");
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