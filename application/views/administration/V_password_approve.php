<form action="<?php echo base_url().'C_password/approve/'?>" method="post">

<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		<label for="id">User ID</label>
	</div>
	<div>
		<input type="text" name="id" maxlength="45" required>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="new_pwd">New Password</label>
	</div>
	<div>
		<input type="password" name="new_pwd" maxlength="45" required>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="new_pwd_cnf">Confirm New Password</label>
	</div>
	<div>
		<input type="password" name="new_pwd_cnf" maxlength="45" required>
	</div>
	<div></div><div></div><div></div>
</div>

<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		<input type="submit" value="Reset"/>
	</div>
	<div>
	</div>
	<div></div><div></div><div></div>
</div>

</form>