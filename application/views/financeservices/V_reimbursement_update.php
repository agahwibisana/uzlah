<?php echo form_open_multipart('C_reimbursement/update/'.$reimbursement['id']);?>

<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		<label for="input_attach">Attachment</label>
	</div>
	<div>
		<input type="file" name="input_attach" required>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="pay_to">Pay To</label>
	</div>
	<div>
		<input type="text" name="pay_to" value="<?php echo $reimbursement['pay_to']?>" required>
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