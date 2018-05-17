<form action="<?php echo base_url().'C_roleassignment/create/'.$user; ?>" method="post">
<div class="block-row-xxs-5 no-gutter pad">
	<div>
		<label for="user_id">User ID</label>
	</div>
	<div>
		<input type="text" value="<?php echo $user;?>" name="user_id" readonly>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="role_id">Role ID</label>
	</div>
	<div>
		<select required name='role_id'>
				<?php foreach($role as $row)
				{echo
				"<option value=".$row['id'].">".$row['description']."</option>";
				}?>
		</select>
	</div>
	<div></div><div></div><div></div>
	<div>
	</div>
	<div>
		<input type="submit" value="Assign" name='createrole'/>
	</div>
	<div></div><div></div><div></div>
</div>
</form>
<div class="block-row-xxs-1 no-gutter pad">
		<div class="table-scrollable">
			<table class="table-striped">
				<tbody>
					<tr class='border'>
						<td><b>User ID</b></td>
						<td><b>Name</b></td>
						<td><b>Role ID</b></td>
						<td><b>Description</b></td>
						<td><b>Status</b></td>
					</tr>
					<?php
					if (is_array($roleassignment))
					{
						foreach($roleassignment as $row)
						{	
							echo "<tr class='border'>
								<td><input type='text' name='user_idr' value='".$row['user_id']."' readonly></td>
								<td><input type='text' name='namer' value='".$row['friendly_name']."' readonly></td>
								<td><input type='text' name='role_idr' value='".$row['role_id']."' readonly></td>
								<td><input type='text' name='descr' value='".$row['description']."' readonly></td>
								<td><input type='text' name='statusr' value='".$row['status']."' readonly></td>
							</tr>";
						}
					}
					?>
				</tbody>
			</table>
		</div>
</div>