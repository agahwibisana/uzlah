<form action="<?php echo base_url().'C_module/create'; ?>" method="post">

<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		<label for="id">Module ID</label>
	</div>
	<div>
		<input type="text" name="id" maxlength="12" value="<?php echo $module_id?>" readonly>
		<p><i>Module ID cannot be changed while assigned to objects.</i></p>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="name">Name</label>
	</div>
	<div>
		<input type="text" name="name" required>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="sort">Sort</label>
	</div>
	<div>
		<input type="text" name="sort" value="<?php echo $sort?>" readonly>
	</div>
	<div></div><div></div><div></div>
</div>
<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		<input type="submit" value="Submit"/>
	</div>
	<div>
	</div>
	<div></div><div></div><div></div>
</div>
</form>