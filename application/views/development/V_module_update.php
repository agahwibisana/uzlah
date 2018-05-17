<form action="<?php echo base_url().'C_module/update/'.$module['id']; ?>" method="post">

<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		<label for="name">Name</label>
	</div>
	<div>
		<input type="text" name="name" value="<?php echo $module['name']?>" required>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="sort">Sort</label>
	</div>
	<div>
		<input type="number" name="sort" value="<?php echo $module['sort']?>" required>
	</div>
	<div></div><div></div><div></div>
</div>
<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		<input type="submit" value="Save" name="edit"/>
	</div>
	<div>
	</div>
	<div></div><div></div><div></div>
</div>
</form>