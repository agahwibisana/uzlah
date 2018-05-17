<!--
<div class="row no-gutter pad">
	<div class="column-xxs-12 no-gutter pad">
		back to <a href="<?php echo base_url().'C_advance/read_all'?>">View All Employee Advances</a>
	</div>
</div>!-->
<div class="row no-gutter pad equal-height" style='background-color:#ebebeb;border:1px solid black'>
	<div class="col-xxs-12 col-m-4">
		<div class="row no-gutter">
			<div class="col-xxs-12 col-m-4">
				<b>Invoice Date</b>
			</div>
			<div class="col-xxs-12 col-m-8">
				<?php echo $inv_header['invoice_date'];?>
			</div>
			<div class="col-xxs-12 col-m-4">
				<b>Due Date</b>
			</div>
			<div class="col-xxs-12 col-m-8">
				<?php echo $inv_header['due_date'];?>
			</div>
			<div class="col-xxs-12 col-m-4">
				<b>Status</b>
			</div>
			<div class="col-xxs-12 col-m-8">
				<?php echo $inv_header['status'];?>
			</div>			
		</div>
	</div>
	<div class="col-xxs-12 col-m-4">
		<div class="row no-gutter">
			<div class="col-xxs-12 col-m-4">
				<b>Attachment</b>
			</div>
			<div class="col-xxs-12 col-m-8">
				<?php echo $inv_header['attachment'];?>
				<?php if($inv_header['attachment']!=="" )
					{
						echo
						"<form action='".base_url()."C_invoice/download/".$inv_header['id']."' method='post' style='display:inline'>
						<input type='submit' value='↓' name='download_inv'>&nbsp
						</form>";
					}
				?>
			</div>
			<div class="col-xxs-12 col-m-4">
				<b>Vendor</b>
			</div>
			<div class="col-xxs-12 col-m-8">
				<?php echo $inv_header['vendor'];?>
			</div>	
			<div class="col-xxs-12 col-m-4">
				<b>Employee ID</b>
			</div>
			<div class="col-xxs-12 col-m-8">
				<?php echo $inv_header['employee_id'];?>
			</div>	
		</div>
	</div>
	<div class="col-xxs-12 col-m-4">
		<div class="row no-gutter">
			<div class="col-xxs-12 col-m-4">
				<b>Paid Date</b>
			</div>
			<div class="col-xxs-12 col-m-8">
				<?php echo $inv_header['paid_date'];?>
			</div>
			<div class="col-xxs-12 col-m-4">
				<b>Payment Proof Doc</b>
			</div>
			<div class="col-xxs-12 col-m-8">
				<?php echo $inv_header['pay_attachment'];?>
				<?php if($inv_header['status']=="Paid To Vendor" and $inv_header['pay_attachment']!=="" )
					{
						echo
						"<form action='".base_url()."C_invoice/download/".$inv_header['id']."' method='post' style='display:inline'>
						<input type='submit' value='↓' name='download_pay'>&nbsp
						</form>";
					}
				?>
			</div>
		</div>
	</div>
</div>
<div class="block-row-xxs-1 no-gutter pad">
	<div>
		<?php if($inv_header['status']=="Accrued" and $creator_only==true){
		echo"<form action='".base_url()."C_invoice/download/".$inv_header['id']."' method='post' style='display:inline'>
			<input type='submit' value='Lock' name='lock'>&nbsp
		</form>";
		}?>
		<?php if($inv_header['status']=="Accrued" and $creator_only==true){
		echo"<form action='".base_url()."C_invoice/update/".$inv_header['id']."' method='post' style='display:inline'>
			<input type='submit' value='Edit' name='edit'>&nbsp
		</form>";
		}?>
		<?php if($inv_header['status']=="Accrued" and $creator_only==true)
		{
			echo
			"<form action='".base_url()."C_invoice/cancel/".$inv_header['id']."' method='post' style='display:inline'>
			<input type='submit' value='Cancel' name='cancel'>&nbsp
			</form>";
		}
		?>
		<?php if($inv_header['status']=="Locked" and $paid_but===true and $upload_stat==true)
		{
			echo
			"<form action='".base_url()."C_invoice/pay/".$inv_header['id']."' method='post' style='display:inline'>
			<input type='submit' value='Set To Paid' name='pay'>&nbsp
			</form>";
		}
		?>
	</div>
</div>
<div class="block-row-xxs-1 no-gutter pad">
	<div>
		<p class='nm'>Hint: It's a good idea to separate tax, administration cost, and actual billed amount.</p>
	</div>
</div>
<div class="block-row-xxs-1 no-gutter pad">
	<div>
		<div class="table-scrollable">
			<table>
				<tbody>
					<tr class='border'>
						<td><b>Number</b></td>
						<td><b>Description</b></td>
						<td><b>Amount Invoiced</b></td>
						<td><b>Amount Paid</b></td>
						<td></td>
					</tr>
					<?php
						foreach($inv_line as $row)
						{
							 echo "
							 <tr>";
							 echo "	<td>".$row['list_id']."</td>
									<td>".$row['description']."</td>
									<td>£".$row['amount_invoiced']."</td>
									<td>£".$row['amount_paid']."</td>
									<td>".(($inv_header['status']=='Accrued' and $creator_only==true)?"<form action='".base_url()."C_invoice/update_ts/".$row['invoice_id']."/".$row['list_id']."' method='post' style='margin:0;display:inline'>
											<input type='hidden' value='".$row['list_id']."' name='list_id_edit".$row['list_id']."'>
											<input type='submit' value='e' name='edit_inv_li'>
										</form>"
									:'').(($inv_header['status']=='Accrued' and $creator_only==true)?"<form action='".base_url()."C_invoice/delete_ts/".$row['invoice_id']."/".$row['list_id']."' method='post' style='margin:0;display:inline'>
											<input type='hidden' value='".$row['list_id']."' name='list_id_del".$row['list_id']."'>
											<input type='submit' value='-' name='del_inv_li' style='background-color:red'>
										</form>"
									:'')."</td>";
							 echo "</tr>";
						}
						if($inv_header['status']=='Accrued' and $creator_only==true)
						{
							 echo "
								<form action='".base_url()."C_invoice/create_ts/".$inv_header['id']."' method='post' style='margin:0'>
									<tr>
									<td class='white'></td>
									<td class='white'><input type='text' name='add_desc' required></td>
									<td class='white'><input type='number' name='add_amt' required></td>
									<td class='white'></td>
									<td class='white'><input type='submit' value='+' name='add_inv_li' style='margin:0;background-color:green'></td>";
							 echo "</tr></form>";
						}
						$aq=array_column($inv_line,'amount_invoiced');
						$aa=array_column($inv_line,'amount_paid');
						$subtotalreq = array();
						$subtotalact = array();
						foreach ($aq as $key=>$aq)
						{
							$subtotalreq[] = $aq;
						}
						foreach ($aa as $key=>$aa)
						{
							$subtotalact[] = $aa;
						}
						$gt_req=array_sum($subtotalreq);
						$gt_act=array_sum($subtotalact);
						echo "<tr class='border'>
								<td class='white'></td>
								<td class='white'><b>Grand Total</b></td>
								<td class='white'><b>£".$gt_req."</b></td>
								<td class='white'><b>£".$gt_act."</b></td>
								<td class='white'></td>
							</tr>";
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>