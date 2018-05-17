<form action="<?php echo base_url().'C_privilege/create/'.$object; ?>" method="post">
<div class="block-row-xxs-5 no-gutter pad">
	<div>
		<label for="object_id">Object ID</label>
	</div>
	<div>
		<input type="text" value="<?php echo $object;?>" name="object_id" readonly>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="operation_id">Operation ID</label>
	</div>
	<div>
		<select required name='operation_id'>
				<?php foreach($operation as $row)
				{echo
				"<option value=".$row['id'].">".$row['friendly_name']."</option>";
				}?>
		</select>
	</div>
	<div></div><div></div><div></div>
	<div>
	</div>
	<div>
		<input type="submit" value="Assign" name='createoperation'/>
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
					<td><b>Name</b></td>
					<td><b>Controller</b></td>
					<td><b>Operation ID</b></td>
					<td><b>Name</b></td>
					<td><b>Method</b></td>
					<td><b>Status</b></td>
					</tr>
					<?php
					if (is_array($privilege))
					{
						foreach($privilege as $row)
						{	
							echo "<tr class='border'>
								<td><input type='text' name='object_idr' value='".$row['object_id']."' readonly></td>
								<td><input type='text' name='name1r' value='".$row['name1']."' readonly></td>
								<td><input type='text' name='contr' value='".$row['controller']."' readonly></td>
								<td><input type='text' name='operation_idr' value='".$row['operation_id']."' readonly></td>
								<td><input type='text' name='name2r' value='".$row['name2']."' readonly></td>
								<td><input type='text' name='methodr' value='".$row['method']."' readonly></td>
								<td><input type='text' name='statusr' value='".$row['status']."' readonly></td>
							</tr>";
						}
					}
					?>
				</tbody>
			</table>
		</div>
</div>