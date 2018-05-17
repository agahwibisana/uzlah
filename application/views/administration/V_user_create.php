<form action="<?php echo base_url().'C_user/create'; ?>" method="post">

<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		<label for="user_id">User ID</label>
	</div>
	<div>
		<input type="text" name="user_id" maxlength="12" required>
		<p><i>User ID cannot be changed while assigned to roles.</i></p>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="pwd">Password</label>
	</div>
	<div>
		<input type="password" name="pwd" required>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="name">Name</label>
	</div>
	<div>
		<input type="text" name="name" required>
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