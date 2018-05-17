<div class="block-row-xxs-1 no-gutter pad">
	<div>
		<div class="table-scrollable">
			<table class="table-striped">
				<tr class='border'>
					<td><b>Operation ID</b></br><i>(click ID to see detail)</i></td>
					<td><b>Method</b></td>
					<td><b>Name</b></td>
					<td><b>Status</b></td>
					<td><b>Sort</b></td>
				</tr>
					<?php foreach($operation as $row){?>
						<tr>
							<?php
							echo "<td><a href='".base_url()."C_operation/read_one/".$row['id']."'>".$row['id']."</a></td>";
							echo "<td>".$row['method']."</td>";
							echo "<td>".$row['friendly_name']."</td>";
							echo "<td>".$row['status']."</td>";
							echo "<td>".$row['sort']."</td>";
							?>
						</tr>
					<?php	}
					?>
			</table>
		</div>
	</div>
</div>