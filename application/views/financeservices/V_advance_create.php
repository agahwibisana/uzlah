<?php echo form_open_multipart("C_advance/create");?>

<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		<label for="adv_id">Employee Advance ID</label>
	</div>
	<div>
		<input type="text" name="adv_id" value="<?php echo $advid?>" required readonly>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="req_date">Request Date</label>
	</div>
	<div>
		<input type="date" name="req_date" value="<?php echo date('Y-m-d')?>" required readonly>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="need_date">Needed Date</label>
	</div>
	<div>
		<input type="date" name="need_date" maxlength="10" required>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="pay_to">Cash/Account Number</label>
	</div>
	<div>
		<input type="text" name="pay_to" maxlength="20" required>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="input_attach">Attach</label>
	</div>
	<div>
		<input type="file" name="input_attach" required>
	</div>
	<div></div><div></div><div></div>
</div>
<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		<input type="submit" value="Create"/>
	</div>
	<div>
	</div>
	<div></div><div></div><div></div>
</div>

</form>