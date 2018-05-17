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
				<?php echo $adv_header['employee_id'];?>
			</div>
			<div class="col-xxs-12 col-m-4">
				<b>Status</b>
			</div>
			<div class="col-xxs-12 col-m-8">
				<?php echo $adv_header['status'];?>
			</div>
			<div class="col-xxs-12 col-m-4">
				<b>Attachment</b>
			</div>
			<div class="col-xxs-12 col-m-8">
				<?php echo $adv_header['attachment'];?>
				<?php if($adv_header['attachment']!=="" )
					{
						echo
						"<form action='".base_url()."C_advance/return/".$adv_header['id']."/dl' method='post' style='display:inline'>
						<input type='submit' value='↓' name='download_attach'>&nbsp
						</form>";
					}
				?>
			</div>			
		</div>
	</div>
	<div class="col-xxs-12 col-m-4">
		<div class="row no-gutter">
			<div class="col-xxs-12 col-m-4">
				<b>Request Date</b>
			</div>
			<div class="col-xxs-12 col-m-8">
				<?php echo $adv_header['request_date'];?>
			</div>
			<div class="col-xxs-12 col-m-4">
				<b>Needed Date</b>
			</div>
			<div class="col-xxs-12 col-m-8">
				<?php echo $adv_header['usage_date'];?>
			</div>
			<div class="col-xxs-12 col-m-4">
				<b>Settlement Doc</b>
			</div>
			<div class="col-xxs-12 col-m-8">
				<?php echo $adv_header['settle_attachment'];?>
				<?php if(($adv_header['status']=="Paid To Employee" or $adv_header['status']=="Settled") and $adv_header['settle_attachment']!=="" )
					{
						echo
						"<form action='".base_url()."C_advance/return/".$adv_header['id']."/dl' method='post' style='display:inline'>
						<input type='submit' value='↓' name='download_settle'>&nbsp
						</form>";
					}
				?>
			</div>
		</div>
	</div>
	<div class="col-xxs-12 col-m-4">
		<div class="row no-gutter">
			<div class="col-xxs-12 col-m-4">
				<b>Pay to</b>
			</div>
			<div class="col-xxs-12 col-m-8">
				<?php echo $adv_header['pay_to'];?>
			</div>
			<div class="col-xxs-12 col-m-4">
				<b>Paid Date</b>
			</div>
			<div class="col-xxs-12 col-m-8">
				<?php echo $adv_header['paid_date'];?>
			</div>
			<div class="col-xxs-12 col-m-4">
				<b>Settlement Date</b>
			</div>
			<div class="col-xxs-12 col-m-8">
				<?php echo $adv_header['return_date'];?>
			</div>
		</div>
	</div>
