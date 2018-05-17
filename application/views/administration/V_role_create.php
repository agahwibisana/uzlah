<form action="<?php echo base_url().'C_role/create'; ?>" method="post">

<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		<label for="id">Role ID</label>
	</div>
	<div>
		<input type="text" name="id" maxlength="12" value="<?php echo $role_id?>" readonly>
		<p><i>Role ID cannot be changed while assigned to roles.</i></p>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="desc">Description</label>
	</div>
	<div>
		<input type="text" name="desc" required>
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