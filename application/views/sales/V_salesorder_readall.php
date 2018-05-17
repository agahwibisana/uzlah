<div class="block-row-xxs-1 block-row-s-4 no-gutter pad">
	<div>
		<form action='' method='get'>
			<div class='input-group'>
				<input type='text' name='status' placeholder='filter by status...' required maxlength='23'>
				<span class='input-group-addon'><input type='submit' value='filter'></span>
			</div>
		</form>
	</div>
	<div></div><div></div><div></div>
</div>

<div class="block-row-xxs-1 no-gutter pad">
	<div>
		<div class="table-scrollable">
			<table class="table-striped">
				<tr class='border'>
					<td><b>Sales Order ID</b></br><i>(click ID to see detail)</i></td>
					<td><b>Amount</b></td>
					<td><b>Date</b></td>
					<td><b>Note</b></td>
					<td><b>Status</b></td>
					<td><b>Customer Name</b></td>
					<td><b>created by</b></td>
					<td><b>created in</b></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
					<?php foreach($sales_order as $row){?>
						<tr>
							<?php echo "<td><a href='".base_url()."C_salesorder/read_one/".$row['id']."'>".$row['id']."</a></td>";
							echo "<td>".$row['gt']."</td>";
							echo "<td>".$row['date']."</td>";
							echo "<td>".$row['note']."</td>";
							echo "<td>".$row['status']."</td>";
							echo "<td>".$row['name']."</td>";
							echo "<td>".$row['user_id']."</td>";
							echo "<td>".$row['description']."</td>";
							echo "<td><a href='".base_url()."C_salesorder/update/".$row['id']."'>update</a></td>";
							echo "<td><a href='".base_url()."C_salesorder/delete_md/".$row['id']."'>cancel</a></td>";
							echo "<td><a href='".base_url()."C_salesorder/approve/".$row['id']."'>approve</a></td>";
							?>
						</tr>
					<?php	}
					?>
			</table>
		</div>
	</div>
</div>