<form action="<?php echo base_url().'C_salesorder/approve/'.$sales_order['id']?>" method="post">

<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		<label for="salesorder_id">Sales Order ID</label>
	</div>
	<div>
		<input value="<?php echo $sales_order['id'];?>" type="text" name="salesorder_id" maxlength="45" readonly>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="date">Date</label>
	</div>
	<div>
		<input value="<?php echo $sales_order['date'];?>" type="date" name="date" readonly>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="note">Note</label>
	</div>
	<div>
		<textarea name="note" maxlength="256" readonly><?php echo $sales_order['note'];?></textarea>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="status">Status</label>
	</div>
	<div>
		<input class='nm' value="<?php echo $sales_order['status'];?>" type='text' name="status" maxlength="23" readonly>
	</div>
	<div></div><div></div><div></div>
	<div>
	</div>
	<div>
		<table class="table-striped">
			<tbody>
				<tr class='border'>
					<td><b>clearance level</b></td>
					<td><b>approved by</b></td>
				</tr>
				<?php
					foreach($approval as $row)
					{
						echo
						"<tr>
							<td>".$row['description']."</td>
							<td>".$row['user_id']."</td>
						</tr>";
					}
				?>
			</tbody>
		</table>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="customer_name">Ship to</label>
	</div>
	<div>
		<input value="<?php echo $sales_order['name'];?>" type="text" name="customer_name" readonly>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="user_id">created by</label>
	</div>
	<div>
		<input value="<?php echo $sales_order['user_id'];?>" type="text" name="user_id" maxlength="12" readonly>			
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="location_desc">created in</label>
	</div>
	<div>
		<input value="<?php echo $sales_order['description'];?>" type="text" name="location_desc" maxlength="45" readonly>
	</div>
	<div></div><div></div><div></div>
</div>

<div class="block-row-xxs-1 no-gutter pad">
	<div>
		<div class="table-scrollable">
			<table class="table-striped">
				<tbody>
					<tr class='border'>
						<td><b>Inventory ID</b></td>
						<td><b>Inventory Name</b></td>
						<td><b>Unit of Measurement</b></td>
						<td><b>Amount</b></td>
						<td><b>Awaiting Release</b></td>
						<td><b>Released</b></td>
						<td><b>Selling Price Each</b></td>
						<td><b>Subtotal</b></td>
						<td><b>Status</b></td>
						
					</tr>
					<?php
						foreach($sales_order_line as $row)
						{
						 echo "<tr>";
						 echo "	<td>".$row['inventory_id']."</td>
								<td>".$row['inv_name']."</td>
								<td>".$row['unit_of_measurement']."</td>
								<td>".$row['quantity_sold']."</td>
								<td>".$row['awaiting']."</td>
								<td>".$row['released']."</td>
								<td>".$row['selling_price_each']."</td>
								<td>".$row['quantity_sold']*$row['selling_price_each']."</td>
								<td>".$row['status']."</td>";
						 echo "</tr>";
						}
						$q=array_column($sales_order_line,'quantity_sold');
						$sp=array_column($sales_order_line,'selling_price_each');
						$subtotal = array();
						foreach ($sp as $key=>$sp)
						{
							$subtotal[] = $sp * $q[$key];
						}
						$gt=array_sum($subtotal);
						echo "<tr class='border'>
								<td class='white'></td>
								<td class='white'></td>
								<td class='white'></td>
								<td class='white'></td>
								<td class='white'></td>
								<td class='white'></td>
								<td class='white'><b>Grand Total</b></td>
								<td class='white'><input type='number' name='gt' value='".$gt."' readonly required></td>
								<td class='white'></td>
							</tr>";
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		<input type="submit" value="Approve"/>
	</div>
	<div>
		<span style='color:red;'> Note: you cannot change sales order once it's in approval state.</span>
	</div>
	<div></div><div></div><div></div>
</div>

</form>