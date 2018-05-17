<?php echo form_open_multipart("C_invoice/create");?>

<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		<label for="inv_id">Invoice ID</label>
	</div>
	<div>
		<input type="text" name="inv_id" value="<?php echo $invid?>" required readonly>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="inv_ref">Invoice Reference</label>
	</div>
	<div>
		<input type="text" name="inv_ref" required>
		<p>This shall be the invoice number in the bills</p>
	</div>
	<div></div><div></div><div></div>	
	<div>
		<label for="inv_date">Invoice Date</label>
	</div>
	<div>
		<input type="date" name="inv_date" required>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="due_date">Due Date</label>
	</div>
	<div>
		<input type="date" name="due_date" required>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="vendor">Vendor</label>
	</div>
	<div>
		<input type="text" name="vendor" required>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="input_attach">Attachment</label>
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