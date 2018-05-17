<form action="<?php echo base_url().'C_object/update/'.$object['id']; ?>" method="post">

<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		<label for="cont">Controller</label>
	</div>
	<div>
		<input type="text" name="cont" value="<?php echo $object['controller']?>" required>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="name">Name</label>
	</div>
	<div>
		<input type="text" name="name" value="<?php echo $object['friendly_name']?>" required>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="sort">Sort</label>
	</div>
	<div>
		<input type="number" name="sort" value="<?php echo $object['sort']?>" required>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="module_id">Module</label>
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
		<input type="submit" value="Save" name="edit"/>
	</div>
	<div>
	</div>
	<div></div><div></div><div></div>
</div>
</form>