<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		back to <a href="<?php echo base_url().'C_user/read_all'?>">View All Users</a><br/><br/>
	</div>
	<div></div><div></div><div></div><div></div>
	<div>
		<form action="<?php echo base_url().'C_user/update/'.$user['id']?>" method="post" style='display:inline'>
			<input type="submit" value="Edit" name="edit">&nbsp
		</form>
		<?php if($user['status']=="active")
		{
			echo
			"<form action=".base_url()."C_user/suspend/".$user['id']." method='post' style='display:inline'>
			<input type='submit' value='Suspend' name='suspend'>&nbsp
			</form>";
		}
		?>
		<?php if($user['status']=="suspended")
		{
			echo
			"<form action=".base_url()."C_user/reactivate/".$user['id']." method='post' style='display:inline'>
			<input type='submit' value='Reactivate' name='reactivate'>&nbsp
			</form>";
		}
		?>
	</div>
	<div>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="user_id">User ID</label>
	</div>
	<div>
		:&nbsp<?php echo $user['id'];?>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="name">Name</label>
	</div>
	<div>
		:&nbsp<?php echo $user['friendly_name'];?>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="status">Status</label>
	</div>
	<div>
		:&nbsp<?php echo $user['status'];?>
	</div>
	<div></div><div></div><div></div>
</div>
<div class="block-row-xxs-1 no-gutter pad">
	<div class="table-scrollable">
		<table class="table-striped">
			<tbody>
				<tr class='border'>
					<td><b>Role ID</b></td>
					<td><b>Description</b></td>
					<td><b>Status</b></td>
				</tr>
				<?php
				if (is_array($roleassignment)){
					foreach($roleassignment as $row)
					{	
						echo "<tr class='border'>
							<td>".$row['role_id']."</td>
							<td>".$row['description']."</td>
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
		<form action="<?php echo base_url().'C_roleassignment/create/'.$user['id']?>" method="post" style='display:inline'>
			<input type="submit" value="Assign Roles" name="createrole">&nbsp
		</form>
		<form action="<?php echo base_url().'C_roleassignment/update/'.$user['id']?>" method="post" style='display:inline'>
			<input type="submit" value="Edit Roles" name="assignrole">&nbsp
		</form>
	</div>
	<div></div><div></div><div></div>
</div>