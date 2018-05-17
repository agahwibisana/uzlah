<form action="<?php echo base_url().'C_salesorder/delete_ts/'.$sales_order_line['sales_order_id']."/".$sales_order_line['inventory_id']; ?>" method="post">

<div class="block-row-xxs-1 no-gutter pad">
	<div>
		<div class="table-scrollable">
			<table class="table-striped">
				<tbody>
					<tr class='border'>
						<td><b>Sales Order ID</b></td>
						<td><b>Inventory ID</b></td>
						<td><b>Amount</b></td>
						<td><b>Selling Price Each</b></td>
						<td><b>Unit of Measurement</b></td>
						<td><b>Status</b></td>
						<td></td>
					</tr>
					<?php
						 echo "<tr>";
						 echo "	<td><input value='".$sales_order_line['sales_order_id']."' type='text' name='salesorder_id' readonly></td>
								<td><input value='".$sales_order_line['inventory_id']."' type='text' name='inventory_id' readonly></td>
								<td>".$sales_order_line['quantity_sold']."</td>
								<td>".$sales_order_line['selling_price_each']."</td>
								<td>".$sales_order_line['unit_of_measurement']."</td>
								<td>".$sales_order_line['status']."</td>";
						 echo "</tr>";
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		<input type="submit" value="Delete"/>
	</div>
	<div>
		<span style='color:red;'> This action cannot be undone.</span>
	</div>
	<div></div><div></div><div></div>
</div>
	
</form>