<form action="<?php echo base_url().'C_object/create'; ?>" method="post">

<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		<label for="id">Object ID</label>
	</div>
	<div>
		<input type="text" name="id" maxlength="12" value="<?php echo $object_id?>" readonly>
		<p><i>Object ID cannot be changed while assigned to privileges.</i></p>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="cont">Controller</label>
	</div>
	<div>
		<input type="text" name="cont" required>
		<p><i>Controller starts with 'C_'.</i></p>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="name">Name</label>
	</div>
	<div>
		<input type="text" name="name" required>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="sort">Sort</label>
	</div>
	<div>
		<input type="text" name="sort" value="<?php echo $sort?>" readonly>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="module_id">Module ID</label>
	</div>
	<div>
		<select required name='module_id'>
				<?php foreach($module as $row)
				{echo
				"<option value=".$row['id'].">".$row['name']."</option>";
				}?>
		</select>
	</div>
	<div></div><div></div><div></div>
</div>
<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		<input type="submit" value="Submit"/>
	</div>
	<div>
	</div>
	<div></div><div></div><div></div>
</div>
</form>