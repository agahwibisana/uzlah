<?php echo form_open_multipart("C_reimbursement/create");?>

<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		<label for="rmb_id">Reimbursement ID</label>
	</div>
	<div>
		<input type="text" name="rmb_id" value="<?php echo $rmbid?>" required readonly>
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
		<label for="type">Reimbursement Type</label>
	</div>
	<div>
		<select name='type' required>
		  <option value="Medical">Medical</option>
		  <option value="Business Trip">Business Trip</option>
		  <option value="Operational">Operational</option>
		</select>
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
		<label for="pay_to">Cash/Account Number</label>
	</div>
	<div>
		<input type="text" name="pay_to" maxlength="20" required>
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