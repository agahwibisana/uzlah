<?php echo form_open_multipart('C_advance/update/'.$advance['id']);?>

<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		<label for="need_date">Needed Date</label>
	</div>
	<div>
		<input type="date" name="need_date" value="<?php echo $advance['usage_date']?>" required>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="pay_to">Pay To</label>
	</div>
	<div>
		<input type="text" name="pay_to" value="<?php echo $advance['pay_to']?>" required>
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
		<input type="submit" value="Save" name="edit"/>
	</div>
	<div>
	</div>
	<div></div><div></div><div></div>
</div>
</form>