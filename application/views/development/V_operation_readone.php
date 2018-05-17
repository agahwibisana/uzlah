<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		back to <a href="<?php echo base_url().'C_operation/read_all'?>">View All Operations</a><br/><br/>
	</div>
	<div></div><div></div><div></div><div></div>
	<div>
		<form action="<?php echo base_url().'C_operation/update/'.$operation['id']?>" method="post" style='display:inline;'>
			<input type="submit" value="Edit" name="edit">&nbsp
		</form>
	</div>
	<div>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="operation_id">Operation ID</label>
	</div>
	<div>
		:&nbsp<?php echo $operation['id'];?>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="method">Method</label>
	</div>
	<div>
		:&nbsp<?php echo $operation['method'];?>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="name">Name</label>
	</div>
	<div>
		:&nbsp<?php echo $operation['friendly_name'];?>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="status">Status</label>
	</div>
	<div>
		:&nbsp<?php echo $operation['status'];?>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="sort">Sort</label>
	</div>
	<div>
		:&nbsp<?php echo $operation['sort'];?>
	</div>
	<div></div><div></div><div></div>
</div>