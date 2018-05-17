<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class M_operation extends CI_Model
{
	function get_operation_id()
	{
		$query=$this->db->query("SELECT max(ExtractNumber(id)) as max_id from operation");
		$result=$query->row_array();
		/*nextid*/
		$operation_id=$result['max_id']+1;
		return $operation_id;	
	}

	function get_sort()
	{
		$query=$this->db->query("SELECT max(ExtractNumber(sort)) as max_sort from operation");
		$result=$query->row_array();
		/*nextsort*/
		$sort=$result['max_sort']+1;
		return $sort;	
	}

	function create()
	{
	    $id=$this->db->escape($this->input->post('id'));
		$method=$this->db->escape($this->input->post('method'));
		$name=$this->db->escape($this->input->post('name'));
		$sort=$this->input->post('sort');
		
		$query=$this->db->query("select id from operation where id=".$id."");
		if($query -> num_rows() <= 0)
		{
			$query=$this->db->query("insert into operation values(".$id.",".$method.",".$name.",'Active',".$sort.")");
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
    		$message=$this->session->set_flashdata("message", "<span class='rd'>Operation ID is already exist.</span>");
    		return $message;            
        }
	}

	function get_operation()
	{
		$query = $this->db->query("select * from operation");		
		return $query->result_array();
	}

	function get_operation_one($param)
	{
		$query = $this->db->query("select * from operation where id='".$param."'");
		if($query -> num_rows() < 1)
		{
    		return true;		
		}
		else
		{
			return $query->row_array();
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
			$query= $this->db->query("select * from operation where id='".$param."'");
			if($query -> num_rows() < 1)
			{
				$not="notexist";
				return $not;		
			}
			else if($this->input->post('sort') and $this->input->post('method') and $this->input->post('name'))
			{
				$name=$this->db->escape($this->input->post('name'));
				$method=$this->db->escape($this->input->post('method'));
				$sort=$this->input->post('sort');
				$query= $this->db->query("update operation set friendly_name=".$name.",sort=".$sort.",method=".$method." where id='".$param."'");
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