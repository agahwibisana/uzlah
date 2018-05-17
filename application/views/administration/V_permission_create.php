<form action="<?php echo base_url().'C_permission/create/'.$role; ?>" method="post">
<div class="block-row-xxs-5 no-gutter pad">
	<div>
		<label for="role_id">Role ID</label>
	</div>
	<div>
		<input type="text" value="<?php echo $role;?>" name="role_id" readonly>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="object_id">Object ID</label>
	</div>
	<div>
		<select required name='object_id'>
				<?php foreach($privilege as $row)
				{echo
				"<option value=".$row['object_id'].">".$row['name1']."</option>";
				}?>
		</select>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="operation_id">Operation ID</label>
	</div>
	<div>
		<select required name='operation_id'>
				<?php foreach($privilege as $row)
				{echo
				"<option value=".$row['operation_id'].">".$row['name2']."</option>";
				}?>
		</select>
	</div>
	<div></div><div></div><div></div>
	<div>
	</div>
	<div>
		<input type="submit" value="Assign" name='createpermission'/>
	</div>
	<div></div><div></div><div></div>
</div>
</form>
<div class="block-row-xxs-1 no-gutter pad">
		<div class="table-scrollable">
			<table class="table-striped">
				<tbody>
					<tr class='border'>
						<td><b>Object ID</b></td>
						<td><b>Description</b></td>
						<td><b>Privilege ID</b></td>
						<td><b>Description</b></td>
						<td><b>Status</b></td>
					</tr>
					<?php
					if (is_array($permission))
					{
						foreach($permission as $row)
						{	
							echo "<tr class='border'>
								<td>".$row['object_id']."</td>
								<td>".$row['name1']."</td>
								<td>".$row['operation_id']."</td>
								<td>".$row['name2']."</td>
								<td>".$row['status']."</td>
							</tr>";
						}
					}
					?>
				</tbody>
			</table>
		</div>
</div>