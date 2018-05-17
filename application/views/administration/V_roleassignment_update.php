<form action="<?php echo base_url().'C_roleassignment/update/'.$user; ?>" method="post">
<div class="block-row-xxs-1 no-gutter pad">
	<div>
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
					<?php if (is_array($roleassignment)){foreach($roleassignment as $row)
					{
						echo "<tr class='border'>
							<td><input type='text' name='user_id[]' value='".$row['user_id']."' readonly></td>
							<td><input type='text' name='name[]' value='".$row['friendly_name']."' readonly></td>
							<td><input type='text' name='role_id[]' value='".$row['role_id']."' readonly></td>
							<td><input type='text' name='desc[]' value='".$row['description']."' readonly></td>
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
<?php if(is_array($roleassignment))
{echo
"<div class='block-row-xxs-1 block-row-s-5 no-gutter pad'>
	<div>
		<input type='submit' value='Save' name='assignrole'/>
	</div>
	<div>
	</div>
	<div></div><div></div><div></div>
</div>";}?>
</form>