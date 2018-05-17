<div class="block-row-xxs-1 no-gutter pad">
	<div>
		<div class="table-scrollable">
			<table class="table-striped">
				<tr class='border'>
					<td><b>Object ID</b></br><i>(click ID to see detail)</i></td>
					<td><b>Controller</b></td>
					<td><b>Name</b></td>
					<td><b>Status</b></td>
					<td><b>Sort</b></td>
					<td><b>Module</b></td>
				</tr>
					<?php foreach($object as $row){?>
						<tr>
							<?php
							echo "<td><a href='".base_url()."C_object/read_one/".$row['id']."'>".$row['id']."</a></td>";
							echo "<td>".$row['controller']."</td>";
							echo "<td>".$row['friendly_name']."</td>";
							echo "<td>".$row['status']."</td>";
							echo "<td>".$row['sort']."</td>";
							echo "<td><a href='".base_url()."C_module/read_one/".$row['module_id']."'>".$row['name']."</a></td>";
							?>
						</tr>
					<?php	}
					?>
			</table>
		</div>
	</div>
</div>