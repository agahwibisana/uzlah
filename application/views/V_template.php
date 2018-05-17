<html>
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="description" content="Your Page Description." />
		<title><?php if(! is_null($title)){ echo $title;}?></title>
		<link href="<?php echo base_url()?>css/responsive.min.css" rel="stylesheet" />
		<link href="<?php echo base_url()?>css/main.css" rel="stylesheet" />
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:400,400i,700">
	</head>
	<body>
		<div class="block-row-xxs-1 no-gutter pad embossed" style='background-color:black'>
			<div>
				<h2 class='nm' style='color:white'>HAUTE ELAN X HIJUP</h2>
			</div>
			
			<?php
				if($this->session->userdata('username'))
				{
					echo 
					"
					<div>
						<p class='nm' style='color:white'>Welcome, ".$this->session->userdata('friendly_username').". <a style='color:white' href='".base_url()."C_home/logout_process'>Logout</a> here.</p>
					</div>";
				}
				else
				{
					echo
					"<div>
					</div>";
				}
			?>
			
			<hr class='nm' style='background-color:black;'/>
			
			<div class="accordion-group">
				<div class="accordion">
					<div class="accordion-head">
						<a style='color:white' data-dropdown-target="#cllps1" href="#" data-dropdown-parent=".accordion-group">
							&#9776;Menu
						</a>
					</div>
					<div class="accordion-body collapse" id="cllps1">
					<?php 
						if($this->session->userdata('username'))
						{
							if($this->session->userdata('menu'))
							{
								$i=2;
								echo
								"<div class='accordion-group2'>";
								foreach(unserialize(($this->session->userdata('module'))) as $row)
								{
									echo 
									"<div class='accordion'>
										<div class='accordion-head'>
											<a style='color:white' data-dropdown-target='#cllps".$i."' href='#' data-dropdown-parent='.accordion-group2'>&nbsp;&nbsp;".$row['friendly_mod']."</a>
										</div>
										<div class='accordion-body collapse' id='cllps".$i."'>
											<div class='accordion-group3'>";
									foreach(unserialize(($this->session->userdata('object'))) as $row2)
									{
										if($row2['friendly_mod'] === $row['friendly_mod'])
										{
											echo
											"	<div class='accordion'>
													<div class='accordion-head'>
														<a style='color:white' data-dropdown-target='#cllps".($i+1)."' href='#' data-dropdown-parent='.accordion-group3'>&nbsp;&nbsp;&nbsp;".$row2['friendly_cont']."</a>
													</div>
													<div class='accordion-body collapse' id='cllps".($i+1)."'>";
											foreach(unserialize(($this->session->userdata('obop'))) as $row3)
											{
												if($row3['friendly_cont'] === $row2['friendly_cont'])
												{									
													echo
														"<a style='color:white' href='".base_url().$row3['controller']."/".$row3['method']."'>&nbsp;&nbsp;&nbsp;&nbsp;".$row3['friendly_meth']." ".$row3['friendly_cont']."</a><br/>";																	
														"<a style='color:white' href='".base_url().$row3['controller']."/".$row3['method']."'>&nbsp;&nbsp;&nbsp;&nbsp;".$row3['friendly_meth']." ".$row3['friendly_cont']."</a><br/>";																	
												}
											}
										echo
										"			</div>
												</div>";
										}
										$i++;
									}
									echo
									"		</div>
										</div>
									</div>";
									$i++;
								}
								echo
								"</div>";
							}
						}
					?>
					</div>
				</div>
			</div>
		</div>
		
		<div class="block-row-xxs-1 no-gutter pad">
			<div>
				<?php if(! is_null($message)) echo $message;?>
			</div>
		</div>
		
		<div class="block-row-xxs-1 no-gutter pad" style='background-color:darkgray;border:1px solid black'>
			<div>
				<h3 class='nm'><?php if(! is_null($title)){ echo $title;}?></h3>
			</div>
		</div>

		<?php $this->load->view($viewpage);?>

		<div class="block-row-xxs-6 no-gutter">
			<div class='h10' style='background-color:#DCB13C;'>
			</div>
			<div class='h10' style='background-color:#57BDA2;'>
			</div>
			<div class='h10' style='background-color:#2493A2;'>
			</div>
			<div class='h10' style='background-color:#304A78;'>
			</div>
			<div class='h10' style='background-color:#2C3259;'>
			</div>
			<div class='h10' style='background-color:#0A0E28;'>
			</div>
		</div>
		
		<script src="<?php echo base_url()?>css/vendor/jquery-2.1.4.min.js"></script>
		<script src="<?php echo base_url()?>css/responsive.min.js"></script>
	</body>
</html>