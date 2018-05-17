<div class="block-row-xxs-1 no-gutter pad">
	<div>
		<div class="table-scrollable">
			<table class="table-striped">
				<tr class='border'>
					<td><b>Role ID</b></br><i>(click ID to see detail)</i></td>
					<td><b>Description</b></td>
					<td><b>Status</b></td>
				</tr>
					<?php foreach($user as $row){?>
						<tr>
							<?php
							echo "<td><a href='".base_url()."C_role/read_one/".$row['id']."'>".$row['id']."</a></td>";
							echo "<td>".$row['description']."</td>";
							echo "<td>".$row['status']."</td>";
							?>
						</tr>
					<?php	}
					?>
			</table>
		</div>
	</div>
</div>