<form action="<?php echo base_url().'C_privilege/update/'.$object; ?>" method="post">
<div class="block-row-xxs-1 no-gutter pad">
	<div>
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
					<?php if (is_array($privilege)){foreach($privilege as $row)
					{
						echo "<tr class='border'>
							<td><input type='text' name='object_id[]' value='".$row['object_id']."' readonly></td>
							<td><input type='text' name='name1[]' value='".$row['name1']."' readonly></td>
							<td><input type='text' name='cont[]' value='".$row['controller']."' readonly></td>
							<td><input type='text' name='operation_id[]' value='".$row['operation_id']."' readonly></td>
							<td><input type='text' name='name2[]' value='".$row['name2']."' readonly></td>
							<td><input type='text' name='method[]' value='".$row['method']."' readonly></td>
							<td><select required name='status[]'>
									<option value='suspended' ".(($row['status']=='suspended')?"selected='selected'":'').">Suspended</option>
									<option value='active' ".(($row['status']=='active')?"selected='selected'":'').">Active</option>
								</select>
							</td>
						</tr>";
					}}?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php if(is_array($privilege))
{echo
"<div class='block-row-xxs-1 block-row-s-5 no-gutter pad'>
	<div>
		<input type='submit' value='Save' name='assignoperation'/>
	</div>
	<div>
	</div>
	<div></div><div></div><div></div>
</div>";}?>
</form>