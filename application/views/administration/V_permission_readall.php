<div class="block-row-xxs-1 no-gutter pad">
	<div>
		<div class="table-scrollable">
			<table class="table-striped">
				<tr class='border'>
					<td><b>Role ID</b></td>
					<td><b>Description</b></td>
					<td><b>Privilege ID</b></td>
					<td><b>Description</b></td>
					<td><b>Status</b></td>
				</tr>
					<?php foreach($permission as $row){?>
						<tr>
							<?php
							echo "<td><a href='".base_url()."C_role/read_one/".$row['role_id']."'>".$row['role_id']."</a></td>";
							echo "<td>".$row['description']."</td>";
							echo "<td><a href='".base_url()."C_object/read_one/".$row['object_id']."'>".$row['object_id']." ".$row['operation_id']."</a></td>";
							echo "<td>".$row['name1']." ".$row['name2']."</td>";
							echo "<td>".$row['status']."</td>";
							?>
						</tr>
					<?php	}
					?>
			</table>
		</div>
	</div>
</div>