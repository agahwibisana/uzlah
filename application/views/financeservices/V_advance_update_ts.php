<form action="<?php echo base_url().'C_advance/update_ts/'.$advance_list['advance_id'].'/'.$advance_list['list_id']; ?>" method="post">
<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		<label for="desc">Description</label>
	</div>
	<div>
		<input type="text" name="desc" value="<?php echo $advance_list['description']?>" required>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="amt_req">Amount Requested</label>
	</div>
	<div>
		<input type="number" name="amt_req" value="<?php echo $advance_list['amount_requested']?>" required>
	</div>
	<div></div><div></div><div></div>
</div>
<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		<input type="submit" value="Save" name="edit_adv_li"/>
	</div>
	<div>
	</div>
	<div></div><div></div><div></div>
</div>
</form>