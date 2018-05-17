<?php echo form_open_multipart('C_invoice/update/'.$invoice['id']);?>

<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		<label for="invoice_date">Invoice Date</label>
	</div>
	<div>
		<input type="text" name="invoice_date" value="<?php echo $invoice['invoice_date']?>" required>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="due_date">Due Date</label>
	</div>
	<div>
		<input type="text" name="due_date" value="<?php echo $invoice['invoice_date']?>" required>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="input_attach">Attachment</label>
	</div>
	<div>
		<input type="file" name="input_attach" required>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="vendor">Vendor</label>
	</div>
	<div>
		<input type="text" name="vendor" value="<?php echo $invoice['vendor']?>" required>
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