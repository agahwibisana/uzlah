<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//these functions are only called once during login
Class M_home extends CI_Model
{
	function user_authentication()
	{
		$username = $this->db->escape($this->input->post('username'));
		$password = $this->db->escape(md5($this->input->post('password')));
		$query = $this->db->query("select id from user where id=".$username." and password =".$password." and status='active'");
		if($query -> num_rows() == 1)
		{
			$result= $query->row_array();
			return $result;
		}
		else
		{
			return false;
		}
	}
	
	function get_userdata()
	{
		$username = $this->db->escape($this->input->post('username'));
		$query = $this->db->query("select id,friendly_name from user where id=".$username." and status='active'");
		if($query)
		{
			$result= $query->result_array();
			return $result;
		}
		else
		{
			return false;
		}
	}
	
	function user_authorization()
	{
		$username = $this->db->escape($this->session->userdata('username'));
		$query = $this->db->query("select *,concat(l.object_id,l.operation_id) as obop from
										(select j.user_id, k.friendly_name as friendly_user, j.period_start, j.period_end,i.* from role_assignment as j join user as k on j.user_id=k.id
										join (select g.role_id, h.description as friendly_role, f.* from permission as g 
											 join role as h on g.role_id=h.id
											 join (select c.module_id, d.object_id, d.operation_id, c.name as friendly_mod, c.controller, c.friendly_name as friendly_cont, e.method, e.friendly_name as friendly_meth,c.module_sort,c.object_sort,e.sort as operation_sort from privilege as d join (select a.id, a.controller, a.friendly_name, b.id as module_id, b.name,a.sort as object_sort,b.sort as module_sort from object as a join module as b on a.module_id=b.id where a.status='active' and b.status='active') as c on d.object_id=c.id join operation as e on d.operation_id=e.id where d.status='active') as f on g.object_id=f.object_id and g.operation_id=f.operation_id
											 where g.status='active') as i
										on i.role_id=j.role_id where j.status='active') as l
									where l.user_id=".$username."
									order by module_sort asc, object_sort asc, operation_sort asc");
		if($query -> num_rows() > 0)
		{
			$result= $query->result_array();
			return $result;
		}
		else
		{
			return false;
		}
	}
	
	function get_menu($array, $key)
	{
		$temp_array = array(); 
		$i = 0; 
		$key_array = array();
		foreach($array as $val)
		{
			if(!in_array($val[$key], $key_array))
			{
				$key_array[$i] = $val[$key];
				$temp_array[$i] = $val;
			}
			$i++;
		}
		return $temp_array; 
	}
}

?>