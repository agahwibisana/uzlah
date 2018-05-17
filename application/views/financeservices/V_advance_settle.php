<?php echo form_open_multipart("C_advance/settle/".$advance_list[0]['advance_id']);?>
<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		<label for="list_attach">Attachment (pdf only, max 2048 KB)</label>
	</div>
	<div>
		<input type="hidden" name="hiddenid" value="xx"><!--helper aja, ga ada isi data ini kemana2!-->
		<input type="file" name="file" required>
	</div>
	<div></div><div></div><div></div>
</div>
<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		<input type="submit" value="Save" name="settle_attach"/>
	</div>
	<div>
	</div>
	<div></div><div></div><div></div>
</div>
</form>
<form action="<?php echo base_url().'C_advance/settle/'.$advance_list[0]['advance_id']; ?>" method="post">
<div class="block-row-xxs-1 no-gutter pad">
	<div>
		<div class="table-scrollable">
			<table class="table-striped">
				<tbody>
					<tr class='border'>
						<td><b>Description</b></td>
						<td><b>Paid Amount</b></td>
						<td><b>Settlement Amount</b></td>
					</tr>
					<?php if (is_array($advance_list)){foreach($advance_list as $row)
					{
						echo "<tr class='border'>
							<td><input type='text' name='desc[]' value='".$row['description']."' readonly></td>
							<td><input type='number' name='paid_amt[]' value='".$row['amount_committed']."' readonly></td>
							<td><input type='number' name='settle_amt[]' value='".$row['amount_actual']."'>
								<input type='hidden' name='adv_id[]' value='".$row['advance_id']."'>
								<input type='hidden' name='list_id[]' value='".$row['list_id']."'>
							</td>
						</tr>";
					}}?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php if(is_array($advance_list))
{echo
"<div class='block-row-xxs-1 block-row-s-5 no-gutter pad'>
	<div>
		<input type='submit' value='Settle' name='settle'/>
	</div>
	<div>
	</div>
	<div></div><div></div><div></div>
</div>";}?>
</form>