</div>
<div class="block-row-xxs-1 no-gutter pad">
	<div>
		<?php if($adv_header['status']=="Draft" and $creator_only==true){
		echo"<form action='".base_url()."C_advance/update/".$adv_header['id']."' method='post' style='display:inline'>
			<input type='submit' value='Edit' name='edit'>&nbsp
		</form>";
		}?>
		<?php if($adv_header['status']=="Draft" and $creator_only==true)
		{
			echo
			"<form action='".base_url()."C_advance/cancel/".$adv_header['id']."' method='post' style='display:inline'>
			<input type='submit' value='Cancel' name='cancel'>&nbsp
			</form>";
		}
		?>
		<?php if($approval_but===false)
		{
			echo
			"<form action='".base_url()."C_advance/approve/".$adv_header['id']."' method='post' style='display:inline'>
			<input type='submit' value='Approve' name='approve'>&nbsp
			</form>";
		}
		?>
		<?php if($approval_but===false)
		{
			echo
			"<form action='".base_url()."C_advance/approve/".$adv_header['id']."' method='post' style='display:inline'>
			<input type='submit' value='Reject' name='reject'>&nbsp
			</form>";
		}
		?>
		<?php if($adv_header['status']=="Fully Approved" and $paid_but===true)
		{
			echo
			"<form action='".base_url()."C_advance/pay/".$adv_header['id']."' method='post' style='display:inline'>
			<input type='submit' value='Set To Paid' name='pay'>&nbsp
			</form>";
		}
		?>
		<?php if($adv_header['status']=="Paid To Employee" and $paid_but===true and $upload_stat==true)
		{
			echo
			"<form action='".base_url()."C_advance/settle/".$adv_header['id']."' method='post' style='display:inline'>
			<input type='submit' value='Settle' name='settle'>&nbsp
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
						<td><b>Amount Returned</b></td>
						<td><b>Attachment</b></td>
						<td></td>
					</tr>
					<?php
						foreach($adv_line as $row)
						{
							 echo "
							 <tr>";
							 echo "	<td>".$row['list_id']."</td>
									<td>".$row['description']."</td>
									<td>£".$row['amount_requested']."</td>
									<td>£".$row['amount_committed']."</td>
									<td>£".$row['amount_actual']."</td>
									<td>".$row['list_attachment']."</td>
									<td>".(($adv_header['status']=='Draft' and $creator_only==true)?"<form action='".base_url()."C_advance/update_ts/".$row['advance_id']."/".$row['list_id']."' method='post' style='margin:0;display:inline'>
											<input type='hidden' value='".$row['list_id']."' name='list_id_edit".$row['list_id']."'>
											<input type='submit' value='e' name='edit_adv_li'>
										</form>"
									:'').(($adv_header['status']=='Draft' and $creator_only==true)?"<form action='".base_url()."C_advance/delete_ts/".$row['advance_id']."/".$row['list_id']."' method='post' style='margin:0;display:inline'>
											<input type='hidden' value='".$row['list_id']."' name='list_id_del".$row['list_id']."'>
											<input type='submit' value='-' name='del_adv_li' style='background-color:red'>
										</form>"
									:'').(($adv_header['status']=='Paid To Employee' and $creator_only==true)?"<form action='".base_url()."C_advance/return/".$row['advance_id']."/".$row['list_id']."' method='post' style='margin:0;display:inline'>
											<input type='hidden' value='".$row['list_id']."' name='list_id_ret".$row['list_id']."'>
											<input type='submit' value='↑' name='return'>
										</form>"
									:'').((($adv_header['status']=='Paid To Employee' or $adv_header['status']=='Settled') and $row['list_attachment']!=='')?"<form action='".base_url()."C_advance/return/".$row['advance_id']."/".$row['list_id']."' method='post' style='margin:0;display:inline'>
											<input type='hidden' value='".$row['list_id']."' name='list_id_dl".$row['list_id']."'>
											<input type='submit' value='↓' name='download'>
										</form>"
									:'')."</td>";
							 echo "</tr>";
						}
						if($adv_header['status']=='Draft' and $creator_only==true)
						{
							 echo "
								<form action='".base_url()."C_advance/create_ts/".$adv_header['id']."' method='post' style='margin:0'>
									<tr>
									<td class='white'></td>
									<td class='white'><input type='text' name='add_desc' required></td>
									<td class='white'><input type='number' name='add_amt' required></td>
									<td class='white'></td>
									<td class='white'></td>
									<td class='white'></td>
									<td class='white'><input type='submit' value='+' name='add_adv_li' style='margin:0;background-color:green'></td>";
							 echo "</tr></form>";
						}
						$aq=array_column($adv_line,'amount_requested');
						$ac=array_column($adv_line,'amount_committed');
						$aa=array_column($adv_line,'amount_actual');
						$subtotalreq = array();
						$subtotalcom = array();
						$subtotalact = array();
						foreach ($aq as $key=>$aq)
						{
							$subtotalreq[] = $aq;
						}
						foreach ($ac as $key=>$ac)
						{
							$subtotalcom[] = $ac;
						}
						foreach ($aa as $key=>$aa)
						{
							$subtotalact[] = $aa;
						}
						$gt_req=array_sum($subtotalreq);
						$gt_com=array_sum($subtotalcom);
						$gt_act=array_sum($subtotalact);
						echo "<tr class='border'>
								<td class='white'></td>
								<td class='white'><b>Grand Total</b></td>
								<td class='white'><b>£".$gt_req."</b></td>
								<td class='white'><b>£".$gt_com."</b></td>
								<td class='white'><b>£".$gt_act."</b></td>
								<td class='white'></td>
								<td class='white'></td>
							</tr>";
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>