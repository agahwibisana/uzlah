<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		back to <a href="<?php echo base_url().'C_module/read_all'?>">View All Modules</a><br/><br/>
	</div>
	<div></div><div></div><div></div><div></div>
	<div>
		<form action="<?php echo base_url().'C_module/update/'.$module['id']?>" method="post" style='display:inline;'>
			<input type="submit" value="Edit" name="edit">&nbsp
		</form>
		<?php if($module['status']=="active")
		{
			echo
			"<form action=".base_url()."C_module/suspend/".$module['id']." method='post' style='display:inline;'>
			<input type='submit' value='Suspend' name='suspend'>&nbsp
			</form>";
		}
		?>
		<?php if($module['status']=="suspended")
		{
			echo
			"<form action=".base_url()."C_module/reactivate/".$module['id']." method='post' style='display:inline;'>
			<input type='submit' value='Reactivate' name='reactivate'>&nbsp
			</form>";
		}
		?>
	</div>
	<div>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="module_id">Module ID</label>
	</div>
	<div>
		:&nbsp<?php echo $module['id'];?>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="name">Name</label>
	</div>
	<div>
		:&nbsp<?php echo $module['name'];?>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="status">Status</label>
	</div>
	<div>
		:&nbsp<?php echo $module['status'];?>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="sort">Sort</label>
	</div>
	<div>
		:&nbsp<?php echo $module['sort'];?>
	</div>
	<div></div><div></div><div></div>
</div>