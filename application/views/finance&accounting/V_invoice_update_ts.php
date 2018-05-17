<form action="<?php echo base_url().'C_invoice/update_ts/'.$invoice_list['invoice_id'].'/'.$invoice_list['list_id']; ?>" method="post">
<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		<label for="desc">Description</label>
	</div>
	<div>
		<input type="text" name="desc" value="<?php echo $invoice_list['description']?>" required>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="amt_inv">Amount Invoiced</label>
	</div>
	<div>
		<input type="number" name="amt_inv" value="<?php echo $invoice_list['amount_invoiced']?>" required>
	</div>
	<div></div><div></div><div></div>
</div>
<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		<input type="submit" value="Save" name="edit_inv_li"/>
	</div>
	<div>
	</div>
	<div></div><div></div><div></div>
</div>
</form>