<form action="<?php echo base_url().'C_role/update/'.$role['id']; ?>" method="post">

<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		<label for="desc">Description</label>
	</div>
	<div>
		<input type="text" name="desc" value="<?php echo $role['description']?>" required>
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