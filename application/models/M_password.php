<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class M_password extends CI_Model
{
	function update()
	{
	    $id=$this->db->escape($this->session->userdata('username'));
		$old_pwd=$this->db->escape(md5($this->input->post('old_pwd')));
		$new_pwd=$this->db->escape($this->input->post('new_pwd'));
		$new_pwd_cnf=$this->db->escape($this->input->post('new_pwd_cnf'));
		
		$query=$this->db->query("select id from user where id=".$id." and password=".$old_pwd."");
		if($query -> num_rows() > 0)
		{
    		if($new_pwd<>$new_pwd_cnf)
    		{
    			$message=$this->session->set_flashdata("message", "<span class='rd'>New password does not match. Please try again.<span>");
    			return $message;		    
    		}
    		else
    		{
    		    $query=$this->db->query("update user set password=md5(".$new_pwd.") where id=".$id."");
        	    if($query=TRUE)
        		{
        			return true;
        		}
        		else
        		{
    			    $message=$this->session->set_flashdata("message", "<span class='rd'>Database error.<span>");
    			    return $message;    		    
        		}
    	    }
		}
        else
        {
    		$message=$this->session->set_flashdata("message", "<span class='rd'>The old password you entered is incorrect.<span>");
    		return $message;            
        }
	}

	function approve()
	{
	    $id=$this->db->escape($this->input->post('id'));
		$new_pwd=$this->db->escape($this->input->post('new_pwd'));
		$new_pwd_cnf=$this->db->escape($this->input->post('new_pwd_cnf'));
		
		$query=$this->db->query("select id from user where id=".$id."");
		if($query -> num_rows() > 0)
		{
    		if($new_pwd<>$new_pwd_cnf)
    		{
    			$message=$this->session->set_flashdata("message", "<span class='rd'>New password does not match. Please try again.<span>");
    			return $message;		    
    		}
    		else
    		{
    		    $query=$this->db->query("update user set password=md5(".$new_pwd.") where id=".$id."");
        	    if($query=TRUE)
        		{
        			return true;
        		}
        		else
        		{
    			    $message=$this->session->set_flashdata("message", "<span class='rd'>Database error.<span>");
    			    return $message;    		    
        		}
    	    }
		}
        else
        {
    		$message=$this->session->set_flashdata("message", "<span class='rd'>User ID you entered is incorrect.<span>");
    		return $message;            
        }
	}
}

?>