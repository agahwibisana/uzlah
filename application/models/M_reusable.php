<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class M_reusable extends CI_Model
{
	//0. foreach transaction function in controller is triggered, call these function:
	//1. cek apakah ada user?
	//2. cek apakah user ini punya permission untuk pake transaksi ini?
	//3. apakah ini ada parameternya? kalo ga ada, diredirect aja ke index controller.
	//4. apakah ini view atau proses transaksi atau ada keduanya?
	
	function get_session()
	{
		if($this->session->userdata('username'))
		{
			if($this->session->userdata('menu'))
			{
				$controller=$this->uri->segment(1);
				$method=$this->uri->segment(2);
				$session = unserialize(($this->session->userdata('menu')));
				foreach($session as $row)
				{
					if($row['controller']===$controller && $row['method']===$method)
					{
						$result = true;
						break;
					}
				}
				if(isset($result))
				{
					return $result;
				}
				else
				{
					return false;
				}
			}
		}
		else
		{
			return false;
		}
	}
}

?>