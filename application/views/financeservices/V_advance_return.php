<?php echo form_open_multipart("C_advance/return/".$advance_list['advance_id']."/".$advance_list['list_id']."");?>
<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		<label for="list_attach">Attachment (pdf only, max 2048 KB)</label>
	</div>
	<div>
		<input type="hidden" name="hiddenid" value="xx"><!--helper aja, ga ada isi data ini kemana2!-->
		<input type="file" name="file">
	</div>
	<div></div><div></div><div></div>
</div>
<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		<input type="submit" value="Save" name="return"/>
	</div>
	<div>
	</div>
	<div></div><div></div><div></div>
</div>
</form>