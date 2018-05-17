<div class="block-row-xxs-1 no-gutter pad">
	<div>
		<div class="table-scrollable">
			<table class="table-striped">
				<tr class='border'>
					<td><b>Reimbursement ID</b></br><i>(click ID to see detail)</i></td>
					<td><b>Request Date</b></td>
					<td><b>Type</b></td>
					<td><b>Pay To</b></td>
					<td><b>Paid Date</b></td>
					<td><b>Status</b></td>
					<td><b>Employee ID</b></td>
				</tr>
					<?php foreach($reimbursement as $row){?>
						<tr>
							<?php
							echo "<td><a href='".base_url()."C_reimbursement/read_one/".$row['id']."'>".$row['id']."</a></td>";
							echo "<td>".$row['request_date']."</td>";
							echo "<td>".$row['type']."</td>";
							echo "<td>".$row['pay_to']."</td>";
							echo "<td>".$row['paid_date']."</td>";
							echo "<td>".$row['status']."</td>";
							echo "<td>".$row['employee_id']."</td>";
							?>
						</tr>
					<?php	}
					?>
			</table>
		</div>
	</div>
</div>
<div class="block-row-xxs-1 no-gutter pad">
	<ul>
		<li>Draft: you should check whether you should approve it or not.</li>
		<li>Partially Approved: you should check whether you should approve it or not.</li>
		<li>Fully Approved: Cashier should process the payment.</li>
		<li>Paid To Employee: If the documents are complete, Cashier should start the payment process.</li>
		<li>Rejected: No further action necessary.</li>
		<li>Canceled: No further action necessary.</li>
	</ul>
</div>