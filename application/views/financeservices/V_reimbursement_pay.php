<?php echo form_open_multipart("C_reimbursement/pay/".$rmb['id']);?>
<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		<label for="list_attach">Payment Document (pdf only, max 2048 KB)</label>
	</div>
	<div>
		<input type="hidden" name="hiddenid" value="xx"><!--helper aja, ga ada isi data ini kemana2!-->
		<input type="file" name="file" required>
	</div>
	<div></div><div></div><div></div>
</div>
<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		<input type="submit" value="Save" name="pay_attach"/>
	</div>
	<div>
	</div>
	<div></div><div></div><div></div>
</div>
</form>