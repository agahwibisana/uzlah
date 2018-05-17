<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		back to <a href="<?php echo base_url().'C_role/read_all'?>">View All Roles</a><br/><br/>
	</div>
	<div></div><div></div><div></div><div></div>
	<div>
		<form action="<?php echo base_url().'C_role/update/'.$role['id']?>" method="post">
			<input type="submit" value="Edit" name="edit">&nbsp
		</form>
		<?php if($role['status']=="active")
		{
			echo
			"<form action=".base_url()."C_role/suspend/".$role['id']." method='post'>
			<input type='submit' value='Suspend' name='suspend'>&nbsp
			</form>";
		}
		?>
		<?php if($role['status']=="suspended")
		{
			echo
			"<form action=".base_url()."C_role/reactivate/".$role['id']." method='post'>
			<input type='submit' value='Reactivate' name='reactivate'>&nbsp
			</form>";
		}
		?>
	</div>
	<div>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="role_id">Role ID</label>
	</div>
	<div>
		:&nbsp<?php echo $role['id'];?>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="desc">Description</label>
	</div>
	<div>
		:&nbsp<?php echo $role['description'];?>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="status">Status</label>
	</div>
	<div>
		:&nbsp<?php echo $role['status'];?>
	</div>
	<div></div><div></div><div></div>
</div>
<!--belum diadjust yg dibawah ini-->
<div class="block-row-xxs-1 no-gutter pad">
	<div class="table-scrollable">
		<table class="table-striped">
			<tbody>
				<tr class='border'>
					<td><b>Privilege ID</b></td>
					<td><b>Description</b></td>
					<td><b>Status</b></td>
				</tr>
				<?php
				if (is_array($permission)){
					foreach($permission as $row)
					{	
						echo "<tr class='border'>
							<td>".$row['object_id']." ".$row['operation_id']."</a></td>
							<td>".$row['name2']." ".$row['name1']."</td>
							<td>".$row['status']."</td>
						</tr>";
				}}
				?>
			</tbody>
		</table>
	</div>
</div>
<div class="block-row-xxs-5 no-gutter pad">
	<div>
		<form action="<?php echo base_url().'C_permission/create/'.$role['id']?>" method="post" style='display:inline'>
			<input type="submit" value="Assign Permissions" name="createpermission">&nbsp
		</form>
		<form action="<?php echo base_url().'C_permission/update/'.$role['id']?>" method="post" style='display:inline'>
			<input type="submit" value="Edit Permissions" name="assignpermission">&nbsp
		</form>
	</div>
	<div></div><div></div><div></div>
</div>