<div class="block-row-xxs-1 no-gutter pad">
	<div>
		<div class="table-scrollable">
			<table class="table-striped">
				<tr class='border'>
					<td><b>Invoice ID</b></br><i>(click ID to see detail)</i></td>
					<td><b>Invoice Reference</b></td>
					<td><b>Invoice Date</b></td>
					<td><b>Vendor</b></td>
					<td><b>Due Date</b></td>
					<td><b>Paid Date</b></td>
					<td><b>Status</b></td>
					<td><b>Employee ID</b></td>
				</tr>
					<?php foreach($invoice as $row){?>
						<tr>
							<?php
							echo "<td><a href='".base_url()."C_invoice/read_one/".$row['id']."'>".$row['id']."</a></td>";
							echo "<td>".$row['inv_ref']."</td>";
							echo "<td>".$row['invoice_date']."</td>";
							echo "<td>".$row['vendor']."</td>";
							echo "<td>".$row['due_date']."</td>";
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
		<li>Accrued: you should check whether you should approve it or not.</li>
		<li>Paid To Vendor: If the documents are complete, Cashier should start the payment process.</li>
		<li>Rejected: No further action necessary.</li>
		<li>Canceled: No further action necessary.</li>
	</ul>
</div>