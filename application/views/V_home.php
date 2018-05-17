<?php if($this->session->userdata('username'))
	{
		if($this->session->userdata('menu'))
		{
			echo
			"<div class='block-row-xxs-1 no-gutter pad'>
				<div>
					<p class='nm'>Here are your roles and permissions list:</p>
					<ul>";
			$tosort=unserialize(($this->session->userdata('menu')));
			usort($tosort,function(array $a, array $b)
						{
							return strcmp($a['friendly_role'],$b['friendly_role'])
								?: strcmp($a['module_id'],$b['module_id'])
								?: strcmp($a['obop'],$b['obop']);
						}
				);
			foreach($tosort as $row)
			{
				echo
						"<li>".$row['friendly_role']."->".$row['friendly_meth']." ".$row['friendly_cont']."</li>";
			}
			echo
					"</ul>
				</div>
			</div>";
		}
	}
?>