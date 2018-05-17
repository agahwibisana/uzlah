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
				<b>Employee ID</b>
			</div>
			<div class="col-xxs-12 col-m-8">
				<?php echo $rmb_header['employee_id'];?>
			</div>
			<div class="col-xxs-12 col-m-4">
				<b>Status</b>
			</div>
			<div class="col-xxs-12 col-m-8">
				<?php echo $rmb_header['status'];?>
			</div>
			<div class="col-xxs-12 col-m-4">
				<b>Type</b>
			</div>
			<div class="col-xxs-12 col-m-8">
				<?php echo $rmb_header['type'];?>
			</div>			
		</div>
	</div>
	<div class="col-xxs-12 col-m-4">
		<div class="row no-gutter">
			<div class="col-xxs-12 col-m-4">
				<b>Request Date</b>
			</div>
			<div class="col-xxs-12 col-m-8">
				<?php echo $rmb_header['request_date'];?>
			</div>
			<div class="col-xxs-12 col-m-4">
				<b>Attachment</b>
			</div>
			<div class="col-xxs-12 col-m-8">
				<?php echo $rmb_header['attachment'];?>
				<?php if($rmb_header['attachment']!=="" )
					{
						echo
						"<form action='".base_url()."C_reimbursement/download/".$rmb_header['id']."' method='post' style='display:inline'>
						<input type='submit' value='↓' name='download_req'>&nbsp
						</form>";
					}
				?>
			</div>
			<div class="col-xxs-12 col-m-4">
				<b>Pay to</b>
			</div>
			<div class="col-xxs-12 col-m-8">
				<?php echo $rmb_header['pay_to'];?>
			</div>
		</div>
	</div>
	<div class="col-xxs-12 col-m-4">
		<div class="row no-gutter">
			<div class="col-xxs-12 col-m-4">
				<b>Paid Date</b>
			</div>
			<div class="col-xxs-12 col-m-8">
				<?php echo $rmb_header['paid_date'];?>
			</div>
			<div class="col-xxs-12 col-m-4">
				<b>Payment Proof Doc</b>
			</div>
			<div class="col-xxs-12 col-m-8">
				<?php echo $rmb_header['pay_attachment'];?>
				<?php if($rmb_header['status']=="Paid To Employee" and $rmb_header['pay_attachment']!=="" )
					{
						echo
						"<form action='".base_url()."C_reimbursement/download/".$rmb_header['id']."' method='post' style='display:inline'>
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
		<?php if($rmb_header['status']=="Draft" and $creator_only==true){
		echo"<form action='".base_url()."C_reimbursement/update/".$rmb_header['id']."' method='post' style='display:inline'>
			<input type='submit' value='Edit' name='edit'>&nbsp
		</form>";
		}?>
		<?php if($rmb_header['status']=="Draft" and $creator_only==true)
		{
			echo
			"<form action='".base_url()."C_reimbursement/cancel/".$rmb_header['id']."' method='post' style='display:inline'>
			<input type='submit' value='Cancel' name='cancel'>&nbsp
			</form>";
		}
		?>
		<?php if(($rmb_header['status']=="Partially Approved" or $rmb_header['status']=="Draft") and $approval_but===false)
		{
			echo
			"<form action='".base_url()."C_reimbursement/approve/".$rmb_header['id']."' method='post' style='display:inline'>
			<input type='submit' value='Approve' name='approve'>&nbsp
			</form>";
		}
		?>
		<?php if(($rmb_header['status']=="Partially Approved" or $rmb_header['status']=="Draft") and $approval_but===false)
		{
			echo
			"<form action='".base_url()."C_reimbursement/approve/".$rmb_header['id']."' method='post' style='display:inline'>
			<input type='submit' value='Reject' name='reject'>&nbsp
			</form>";
		}
		?>
		<?php if($rmb_header['status']=="Fully Approved" and $paid_but===true and $upload_stat==true)
		{
			echo
			"<form action='".base_url()."C_reimbursement/pay/".$rmb_header['id']."' method='post' style='display:inline'>
			<input type='submit' value='Set To Paid' name='pay'>&nbsp
			</form>";
		}
		?>
	</div>
</div>
<div class="block-row-xxs-1 block-row-s-2 no-gutter pad">
	<div>
		<div class="table-scrollable">
			<table>
				<tbody>
					<tr>
						<td><b>Approval Status</b></td>
						<td></td>
						<td></td>					
					</tr>
					<tr class="border">
						<td><b>Title</b></td>
						<td><b>Employee Number</b></td>
						<td><b>Authorized Employee</b></td>
						<td><b>Approved</b></td>
					</tr>
					<?php foreach($approval as $row){
					echo "<tr>
						<td>".$row['description']."</td>
						<td>".$row['approver']."</td>
						<td>".$row['name']."</td>
						<td>".$row['approved']."</td>
					</tr>";
					}?>
				</tbody>
			</table>
		</div>
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
						<td><b>Amount Requested</b></td>
						<td><b>Amount Paid</b></td>
						<td></td>
					</tr>
					<?php
						foreach($rmb_line as $row)
						{
							 echo "
							 <tr>";
							 echo "	<td>".$row['list_id']."</td>
									<td>".$row['description']."</td>
									<td>£".$row['amount_requested']."</td>
									<td>£".$row['amount_actual']."</td>
									<td>".(($rmb_header['status']=='Draft' and $creator_only==true)?"<form action='".base_url()."C_reimbursement/update_ts/".$row['reimbursement_id']."/".$row['list_id']."' method='post' style='margin:0;display:inline'>
											<input type='hidden' value='".$row['list_id']."' name='list_id_edit".$row['list_id']."'>
											<input type='submit' value='e' name='edit_rmb_li'>
										</form>"
									:'').(($rmb_header['status']=='Draft' and $creator_only==true)?"<form action='".base_url()."C_reimbursement/delete_ts/".$row['reimbursement_id']."/".$row['list_id']."' method='post' style='margin:0;display:inline'>
											<input type='hidden' value='".$row['list_id']."' name='list_id_del".$row['list_id']."'>
											<input type='submit' value='-' name='del_rmb_li' style='background-color:red'>
										</form>"
									:'')."</td>";
							 echo "</tr>";
						}
						if($rmb_header['status']=='Draft' and $creator_only==true)
						{
							 echo "
								<form action='".base_url()."C_reimbursement/create_ts/".$rmb_header['id']."' method='post' style='margin:0'>
									<tr>
									<td class='white'></td>
									<td class='white'><input type='text' name='add_desc' required></td>
									<td class='white'><input type='number' name='add_amt' required></td>
									<td class='white'></td>
									<td class='white'><input type='submit' value='+' name='add_rmb_li' style='margin:0;background-color:green'></td>";
							 echo "</tr></form>";
						}
						$aq=array_column($rmb_line,'amount_requested');
						$aa=array_column($rmb_line,'amount_actual');
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