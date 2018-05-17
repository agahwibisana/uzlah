<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		back to <a href="<?php echo base_url().'C_object/read_all'?>">View All Objects</a><br/><br/>
	</div>
	<div></div><div></div><div></div><div></div>
	<div>
		<form action="<?php echo base_url().'C_object/update/'.$object['id']?>" method="post" style='display:inline;'>
			<input type="submit" value="Edit" name="edit">&nbsp
		</form>
		<?php if($object['status']=="active")
		{
			echo
			"<form action=".base_url()."C_object/suspend/".$object['id']." method='post' style='display:inline;'>
			<input type='submit' value='Suspend' name='suspend'>&nbsp
			</form>";
		}
		?>
		<?php if($object['status']=="suspended")
		{
			echo
			"<form action=".base_url()."C_object/reactivate/".$object['id']." method='post' style='display:inline;'>
			<input type='submit' value='Reactivate' name='reactivate'>&nbsp
			</form>";
		}
		?>
	</div>
	<div>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="object_id">Object ID</label>
	</div>
	<div>
		:&nbsp<?php echo $object['id'];?>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="cont">Controller</label>
	</div>
	<div>
		:&nbsp<?php echo $object['controller'];?>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="name">Name</label>
	</div>
	<div>
		:&nbsp<?php echo $object['friendly_name'];?>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="status">Status</label>
	</div>
	<div>
		:&nbsp<?php echo $object['status'];?>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="sort">Sort</label>
	</div>
	<div>
		:&nbsp<?php echo $object['sort'];?>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="module_id">Module</label>
	</div>
	<div>
		:&nbsp<?php echo $object['name'];?>
	</div>
	<div></div><div></div><div></div>
</div>
<div class="block-row-xxs-1 no-gutter pad">
	<div class="table-scrollable">
		<table class="table-striped">
			<tbody>
				<tr class='border'>
					<td><b>Operation ID</b></td>
					<td><b>Name</b></td>
					<td><b>Method</b></td>
					<td><b>Status</b></td>
				</tr>
				<?php
				if (is_array($privilege)){
					foreach($privilege as $row)
					{	
						echo "<tr class='border'>
							<td>".$row['operation_id']."</td>
							<td>".$row['name2']."</td>
							<td>".$row['method']."</td>
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
		<form action="<?php echo base_url().'C_privilege/create/'.$object['id']?>" method="post" style='display:inline'>
			<input type="submit" value="Assign Operations" name="createoperation">&nbsp
		</form>
		<form action="<?php echo base_url().'C_privilege/update/'.$object['id']?>" method="post" style='display:inline'>
			<input type="submit" value="Edit Operations" name="assignoperation">&nbsp
		</form>
	</div>
	<div></div><div></div><div></div>
</div>