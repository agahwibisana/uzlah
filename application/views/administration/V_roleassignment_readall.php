<div class="block-row-xxs-1 no-gutter pad">
	<div>
		<div class="table-scrollable">
			<table class="table-striped">
				<tr class='border'>
					<td><b>User ID</b></td>
					<td><b>Name</b></td>
					<td><b>Role ID</b></td>
					<td><b>Description</b></td>
					<td><b>Status</b></td>
				</tr>
					<?php foreach($user as $row){?>
						<tr>
							<?php
							echo "<td><a href='".base_url()."C_user/read_one/".$row['user_id']."'>".$row['user_id']."</a></td>";
							echo "<td>".$row['friendly_name']."</td>";
							echo "<td><a href='".base_url()."C_role/read_one/".$row['role_id']."'>".$row['role_id']."</a></td>";
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