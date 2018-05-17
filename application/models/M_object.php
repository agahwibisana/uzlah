<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class M_object extends CI_Model
{
	function get_object_id()
	{
		$query=$this->db->query("SELECT max(ExtractNumber(id)) as max_id from object");
		$result=$query->row_array();
		/*nextid*/
		$object_id=$result['max_id']+1;
		return $object_id;	
	}

	function get_sort()
	{
		$query=$this->db->query("SELECT max(ExtractNumber(sort)) as max_sort from object");
		$result=$query->row_array();
		/*nextsort*/
		$sort=$result['max_sort']+1;
		return $sort;	
	}
	
	function get_active_module()
	{
		$query=$this->db->query("SELECT * from module where status='active'");
		$result=$query->result_array();
		return $result;
	}

	function create()
	{
	    $id=$this->db->escape($this->input->post('id'));
		$cont=$this->db->escape($this->input->post('cont'));
		$name=$this->db->escape($this->input->post('name'));
		$sort=$this->input->post('sort');
		$module_id=$this->input->post('module_id');
		
		$query=$this->db->query("select id from object where id=".$id."");
		if($query -> num_rows() <= 0)
		{
			$query=$this->db->query("insert into object values(".$id.",".$cont.",".$name.",'Active',".$module_id.",".$sort.")");
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
    		$message=$this->session->set_flashdata("message", "<span class='rd'>Object ID is already exist.</span>");
    		return $message;            
        }
	}

	function get_object()
	{
		$query = $this->db->query("select object.*,module.name from object join module on object.module_id=module.id ");		
		return $query->result_array();
	}

	function get_object_one($param)
	{
		$query = $this->db->query("select object.*,module.name from object join module on module.id=object.module_id where object.id='".$param."'");
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
			$query= $this->db->query("select id,status from object where id='".$param."'");
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
					$query = $this->db->query("update object set status='Suspended' where id='".$param."'");
					$query = $this->db->query("update privilege set status='Suspended' where object_id='".$param."'");
					$message=$this->session->set_flashdata("message", "<span class='gr'>Object and its related privileges has been successfully suspended.</span>");
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
			$query= $this->db->query("select id,status from object where id='".$param."'");
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
					$query = $this->db->query("update object set status='Active' where id='".$param."'");
					$query = $this->db->query("update privilege set status='Active' where object_id='".$param."'");
					$message=$this->session->set_flashdata("message", "<span class='gr'>Object and its related privileges has been successfully reactivated.</span>");
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
			$query= $this->db->query("select * from object where id='".$param."'");
			if($query -> num_rows() < 1)
			{
				$not="notexist";
				return $not;		
			}
			else if($this->input->post('module_id') and $this->input->post('cont') and $this->input->post('sort') and $this->input->post('name'))
			{
				$name=$this->db->escape($this->input->post('name'));
				$cont=$this->db->escape($this->input->post('cont'));
				$sort=$this->input->post('sort');
				$module_id=$this->input->post('module_id');
				$query= $this->db->query("update object set friendly_name=".$name.",sort=".$sort.",controller=".$cont.",module_id=".$module_id." where id='".$param."'");
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