<form action="<?php echo base_url().'C_salesorder/create'; ?>" method="post">

<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		<label for="salesorder_id">Sales Order ID</label>
	</div>
	<div>
		<input type="text" name="salesorder_id" maxlength="45" required>
		<p><i>be careful! you cannot adjust sales order ID after the data has been created.</i></p>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="date">Date</label>
	</div>
	<div>
		<input type="date" name="date" required>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="note">Note</label>
	</div>
	<div>
		<textarea name="note" maxlength="256" required></textarea>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="customer_id">Customer ID</label>
	</div>
	<div>
		<select required name="customer_id">
			<?php
				foreach($customer as $row)
				{
					echo "<option value='".$row['id']."'>".$row['id']." - ".$row['name']."</option>";
				}
			?>
		</select>
		<p><i>If you can't find the customer data, consider adding it via Menu.</i></p>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="user_id">User ID</label>
	</div>
	<div>
		<input type="text" name="user_id" maxlength="12" value="<?php echo $this->session->userdata('username');?>" readonly required>
	</div>
	<div></div><div></div><div></div>
	<div>
		<label for="location_id">Location ID</label>
	</div>
	<div>
		<select required name="location_id">
			<?php
				foreach($location as $row)
				{
					echo "<option value='".$row['location_id']."'>".$row['location_id']." - ".$row['description']."</option>";
				}
			?>
		</select>
		<p><i>This will be the location where the goods come from.</i></p>
	</div>
	<div></div><div></div><div></div>
</div>

<div class="block-row-xxs-1 no-gutter pad">
	<div>
		<div class="table-scrollable">
			<table class="table-striped">
				<tbody id="ajax-parent">
					<tr id="noup" class='border'>
						<td><b>Inventory ID</b></td>
						<td><b>Amount</b></td>
						<td><b>Selling Price Each</b></td>
						<td><b>Unit of Measurement</b></td>
						<td></td>
					</tr>
					<tr id="insertanchor" class='border'>
						<td><a href="#noup" id="addnewline">add</a></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="block-row-xxs-1 block-row-s-5 no-gutter pad">
	<div>
		<input type="submit" value="Submit"/>
	</div>
	<div>
	</div>
	<div></div><div></div><div></div>
</div>

</form>

<script>
	document.getElementById("addnewline").addEventListener("click",newrow);
	var counter = 0;
	function newrow()
	{
		var parent = document.getElementById("ajax-parent");
		var ins = document.getElementById("insertanchor");
		var tr = document.createElement("tr");
		parent.insertBefore(tr,ins);
		tr.id='tr'+(++counter);
		trid = tr.id;
		tr.innerHTML="<td><select required name='inventory_id[]'>"+
							/*for each starts here*/
							"<?php foreach($inventory_stock as $row){ echo "<option value='".$row['id']."'>".$row['id']." - ".$row['name'].". ".$row['description'].". current stock: ".$row['sum_total']."</option>";}?>"+
						   "</select></td>"+
					 "<td><input type='number' name='amount[]' required></td>"+
					 "<td><input type='number' name='sellpriceeach[]' required></td>"+
					 "<td><input type='text' name='uom[]' required></td>"+
					 "<td><a href='#noup' id='del"+trid+"'>remove</a></td>";
		document.getElementById("del"+trid).addEventListener("click",function(){$(this).closest('tr').remove();})
	}
</script>