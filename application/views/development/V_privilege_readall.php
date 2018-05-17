<div class="block-row-xxs-1 no-gutter pad">
	<div>
		<div class="table-scrollable">
			<table class="table-striped">
				<tr class='border'>
					<td><b>Object ID</b></td>
					<td><b>Name</b></td>
					<td><b>Controller</b></td>
					<td><b>Operation ID</b></td>
					<td><b>Name</b></td>
					<td><b>Method</b></td>
					<td><b>Status</b></td>
				</tr>
					<?php if(is_array($privilege))
					{foreach($privilege as $row){?>
						<tr>
							<?php
							echo "<td><a href='".base_url()."C_object/read_one/".$row['object_id']."'>".$row['object_id']."</a></td>";
							echo "<td>".$row['name1']."</td>";
							echo "<td>".$row['controller']."</td>";
							echo "<td><a href='".base_url()."C_operation/read_one/".$row['operation_id']."'>".$row['operation_id']."</a></td>";
							echo "<td>".$row['name2']."</td>";							
							echo "<td>".$row['method']."</td>";
							echo "<td>".$row['status']."</td>";
							?>
						</tr>
					<?php	}}
					?>
			</table>
		</div>
	</div>
</div>