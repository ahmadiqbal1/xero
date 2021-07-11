<?php if($compare_report == 0) { ?>
	<?php 	
		if($load_css_js==1) 
		{
			$include_css_js=include("application/modules/sitedoctor/views/report_css_js.php");
			echo "<!DOCTYPE html><html><head>".$include_css_js."<meta charset='UTF-8'></head><body>";
		}
		else
		{
			echo "<title>".$this->config->item("product_name")." | ".$this->lang->line("website health check")." : ".$page_title."</title>";
			echo "<meta charset='UTF-8'>";
			$this->load->view("css_include.php");
			$this->load->view("js_include.php");
		}
		
		$direct_download="1";
		echo "<input type='hidden' value='".$site_info[0]["id"]."' id='hidden_id'/>";		
	?>

	<?php 
		$path = 'assets/img/logo.png';
		$type = pathinfo($path, PATHINFO_EXTENSION);
		$data = file_get_contents($path);
		if($load_css_js==1) {

			$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
			echo "<a style='text-decoration:none;' href='".base_url()."'><img style='margin-left:242px;max-width:200px;' src='".$base64."' alt='".$this->config->item("institute_address1")."'></a</p>>";
			echo "<p align='center'>Powered by <a style='text-align:center;text-decoration:none;' href='".base_url()."'>".base_url()."</a>"." (".$this->config->item("institute_address1").")";		
			echo '<br><br>';
		}
		else {

			$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
			echo "<br><br><a href='".base_url()."'><img style='max-width:200px;' class='center-block' src='".$base64."' alt='".$this->config->item("institute_address1")."'></a></p>";			
			echo "<p align='center'>Powered by <a style='text-align:center;text-decoration:none;' href='".base_url()."'>".base_url()."</a>"." (".$this->config->item("institute_address1").")"."</p>";
		}
	?>


	<?php 
		$headline=$this->lang->line("health report");
		$catch_line=$this->lang->line('follow recommendations of this health report to keep your site healthy');
		$searched_at=$this->lang->line('examined at');
	?>

	<h4 id="" align="center"><?php echo $headline; ?> : <a style='text-decoration:none;font-size:14px;' href="<?php echo $site_info[0]["domain_name"]; ?>" target="_BLANK"><?php echo $site_info[0]["domain_name"]; ?></a></h4>
	<p align="center"><?php echo "<b>".$searched_at."</b> : ".date("y-m-d H:i:s", strtotime($site_info[0]["searched_at"])); ?></p>
	<p align="center"><?php echo $catch_line; ?></p>

	<?php if($load_css_js!=1) {?>
		<div class="container-fluid">
			<div class="row">
				<div class="col-12 share-container text-center" style="margin-bottom:50px;">
					<?php include("application/modules/sitedoctor/views/share_button.php");?>		
				</div>
			</div>
		</div>
		
		<div class="container">
			<div class="row" style="padding: 0 15px;">
				<div class="col-12 col-md-4 share-sibing text-center">
					<a class="btn btn-info rounded float-left" href="<?php echo site_url("sitedoctor/add_domain/");?>">  <i class="fa fa-arrow-circle-left"></i> <?php echo $this->lang->line("go back");?></a>
				</div>

				<div class="col-12 col-md-4 share-sibing text-center">
					<a id="add_competutor" class="btn rounded btn-primary" href="<?php echo site_url("sitedoctor/add_domain/".$site_info[0]["id"]);?>"><i class="fa fa-adjust"></i> <?php echo $this->lang->line("compare with competitor");?></a>
				</div>

				<div class="col-12 col-md-4 share-sibing text-center">
					<a target="_blank" class="btn rounded btn-success float-right" href="<?php echo base_url('sitedoctor/report_pdf/'.$site_info[0]['id']); ?>"><i class="fas fa-cloud-download-alt"></i> <?php echo $this->lang->line("download pdf");?></a>
				</div>
			</div>
		</div>
		<div class="container-fluid border-bottom mb-5"></div>

		<div class="container">
			<div class="row">
				<div class="col-12 mt-5" id="subscribe_div" style="display:none"></div>
			</div>
		</div>
		
	<?php } ?>


<?php } ?>


	<?php 		
		$warning_count=$site_info[0]["warning_count"];
		//if($pass!="1") $warning_count++;
		
		$warning_class="success";
		if($warning_count>0) $warning_class="warning";
	?>

	<?php if($is_pdf == 1) : ?>
		<style type="text/css">
			*,body{margin:0;padding:0;box-sizing:border-box;background-color:#f4f6f9}
			.col-12{width:100%!important;}
			.card{box-shadow:0 4px 8px rgba(0,0,0,.03);background-color:#fff;border-radius:3px;;position:relative;margin-bottom:30px;}
			.card .card-body{background-color:transparent;}
			.card .card-header{border-bottom:0.5px solid red;width:100%;display:flex;}
			.card .card-header h4{font-size:16px;margin-bottom:0;text-transform: capitalize;}
			.card-body .long-recom { color: #000 !important;background-color:#eee; }
			.list-group-item{list-style:none;}

			.table tbody tr td,.table tbody tr th,.table thead tr td,.table thead tr th{padding:5px 10px;text-align:center;}

			.is_pdf_table{font-weight:700;color:#191919;border:solid 1px #e1e1e1;padding:10px;background-color:#e1e1e1;list-style:none!important;margin-top:10px;font-style:italic;letter-spacing:1px}
			.well{margin-bottom:10px;background-color:#eee;border-color:#eee;color:#6c757d}
			.score_card div { color: #fff;text-align: center; }

			.item-header { text-transform: capitalize; }

			.card-primary {border-top:2px solid #6777ef !important;}
			.card-success {border-top:2px solid #63ed7a !important;}
			.card-danger {border-top:2px solid #fc544b !important;}
			.card-warning {border-top:2px solid #ffa426 !important;}

			.card-primary .card-header h4 {color:#6777ef !important;}
			.card-success .card-header h4 {color:#63ed7a !important;}
			.card-danger  .card-header h4{color:#fc544b !important;}
			.card-warning .card-header h4 {color:#ffa426 !important;}

			.card-primary .pdf-heading li.active {background-color:#6777ef !important;border-color: #6777ef !important;color: #fff;}
			.card-success .pdf-heading li.active {background-color:#63ed7a !important;border-color: #63ed7a !important;color: #fff;}
			.card-danger .pdf-heading li.active {background-color:#fc544b !important;border-color: #fc544b !important;color: #fff;}
			.card-warning .pdf-heading li.active {background-color:#ffa426 !important;border-color: #ffa426 !important;color: #fff;}
			
			.card .card-body .pdf-heading li.active{color:#fff;padding:10px;font-weight:700;font-size: 14px !important;padding-left:14px !important;text-align: center;}
			.pdf-heading li { padding: 10px;border-bottom:.5px solid #dee2e6  }
		</style>
	<?php endif; ?>
	
	<style>
		.section{background:none;padding:0}.
		card .card-body .section-title + .section-lead{line-height:1.8}
		.table thead tr th{color:#6777ef !important;font-size: 14px;}
		.table tbody tr td{color:#6c757d;font-size: 14px;}

		.heading_styles { height: 150px;overflow-y: auto;border:0.5px solid rgba(0,0,0,.125);}
		/*.heading_styles .list-group-item:last-child { border-bottom:0;}*/

		.card.card-primary .section-title:before {background-color:#6777ef !important;}
		.card.card-success .section-title:before {background-color:#63ed7a !important;}
		.card.card-danger .section-title:before {background-color:#fc544b !important;}
		.card.card-warning .section-title:before {background-color:#ffa426 !important;}

		.card.card-primary .list-group-item.active {background-color:#6777ef !important;border-color: #6777ef;}
		.card.card-success .list-group-item.active {background-color:#63ed7a !important;border-color: #63ed7a;}
		.card.card-danger .list-group-item.active {background-color:#fc544b !important;border-color: #fc544b;}
		.card.card-warning .list-group-item.active {background-color:#ffa426 !important;border-color: #ffa426;}

		.card.card-statistic-1 .card-icon i { line-height: 80px !important; }
		.box-card .card-statistic-1{ border: .5px solid #dee2e6;border-radius: 4px; }
		.box-card .card-icon { border: .5px solid #dee2e6;background: #FAFDFB !important; }

		.bg-react { background-color: #70416d; }
		.bg-smart { background-color: #a3816a; }
		.bg-purple { background-color: #a3816a; }
		.bg-pink { background-color: #c54fa7; }
	</style>

	<?php if($is_pdf == 0) : ?>
		<style>
			.table-responsive-vertical{max-height: 300px;overflow-y:auto;}
		</style>
	<?php endif; ?>    

	<div class="<?php if($compare_report == 0) echo "container"; else echo "container-fluid"; ?>">
		<section class="section mt-5">
			<div class="row">
				<div class="col-12">
					<?php if($load_css_js!=1) {?>

						<?php if($compare_report == 1) { ?>
							<div class="alert alert-light alert-has-icon mt-3 mb-5">
								<div class="alert-icon"><i class="far fa-lightbulb"></i></div>
								<div class="alert-body">
									<div class="alert-title"><?php echo $this->lang->line('Domain Name'); ?></div>
									<a style="font-weight:700;" href="<?php echo $site_info[0]["domain_name"]; ?>"><?php echo $site_info[0]["domain_name"]; ?></a>
								</div>
							</div>
						<?php } ?>

						<div class="card card-primary is_pdf">
							<div class="card-header">
								<h4><i class="fas fa-star-half-alt"></i> <?php echo $this->lang->line('Score'); ?></h4>
								<div class="card-header-action"><i class="fa fa-minus minus"></i></div>
							</div>
							<div class="card-body chart-responsive minus">
								<div class="progress score_card">
								  <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $site_info[0]["overall_score"]; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $site_info[0]["overall_score"]; ?>%"><?php echo $site_info[0]["overall_score"]; ?></div>
								</div>
							</div>
						</div>
					<?php } else { ?>
						<?php if($compare_report == 1) { ?>
							<div class="alert alert-light alert-has-icon mt-3 mb-5">
								<div class="alert-icon"><i class="far fa-lightbulb"></i></div>
								<div class="alert-body">
									<div class="alert-title"><?php echo $this->lang->line('Domain Name'); ?></div>
									<a style="font-weight:700;" href="<?php echo $site_info[0]["domain_name"]; ?>"><?php echo $site_info[0]["domain_name"]; ?></a>
								</div>
							</div>
						<?php } ?>

						<div class="card card-primary">
							<div class="card-header">
								<h4><i class="fas fa-star-half-alt"></i> <?php echo $this->lang->line('Score'); ?></h4>
								<div class="card-header-action"><i class="fa fa-minus minus"></i></div>
							</div>
							<div class="card-body chart-responsive minus">
								<div class="progress score_card">
								  <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $site_info[0]["overall_score"]; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $site_info[0]["overall_score"]; ?>%"><?php echo $site_info[0]["overall_score"]; ?></div>
								</div>
							</div>
						</div>
					<?php } ?>

					<!-- page title start-->
					<?php 
					$recommendation_word = $this->lang->line("Knowledge Base");
					$value = $site_info[0]["title"];
					$check = $this->sitedoctor_library->title_check($value); 
					$item = $this->lang->line("Page Title");
					$long_recommendation=$this->lang->line('page_title_recommendation');
					if(strlen($value)==0) //error
					{
						$class="danger";
						$status="times";
						$short_recommendation=$this->lang->line("Your site do not have any title.");
					}
					else if($check=="1") // warning
					{
						$class="warning";
						$status="exclamation-circle";
						$short_recommendation=$this->lang->line("Your page title exceeds 60 characters. It's not good.");
					}
					else //ok
					{
						$class="success";
						$status="check";
						$short_recommendation=$this->lang->line("Your page title does not exceed 60 characters. It's fine.");
					}
					?>

					<!-- page title start -->
					<div class="card card-<?php echo $class; ?> is_pdf">
						<div class="card-header bbw">
							<h4 class="text-<?php echo $class; ?>" style="margin-top:0;"><i class="fa fa-<?php echo $status;?>"></i> <?php echo $item; ?></h4>
							<div class="card-header-action"><i class="fa fa-minus minus"></i></div>
						</div>
						<div class="card-body chart-responsive minus" <?php if($compare_report == 1 && $is_pdf == 0) echo 'style="height: 610px; overflow-y:auto;"'; ?>>
							<div class="section-title mt-0 item-header"><?php echo $item; ?></div>
							<p class="section-lead item-value"><?php echo $value; ?></p>

							<div class="section-title mt-0 item-header"><?php echo $this->lang->line("Short Recommendation"); ?></div>
							<p class="section-lead short-recom"><?php echo $short_recommendation; ?></p>

							<div class="section-title mt-0 item-header" title="<?php echo $item; ?> : <?php echo $recommendation_word; ?>">
								<?php echo $recommendation_word; ?></div>
							<p class="section-lead alert alert-light long-recom"><?php echo $long_recommendation; ?></p>

						</div>
					</div> 
					<!--  page title end-->
				</div>
			</div>

			<!-- meta description start-->				
			<div class="row">
				<div class="col-12">
					<?php 
						$value = $site_info[0]["description"];
						$check = $this->sitedoctor_library->description_check($value); 
						$item = $this->lang->line("Meta Description");
						$long_recommendation = $this->lang->line('description_recommendation');
						if(strlen($value)==0) // error
						{
							$class="danger";
							$status="times";
							$short_recommendation=$this->lang->line("Your site do not have any meta description.");
						}
						else if($check=="1") //warning
						{
							$class="warning";
							$status="exclamation-circle";
							$short_recommendation=$this->lang->line("Your meta description exceeds 150 characters. It's not good.");
						}
						else // ok
						{
							$class="success";
							$status="check";
							$short_recommendation=$this->lang->line("Your meta description does not exceed 150 characters. It's fine.");
						}
					?>

					<div class="card card-<?php echo $class; ?> is_pdf">
						<div class="card-header bbw">
							<h4 class="text-<?php echo $class; ?>" style="margin-top:0;"><i class="fa fa-<?php echo $status;?>"></i> <?php echo $item; ?></h4>
							<div class="card-header-action"><i class="fa fa-minus minus"></i>
							</div>
						</div>
						<div class="card-body chart-responsive minus" <?php if($compare_report == 1 && $is_pdf == 0) echo 'style="height: 600px; overflow-y:auto;"'?>>
							<div class="section-title mt-0 item-header"><?php echo $item; ?></div>
							<p class="section-lead item-value"><?php echo $value; ?></p>

							<div class="section-title mt-0 item-header"><?php echo $this->lang->line("Short Recommendation"); ?></div>
							<p class="section-lead short-recom"><?php echo $short_recommendation; ?></p>

							<div class="section-title mt-0 item-header" title="<?php echo $item; ?> : <?php echo $recommendation_word; ?>">
								<?php echo $recommendation_word; ?></div>
							<p class="section-lead alert alert-light long-recom"><?php echo $long_recommendation; ?></p>
							
						</div>
					</div>
				</div>
			</div>
			<!-- meta description end-->

			<!-- meta keyword start-->
			<div class="row">
				<div class="col-12">
					<?php 
					$value=$site_info[0]["meta_keyword"];
					$check=empty($value) ? 1 : 0;
					$item=$this->lang->line("Meta Keyword");
					$long_recommendation=$this->lang->line('meta_keyword_recommendation');
					if($check=="1") //error
					{
						$class="danger";
						$status="times";
						$short_recommendation=$this->lang->line("Your site do not have any meta keyword.");
					}
					else //ok
					{
						$class="success";
						$status="check";
						$short_recommendation="";
					}
					?> 

					<div class="card card-<?php echo $class; ?> is_pdf">
						<div class="card-header bbw">
							<h4 class="text-<?php echo $class; ?>"><i class="fa fa-<?php echo $status;?>"></i> <?php echo $item; ?></h4>
							<div class="card-header-action"><i class="fa fa-minus minus"></i></div>
						</div>
						<div class="card-body chart-responsive minus" <?php if($compare_report == 1 && $is_pdf == 0) echo 'style="height:400px; overflow-y:auto;"'?>>
							<div class="section-title mt-0 item-header"><?php echo $item; ?></div>
							<p class="section-lead item-value"><?php echo $value; ?></p>

							<div class="section-title mt-0 item-header"><?php echo $this->lang->line("Short Recommendation"); ?></div>
							<p class="section-lead short-recom"><?php echo $short_recommendation; ?></p>

							<div class="section-title mt-0 item-header" title="<?php echo $item; ?> : <?php echo $recommendation_word; ?>">
								<?php echo $recommendation_word; ?></div>
							<p class="section-lead alert alert-light long-recom"><?php echo $long_recommendation; ?></p>
							
						</div>
					</div>
				</div>
			</div>
			<!--  meta keyword end-->

			<div class="row">
				<?php 
					$one_phrase = json_decode($site_info[0]["keyword_one_phrase"],true); 
					$two_phrase = json_decode($site_info[0]["keyword_two_phrase"],true); 
					$three_phrase = json_decode($site_info[0]["keyword_three_phrase"],true); 
					$four_phrase = json_decode($site_info[0]["keyword_four_phrase"],true); 
					$total_words = empty($site_info[0]["total_words"]) ? 0 : $site_info[0]["total_words"];
					include("application/modules/sitedoctor/views/array_spam_keyword.php");
				
					$class="primary";
					$status="info-circle";
				?>

				<div class="col-12">
					<div class="card card-<?php echo $class; ?> is_pdf">
						<div class="card-header">
							<h4 class="text-<?php echo $class; ?>"><i class="fa fa-info-circle"></i> <?php echo $this->lang->line('Keyword Analysis'); ?></h4>
							<div class="card-header-action"><i class="fa fa-minus minus"></i></div>
						</div>

						<div class="card-body chart-responsive minus" <?php if($compare_report == 1 && $is_pdf == 0) echo 'style="height: 1670px; overflow-y:auto;";' ?>>
							<div class="col-12 col-sm-12 <?php if($compare_report == 0) echo "col-md-6 col-lg-6"; ?>">
								<div class="card card-<?php echo $class; ?>">
									<div class="card-header bg-<?php echo $class; ?>">
										<h4 class="text-white"><i class="fa fa-<?php echo $status;?>"></i> <?php echo $this->lang->line("Single Keywords"); ?></h4>
										<div class="card-header-action"><i class="fa fa-minus minus"></i></div>
									</div>

									<div class="card-body chart-responsive minus p-0">
										<div class="table-resposive table-responsive-vertical">
											<table class="table table-sm table-bordered table-hover text-center">
												<thead>
													<tr>
														<th><?php echo $this->lang->line("Keyword"); ?></th>
														<th><?php echo $this->lang->line("Occurrence"); ?></th>
														<th><?php echo $this->lang->line("Density"); ?></th>
														<th><?php echo $this->lang->line("Possible Spam"); ?></th>
													</tr>
												</thead>
												
												<tbody>
													<?php foreach ($one_phrase as $key => $value) : ?>
														<tr>
															<td><?php echo $key; ?></td>
															<td><?php echo $value; ?></td>
															<td><?php $occurence = ($value/$total_words)*100; echo round($occurence, 3)." %"; ?></td>
															<td><?php 
																	if(in_array(strtolower($key), $array_spam_keyword)) echo "Yes";
																	else echo 'No'; 
																?>
															</td>
														</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>

							<div class="col-12 col-sm-12 <?php if($compare_report == 0) echo "col-md-6 col-lg-6"; ?>">
								<div class="card card-<?php echo $class; ?>">
									<div class="card-header bg-<?php echo $class; ?>">
										<h4 class="text-white"><i class="fa fa-<?php echo $status;?>"></i> <?php echo $this->lang->line("Two Word Keywords"); ?></h4>
										<div class="card-header-action"><i class="fa fa-minus minus"></i></div>
									</div>

									<div class="card-body chart-responsive minus p-0">
										<div class="table-resposive table-responsive-vertical">
											<table class="table table-sm table-bordered table-hover text-center">
												<thead>
													<tr>
														<th><?php echo $this->lang->line("Keyword"); ?></th>
														<th><?php echo $this->lang->line("Occurrence"); ?></th>
														<th><?php echo $this->lang->line("Density"); ?></th>
														<th><?php echo $this->lang->line("Possible Spam"); ?></th>
													</tr>
												</thead>
												
												<tbody>
													<?php foreach ($two_phrase as $key => $value) : ?>
														<tr>
															<td><?php echo $key; ?></td>
															<td><?php echo $value; ?></td>
															<td><?php $occurence = $value/$total_words*100; echo round($occurence, 3)." %"; ?></td>
															<td><?php 
																	if(in_array(strtolower($key), $array_spam_keyword)) echo "Yes";
																	else echo 'No'; 
																?>
															</td>
														</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>

							<div class="col-12 col-sm-12 <?php if($compare_report == 0) echo "col-md-6 col-lg-6"; ?>">
								<div class="card card-<?php echo $class; ?>">
									<div class="card-header bg-<?php echo $class; ?>">
										<h4 class="text-white"><i class="fa fa-<?php echo $status;?>"></i> <?php echo $this->lang->line("Three Word Keywords"); ?></h4>
										<div class="card-header-action"><i class="fa fa-minus minus"></i></div>
									</div>

									<div class="card-body chart-responsive minus p-0">
										<div class="table-resposive table-responsive-vertical">
											<table class="table table-sm table-bordered table-hover text-center">
												<thead>
													<tr>
														<th><?php echo $this->lang->line("Keyword"); ?></th>
														<th><?php echo $this->lang->line("Occurrence"); ?></th>
														<th><?php echo $this->lang->line("Density"); ?></th>
														<th><?php echo $this->lang->line("Possible Spam"); ?></th>
													</tr>
												</thead>
												
												<tbody>
													<?php foreach ($three_phrase as $key => $value) : ?>
														<tr>
															<td><?php echo $key; ?></td>
															<td><?php echo $value; ?></td>
															<td><?php $occurence = $value/$total_words*100; echo round($occurence, 3)." %"; ?></td>
															<td><?php 
																	if(in_array(strtolower($key), $array_spam_keyword)) echo "Yes";
																	else echo 'No'; 
																?>
															</td>
														</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>

							<div class="col-12 col-sm-12 <?php if($compare_report == 0) echo "col-md-6 col-lg-6"; ?>">
								<div class="card card-<?php echo $class; ?>">
									<div class="card-header bg-<?php echo $class; ?>">
										<h4 class="text-white"><i class="fa fa-<?php echo $status;?>"></i> <?php echo $this->lang->line("Four Word Keywords"); ?></h4>
										<div class="card-header-action"><i class="fa fa-minus minus"></i></div>
									</div>

									<div class="card-body chart-responsive minus p-0">
										<div class="table-resposive table-responsive-vertical">
											<table class="table table-sm table-bordered table-hover text-center">
												<thead>
													<tr>
														<th><?php echo $this->lang->line("Keyword"); ?></th>
														<th><?php echo $this->lang->line("Occurrence"); ?></th>
														<th><?php echo $this->lang->line("Density"); ?></th>
														<th><?php echo $this->lang->line("Possible Spam"); ?></th>
													</tr>
												</thead>
												
												<tbody>
													<?php foreach ($four_phrase as $key => $value) : ?>
														<tr>
															<td><?php echo $key; ?></td>
															<td><?php echo $value; ?></td>
															<td><?php $occurence = $value/$total_words*100; echo round($occurence, 3)." %"; ?></td>
															<td><?php 
																	if(in_array(strtolower($key), $array_spam_keyword)) echo "Yes";
																	else echo 'No'; 
																?>
															</td>
														</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div> <!-- end of 1,2,3,4 keyword -->

			<!-- Key words usage start-->
			<div class="row">
				<div class="col-12">				
					<?php 
					$value = $site_info[0]["meta_keyword"];
					$check = $this->sitedoctor_library->keyword_usage_check($site_info[0]["meta_keyword"],array_keys($one_phrase),array_keys($two_phrase),array_keys($three_phrase),array_keys($four_phrase));
					$item = $this->lang->line("Keyword Usage");
					$long_recommendation=$this->lang->line('keyword_usage_recommendation');
					if($check=="1") //error
					{
						$class="danger";
						$status="times";
						$short_recommendation=$this->lang->line("The most using keywords do not match with meta keywords.");
					}
					else //ok
					{
						$class="success";
						$status="check";
						$short_recommendation=$this->lang->line("The most using keywords match with meta keywords.");
					}
					?>

					<div class="card card-<?php echo $class; ?> is_pdf">
						<div class="card-header bbw">
							<h4 class="text-<?php echo $class; ?>" style="margin-top:0;"><i class="fa fa-<?php echo $status;?>"></i> <?php echo $item; ?></h4>
							<div class="card-header-action"><i class="fa fa-minus minus"></i>
							</div>
						</div>
						<div class="card-body chart-responsive minus" <?php if($compare_report == 1 && $is_pdf == 0) echo 'style="height: 400px; overflow-y:auto;"';?>>
							<div class="section-title mt-0 item-header"><?php echo $item; ?></div>
							<p class="section-lead item-value"><?php echo $value; ?></p>

							<div class="section-title mt-0 item-header"><?php echo $this->lang->line("Short Recommendation"); ?></div>
							<p class="section-lead short-recom"><?php echo $short_recommendation; ?></p>

							<div class="section-title mt-0 item-header" title="<?php echo $item; ?> : <?php echo $recommendation_word; ?>">
								<?php echo $recommendation_word; ?></div>
							<p class="section-lead alert alert-light long-recom"><?php echo $long_recommendation; ?></p>
							
						</div>
					</div>
				</div>
			</div>
			<!--  Key words usage end-->

			<!--total words start-->
			<div class="row">
				<div class="col-12">				
					<?php 
						$value=$site_info[0]["total_words"];
						$item=$this->lang->line("Total Words");
						$long_recommendation=$this->lang->line('unique_stop_words_recommendation');
						$class="primary";
						$status="info-circle";
					
					?>

					<div class="card card-<?php echo $class; ?> is_pdf">
						<div class="card-header bbw">
							<h4 class="text-<?php echo $class; ?>" style="margin-top:0;"><i class="fa fa-<?php echo $status;?>"></i> <?php echo $item; ?></h4>
							<div class="card-header-action"><i class="fa fa-minus minus"></i>
							</div>
						</div>
						<div class="card-body chart-responsive minus" <?php if($compare_report == 1 && $is_pdf == 0) echo 'style="height: 460px; overflow-y:auto;"';?>>
							<div class="section-title mt-0 item-header"><?php echo $item; ?></div>
							<p class="section-lead item-value"><?php echo $value; ?></p>

							<div class="section-title mt-0 item-header" title="<?php echo $item; ?> : <?php echo $recommendation_word; ?>">
								<?php echo $recommendation_word; ?></div>
							<p class="section-lead alert alert-light long-recom"><?php echo $long_recommendation; ?></p>
							
						</div>
					</div>
				</div>
			</div>
			<!--total words end-->

			<!-- text_to_html_ratiostart-->
			<div class="row">
				<div class="col-12">				
					<?php 
					$check=round($site_info[0]["text_to_html_ratio"]); 
					$item=$this->lang->line("Text/HTML Ratio Test");
					$long_recommendation=$this->lang->line('text_to_html_ratio_recommendation');

					if($check<20) //error
					{
						$class="danger";
						$status="times";
						$short_recommendation=$this->lang->line("Site failed text/HTML ratio test.");
					}
					else //ok
					{
						$class="success";
						$status="check";
						$short_recommendation=$this->lang->line("Site passed text/HTML ratio test.");
					}
					// $short_recommendation.="<br/><br/><i class='fa fa-".$status."'></i> <b>".$item." : ".$check."%</b>";
					?>
					
					<div class="card card-<?php echo $class; ?> is_pdf">
						<div class="card-header bbw">
							<h4 class="text-<?php echo $class; ?>" style="margin-top:0;"><i class="fa fa-<?php echo $status;?>"></i> <?php echo $item; ?></h4>
							<div class="card-header-action"><i class="fa fa-minus minus"></i>
							</div>
						</div>
						<div class="card-body chart-responsive minus" <?php if($compare_report == 1 && $is_pdf == 0) echo 'style="height: 300px; overflow-y:auto;"';?>>

							<div class="section-title mt-0 item-header"><?php echo $short_recommendation; ?></div>
							<p class="section-lead item-value"><?php echo $item.' : '.$check."%"; ?></p>

							<div class="section-title mt-0 item-header" title="<?php echo $item; ?> : <?php echo $recommendation_word; ?>">
								<?php echo $recommendation_word; ?></div>
							<p class="section-lead alert alert-light long-recom"><?php echo $long_recommendation; ?></p>
							
						</div>
					</div>
				</div>
			</div>
			<!--text_to_html_ratio end-->

			<!-- html headings -->
			<div class="row">
				<?php 
					$h1=json_decode($site_info[0]["h1"],true); 
					$h2=json_decode($site_info[0]["h2"],true); 
					$h3=json_decode($site_info[0]["h3"],true); 
					$h4=json_decode($site_info[0]["h4"],true); 
					$h5=json_decode($site_info[0]["h5"],true); 
					$h6=json_decode($site_info[0]["h6"],true); 			
				?>
				<?php 
					$item=$this->lang->line("HTML Headings");
					$long_recommendation=$this->lang->line('heading_recommendation');
					$class="primary";
					$status="info-circle";
				?>
				<div class="col-12">
					<div class="card card-<?php echo $class;?> is_pdf">
						<div class="card-header">
							<h4 class="text-<?php echo $class;?>"><i class="fa fa-<?php echo $status;?>"></i> <?php echo $item; ?></h4>
							<div class="card-header-action">
								<i class="fa fa-minus minus"></i>
							</div>
						</div>

						<div class="card-body chart-responsive minus" <?php if($compare_report == 1 && $is_pdf == 0) echo 'style="height: 980px; overflow-y:auto;"';?>>
							<div class="row">
								<div class="col-12 pb-5 <?php if($compare_report == 0) echo "col-sm-6 col-md-4 col-lg-4"; else echo "col-sm-6 col-md-6 col-lg-6"; ?>">
									<ul class="list-group pdf-heading">
										<li class="list-group-item active text-center">H1(<?php echo count($h1) ?>)</li>  
										<div class="heading_styles">
											<?php foreach($h1 as $key=>$value): ?>
												<li class="list-group-item"><?php echo $value; ?></li>
											<?php endforeach; ?>
										</div>
									</ul>
								</div>
								<div class="col-12 pb-5 <?php if($compare_report == 0) echo "col-sm-6 col-md-4 col-lg-4"; else echo "col-sm-6 col-md-6 col-lg-6"; ?>">
									<ul class="list-group pdf-heading">
										<li class="list-group-item active text-center">H2(<?php echo count($h2) ?>)</li>  
										<div class="heading_styles">
											<?php foreach($h2 as $key=>$value): ?>
												<li class="list-group-item"><?php echo $value; ?></li>
											<?php endforeach; ?>
										</div>
									</ul>
								</div>
								<div class="col-12 pb-5 <?php if($compare_report == 0) echo "col-sm-6 col-md-4 col-lg-4"; else echo "col-sm-6 col-md-6 col-lg-6"; ?>">
									<ul class="list-group pdf-heading">
										<li class="list-group-item active text-center">H3(<?php echo count($h3) ?>)</li>  
										<div class="heading_styles">
											<?php foreach($h3 as $key=>$value): ?>
												<li class="list-group-item"><?php echo $value; ?></li>
											<?php endforeach; ?>
										</div>
									</ul>
								</div>

								<div class="col-12 pb-5 <?php if($compare_report == 0) echo "col-sm-6 col-md-4 col-lg-4"; else echo "col-sm-6 col-md-6 col-lg-6"; ?>">
									<ul class="list-group pdf-heading">
										<li class="list-group-item active text-center bold">H4(<?php echo count($h4) ?>)</li>  
										<div class="heading_styles">
											<?php foreach($h4 as $key=>$value): ?>
												<li class="list-group-item"><?php echo $value; ?></li>
											<?php endforeach; ?>
										</div>
									</ul>
								</div>
								<div class="col-12 pb-5 <?php if($compare_report == 0) echo "col-sm-6 col-md-4 col-lg-4"; else echo "col-sm-6 col-md-6 col-lg-6"; ?>">
									<ul class="list-group pdf-heading">
										<li class="list-group-item active text-center bold">H5(<?php echo count($h5) ?>)</li>  
										<div class="heading_styles">
											<?php foreach($h5 as $key=>$value): ?>
												<li class="list-group-item"><?php echo $value; ?></li>
											<?php endforeach; ?>
										</div>
									</ul>
								</div>
								<div class="col-12 pb-5 <?php if($compare_report == 0) echo "col-sm-6 col-md-4 col-lg-4"; else echo "col-sm-6 col-md-6 col-lg-6"; ?>">
									<ul class="list-group pdf-heading">
										<li class="list-group-item active text-center bold">H6(<?php echo count($h6) ?>)</li>  
										<div class="heading_styles">
											<?php foreach($h6 as $key=>$value): ?>
												<li class="list-group-item"><?php echo $value; ?></li>
											<?php endforeach; ?>
										</div>
									</ul>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-12">
									<div class="section-title mt-0 item-header" title="<?php echo $item; ?> : <?php echo $recommendation_word; ?>"><?php echo $recommendation_word; ?></div>
									<p class="section-lead alert alert-light long-recom"><?php echo $long_recommendation; ?></p>
								</div>
							</div>
						</div>
					</div>
				</div>		
			</div>
			<!-- html headings -->

			<!-- robot start-->
			<div class="row">
				<div class="col-12">				
					<?php 
					$value=$site_info[0]["robot_txt_exist"];
					$check=$value;
					$item=$this->lang->line("robot.txt");
					$long_recommendation=$this->lang->line('robot_recommendation');
					if($check=="0") //warning
					{
						$class="danger";
						$status="times";
						$short_recommendation=$this->lang->line("Your site does not have robot.txt.");
					}
					else //ok
					{
						$class="success";
						$status="check";
						$short_recommendation=$this->lang->line("Your site have robot.txt");
					}
					?>
					<div class="card card-<?php echo $class; ?> is_pdf">
						<div class="card-header">
							<h4 class="text-<?php echo $class; ?>"><i class="fa fa-<?php echo $status;?>"></i> <?php echo $item; ?></h4>
							<div class="card-header-action"><i class="fa fa-minus minus"></i></div>
						</div>

						<div class="card-body chart-responsive minus" <?php if($compare_report == 1 && $is_pdf == 0) echo 'style="height: 900px; overflow-y:auto;"';?>>
							<div class="section-title mt-0"><?php echo $this->lang->line("Short Recommendation"); ?></div>
							<p class="section-lead"><?php echo $short_recommendation; ?></p>

							<div class="row">
								<div class="col-12">
									<?php if($check == "1") { ?>
										<ul class="list-group generic-ul pdf-heading">
											<li class="list-group-item active text-center"><?php echo $item; ?></li>  
											<div class="heading_styles">
												<li class="list-group-item border-bottom">
													<?php print_r($site_info[0]["robot_txt_content"]);?>
												</li>
											</div>
										</ul>
									<?php } ?>
									<br/><br/>
									<div class="section-title mt-0 item-header" title="<?php echo $item; ?> : <?php echo $recommendation_word; ?>"><?php echo $recommendation_word; ?></div>
									<p class="section-lead alert alert-light long-recom"><?php echo $long_recommendation; ?></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--  robot end-->
		
			<!-- sitemap start-->
			<div class="row">
				<div class="col-12">				
					<?php 
					$value=$site_info[0]["sitemap_exist"];
					$check=$value;
					$item=$this->lang->line("Sitemap");
					$long_recommendation=$this->lang->line('sitemap_recommendation');
					if($check=="0") //warning
					{
						$class="danger";
						$status="times";
						$short_recommendation=$this->lang->line("Your site does not have sitemap");
					}
					else //ok
					{
						$class="success";
						$status="check";
						$short_recommendation=$this->lang->line("Your site have sitemap");
					}
					?>
					
					<div class="card card-<?php echo $class; ?> is_pdf">
						<div class="card-header">
							<h4 class="text-<?php echo $class; ?>"><i class="fa fa-<?php echo $status;?>"></i> <?php echo $item; ?></h4>
							<div class="card-header-action"><i class="fa fa-minus minus"></i></div>
						</div>

						<div class="card-body chart-responsive minus" <?php if($compare_report == 1 && $is_pdf == 0) echo 'style="height: 1060px; overflow-y:auto;"';?>>
							<div class="section-title mt-0"><?php echo $this->lang->line("Short Recommendation"); ?></div>
							<p class="section-lead"><?php echo $short_recommendation; ?></p>

							<?php if($check == "1") { ?>
								<div class="section-title mt-0 item-header"><?php echo $this->lang->line("Location"); ?></div>
								<p class="section-lead item-value">
									<a target='_BLANK' href="<?php echo $site_info[0]["sitemap_location"]; ?>"><?php echo $site_info[0]["sitemap_location"]; ?></a>
								</p>
							<?php } ?>

							<div class="section-title mt-0 item-header" title="<?php echo $item; ?> : <?php echo $recommendation_word; ?>"><?php echo $recommendation_word; ?></div>
							<p class="section-lead alert alert-light long-recom"><?php echo $long_recommendation; ?></p>
						</div>
					</div>
				</div>
			</div>
			<!--  sitemap end-->

			<!-- Internal Vs. External Links start-->
			<div class="row">
				<div class="col-12">				
					<?php 
					$item=$this->lang->line("Internal Vs. External Links");				
					$class="primary";
					$status="info-circle";
					
					?>
					
					<div class="card card-<?php echo $class; ?>">
						<div class="card-header">
							<h4 class="text-<?php echo $class; ?>"><i class="fa fa-<?php echo $status;?>"></i> <?php echo $item; ?></h4>
							<div class="card-header-action"><i class="fa fa-minus minus"></i></div>
						</div>

						<div class="card-body chart-responsive minus" <?php if($compare_report == 1 && $is_pdf == 0) echo 'style="height: 400px; overflow-y:auto;"';?>>
							<div class="row">
								<div class="col-12 col-sm-12 col-md-6 col-lg-6 box-card">
									<div class="card card-statistic-1">
										<div class="card-icon bg-body">
											<i class="fas fa-file-import text-<?php echo $class; ?>"></i>
										</div>
										<div class="card-wrap">
											<div class="card-header">
												<h4><?php echo $this->lang->line("Total Internal Links?"); ?></h4>
											</div>
											<div class="card-body"><?php echo $site_info[0]["internal_link_count"];?></div>
										</div>
									</div>
								</div>
								<div class="col-12 col-sm-12 col-md-6 col-lg-6 box-card">
									<div class="card card-statistic-1">
										<div class="card-icon bg-body">
											<i class="fas fa-file-export text-<?php echo $class; ?>"></i>
										</div>
										<div class="card-wrap">
											<div class="card-header">
												<h4><?php echo $this->lang->line("Total External Links?"); ?></h4>
											</div>
											<div class="card-body"><?php echo $site_info[0]["external_link_count"];?></div>
										</div>
									</div>
								</div>

								<div class="col-12 col-sm-12 col-md-6 col-lg-6">
									<ul class="list-group pdf-heading">
										<li class="list-group-item active text-center"><?php echo $this->lang->line("Internal Links"); ?></li>  
										<div class="heading_styles">
											<?php 
												$internal_link=json_decode($site_info[0]["internal_link"],true);											
												foreach ($internal_link as $value) 
												{
													echo "<li class='list-group-item'>".$value["link"]."</li>";
												}
											?>
										</div>
									</ul>
								</div>
								<div class="col-12 col-sm-12 col-md-6 col-lg-6">
									<ul class="list-group pdf-heading">
										<li class="list-group-item active text-center"><?php echo $this->lang->line("External Links"); ?></li>  
										<div class="heading_styles">
											<?php 
												$external_link=json_decode($site_info[0]["external_link"],true);
												foreach ($external_link as $value) 
												{
													echo "<li class='list-group-item'>".$value["link"]."</li>";
												}
											?>
										</div>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--  Internal Vs. External Links end-->
	
			<!-- Alexa Rank -->
			<div class="row">
				<div class="col-12">				
					<!-- sitemap start-->
					<?php 
					$item=$this->lang->line("Alexa Rank");				 
					$class="primary";
					$status="trophy";	
					$alexa_rank_array = json_decode($site_info[0]['alexa_rank'], true);	
					?>

					<div class="card card-<?php echo $class; ?>">
						<div class="card-header">
							<h4 class="text-<?php echo $class; ?>"><i class="fas fa-<?php echo $status;?>"></i> <?php echo $item; ?></h4>
							<div class="card-header-action"><i class="fa fa-minus minus"></i></div>
						</div>

						<div class="card-body chart-responsive minus" <?php if($compare_report == 1 && $is_pdf == 0) echo 'style="height: 315px; overflow-y:auto;"';?>>
							<div class="row">
								<div class="col-12 col-sm-12 <?php if($compare_report == 0) echo "col-md-3 col-lg-3"; else echo "col-md-6 col-lg-6"; ?>">
									<div class="card card-statistic-1 border">
										<div class="card-icon bg-primary">
											<i class="fas fa-info-circle"></i>
										</div>
										<div class="card-wrap">
											<div class="card-header">
												<h4><?php echo $this->lang->line("Traffic Rank"); ?></h4>
											</div>
											<div class="card-body"><?php echo $alexa_rank_array["traffic_rank"];?></div>
										</div>
									</div>
								</div>
								<div class="col-12 col-sm-12 <?php if($compare_report == 0) echo "col-md-3 col-lg-3"; else echo "col-md-6 col-lg-6"; ?>">
									<div class="card card-statistic-1 border">
										<div class="card-icon bg-warning">
											<i class="fas fa-info-circle"></i>
										</div>
										<div class="card-wrap">
											<div class="card-header">
												<h4><?php echo $this->lang->line("Reach Rank"); ?></h4>
											</div>
											<div class="card-body"><?php echo $alexa_rank_array["reach_rank"];?></div>
										</div>
									</div>
								</div>
								<div class="col-12 col-sm-12 <?php if($compare_report == 0) echo "col-md-3 col-lg-3"; else echo "col-md-6 col-lg-6"; ?>">
									<div class="card card-statistic-1 border">
										<div class="card-icon bg-success">
											<i class="fas fa-info-circle"></i>
										</div>
										<div class="card-wrap">
											<div class="card-header">
												<h4><?php echo $this->lang->line("Top Country"); ?></h4>
											</div>
											<div class="card-body"><?php echo $alexa_rank_array["country"];?></div>
										</div>
									</div>
								</div>
								<div class="col-12 col-sm-12 <?php if($compare_report == 0) echo "col-md-3 col-lg-3"; else echo "col-md-6 col-lg-6"; ?>">
									<div class="card card-statistic-1 border">
										<div class="card-icon bg-danger">
											<i class="fas fa-info-circle"></i>
										</div>
										<div class="card-wrap">
											<div class="card-header">
												<h4><?php echo $this->lang->line("Top Country Rank"); ?></h4>
											</div>
											<div class="card-body"><?php echo $alexa_rank_array["country_rank"];?></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- end Alexa Rank -->

			<!-- Domain IP Information -->
			<div class="row">
				<div class="col-12">
					<?php 
					$item=$this->lang->line("Domain IP Information");				
					$class="primary";
					$status="map-marker-alt";	
					$domain_ip_info = json_decode($site_info[0]['domain_ip_info'], true);	
					?>
					
					<div class="card card-<?php echo $class; ?>">
						<div class="card-header">
							<h4 class="text-<?php echo $class; ?>"><i class="fas fa-<?php echo $status;?>"></i> <?php echo $item; ?></h4>
							<div class="card-header-action"><i class="fa fa-minus minus"></i></div>
						</div>

						<div class="card-body chart-responsive minus" <?php if($compare_report == 1 && $is_pdf == 0) echo 'style="height: 570px; overflow-y:auto;"';?>>
							<div class="row">
								<div class="col-12 col-sm-12 <?php if($compare_report == 0) echo "col-md-3 col-lg-3"; else echo "col-md-6 col-lg-6"; ?>">
									<div class="card card-statistic-1 border">
										<div class="card-icon bg-primary">
											<i class="fas fa-info-circle"></i>
										</div>
										<div class="card-wrap">
											<div class="card-header">
												<h4><?php echo $this->lang->line("ISP"); ?></h4>
											</div>
											<div class="card-body" style="font-size:15px;"><?php echo $domain_ip_info["isp"];?></div>
										</div>
									</div>
								</div>
								<div class="col-12 col-sm-12 <?php if($compare_report == 0) echo "col-md-3 col-lg-3"; else echo "col-md-6 col-lg-6"; ?>">
									<div class="card card-statistic-1 border">
										<div class="card-icon bg-warning">
											<i class="fas fa-info-circle"></i>
										</div>
										<div class="card-wrap">
											<div class="card-header">
												<h4><?php echo $this->lang->line("IP"); ?></h4>
											</div>
											<div class="card-body" style="font-size:15px;"><?php echo $domain_ip_info["ip"];?></div>
										</div>
									</div>
								</div>
								<div class="col-12 col-sm-12 <?php if($compare_report == 0) echo "col-md-3 col-lg-3"; else echo "col-md-6 col-lg-6"; ?>">
									<div class="card card-statistic-1 border">
										<div class="card-icon bg-success">
											<i class="fas fa-info-circle"></i>
										</div>
										<div class="card-wrap">
											<div class="card-header">
												<h4><?php echo $this->lang->line("Organization"); ?></h4>
											</div>
											<div class="card-body" style="font-size:15px;"><?php echo $domain_ip_info["organization"];?></div>
										</div>
									</div>
								</div>
								<div class="col-12 col-sm-12 <?php if($compare_report == 0) echo "col-md-3 col-lg-3"; else echo "col-md-6 col-lg-6"; ?>">
									<div class="card card-statistic-1 border">
										<div class="card-icon bg-danger">
											<i class="fas fa-info-circle"></i>
										</div>
										<div class="card-wrap">
											<div class="card-header">
												<h4><?php echo $this->lang->line("City"); ?></h4>
											</div>
											<div class="card-body" style="font-size:15px;"><?php echo $domain_ip_info["city"];?></div>
										</div>
									</div>
								</div>

								<div class="col-12 col-sm-12 <?php if($compare_report == 0) echo "col-md-3 col-lg-3"; else echo "col-md-6 col-lg-6"; ?>">
									<div class="card card-statistic-1 border">
										<div class="card-icon bg-react">
											<i class="fas fa-info-circle"></i>
										</div>
										<div class="card-wrap">
											<div class="card-header">
												<h4><?php echo $this->lang->line("Country"); ?></h4>
											</div>
											<div class="card-body" style="font-size:15px;"><?php echo $domain_ip_info["country"];?></div>
										</div>
									</div>
								</div>
								<div class="col-12 col-sm-12 <?php if($compare_report == 0) echo "col-md-3 col-lg-3"; else echo "col-md-6 col-lg-6"; ?>">
									<div class="card card-statistic-1 border">
										<div class="card-icon bg-smart">
											<i class="fas fa-info-circle"></i>
										</div>
										<div class="card-wrap">
											<div class="card-header">
												<h4><?php echo $this->lang->line("Time Zone"); ?></h4>
											</div>
											<div class="card-body" style="font-size:15px;"><?php echo $domain_ip_info["time_zone"];?></div>
										</div>
									</div>
								</div>
								<div class="col-12 col-sm-12 <?php if($compare_report == 0) echo "col-md-3 col-lg-3"; else echo "col-md-6 col-lg-6"; ?>">
									<div class="card card-statistic-1 border">
										<div class="card-icon bg-pink">
											<i class="fas fa-info-circle"></i>
										</div>
										<div class="card-wrap">
											<div class="card-header">
												<h4><?php echo $this->lang->line("Longitude"); ?></h4>
											</div>
											<div class="card-body" style="font-size:15px;"><?php echo $domain_ip_info["longitude"];?></div>
										</div>
									</div>
								</div>
								<div class="col-12 col-sm-12 <?php if($compare_report == 0) echo "col-md-3 col-lg-3"; else echo "col-md-6 col-lg-6"; ?>">
									<div class="card card-statistic-1 border">
										<div class="card-icon bg-purple">
											<i class="fas fa-info-circle"></i>
										</div>
										<div class="card-wrap">
											<div class="card-header">
												<h4><?php echo $this->lang->line("Latitude"); ?></h4>
											</div>
											<div class="card-body" style="font-size:15px;"><?php echo $domain_ip_info["latitude"];?></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- end Domain Ip Information -->

			<!-- NoIndex , NoFollow, DoFollow Links start-->
			<div class="row">
				<div class="col-12">				
					<?php 
						$item=$this->lang->line("NoIndex , NoFollow, DoFollow Links");
						$long_recommendation=$this->lang->line('no_do_follow_recommendation');
						
						$class="primary";
						$status="directions";
					?>

					<div class="card card-<?php echo $class; ?>">
						<div class="card-header">
							<h4 class="text-<?php echo $class; ?>"><i class="fas fa-<?php echo $status;?>"></i> <?php echo $item; ?></h4>
							<div class="card-header-action"><i class="fa fa-minus minus"></i></div>
						</div>

						<div class="card-body chart-responsive minus" <?php if($compare_report == 1 && $is_pdf == 0) echo 'style="height: 1220px; overflow-y:auto;"';?>>
							<div class="row">
								<div class="col-12 <?php if($compare_report == 0) echo "col-md-4 col-lg-4"; else echo "col-md-6 col-lg-6"; ?>">
									<div class="card card-statistic-1 border">
										<div class="card-icon bg-primary">
											<i class="fas fa-info-circle"></i>
										</div>
										<div class="card-wrap">
											<div class="card-header">
												<h4><?php echo $this->lang->line("Total NoIndex Links"); ?></h4>
											</div>
											<div class="card-body" style="font-size:15px;"><?php echo count(json_decode($site_info[0]["noindex_list"],true)); ?></div>
										</div>
									</div>
								</div>
									
								<div class="col-12 <?php if($compare_report == 0) echo "col-md-4 col-lg-4"; else echo "col-md-6 col-lg-6"; ?>">
									<div class="card card-statistic-1 border">
										<div class="card-icon bg-warning">
											<i class="fas fa-info-circle"></i>
										</div>
										<div class="card-wrap">
											<div class="card-header">
												<h4><?php echo $this->lang->line("Total NoFollow Links"); ?></h4>
											</div>
											<div class="card-body" style="font-size:15px;"><?php echo $site_info[0]["nofollow_link_count"]; ?></div>
										</div>
									</div>
								</div>
									
								<div class="col-12 <?php if($compare_report == 0) echo "col-md-4 col-lg-4"; else echo "col-md-6 col-lg-6"; ?>">
									<div class="card card-statistic-1 border">
										<div class="card-icon bg-danger">
											<i class="fas fa-info-circle"></i>
										</div>
										<div class="card-wrap">
											<div class="card-header">
												<h4><?php echo $this->lang->line("Total DoFollow Links"); ?></h4>
											</div>
											<div class="card-body" style="font-size:15px;"><?php echo $site_info[0]["dofollow_link_count"]; ?></div>
										</div>
									</div>
								</div>
									
								<div class="col-12 <?php if($compare_report == 0) echo "col-md-6 col-lg-6"; else echo "col-md-6 col-lg-6"; ?>">
									<div class="card card-statistic-1 border">
										<div class="card-icon bg-pink">
											<i class="fas fa-info-circle"></i>
										</div>
										<div class="card-wrap">
											<div class="card-header">
												<h4><?php echo $this->lang->line("NoIndex Enabled by Meta Robot?"); ?></h4>
											</div>
											<div class="card-body" style="font-size:15px;"><?php echo $site_info[0]["noindex_by_meta_robot"];?></div>
										</div>
									</div>
								</div>
									
								<div class="col-12 <?php if($compare_report == 0) echo "col-md-6 col-lg-6"; else echo "col-md-6 col-lg-6"; ?>">
									<div class="card card-statistic-1 border">
										<div class="card-icon bg-smart">
											<i class="fas fa-info-circle"></i>
										</div>
										<div class="card-wrap">
											<div class="card-header">
												<h4><?php echo $this->lang->line("NoFollow Enabled by Meta Robot?"); ?></h4>
											</div>
											<div class="card-body" style="font-size:15px;"><?php echo $site_info[0]["nofollowed_by_meta_robot"];?></div>
										</div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-12 <?php if($compare_report == 0) echo "col-md-6 col-lg-6"; else echo "col-md-6 col-lg-6"; ?>">
									<ul class="list-group pdf-heading">
										<li class="list-group-item active text-center"><?php echo $this->lang->line("NoIndex Links"); ?></li>  
										<div class="heading_styles">
											<?php 
												$noindex_list=json_decode($site_info[0]["noindex_list"],true);
												foreach ($noindex_list as $value) 
												{
													echo "<li class='list-group-item'>".$value."</li>";
												}
											?>
										</div>
									</ul>
								</div>
								<div class="col-12 <?php if($compare_report == 0) echo "col-md-6 col-lg-6"; else echo "col-md-6 col-lg-6"; ?>">
									<ul class="list-group pdf-heading">
										<li class="list-group-item active text-center"><?php echo $this->lang->line("NoFollow Links"); ?></li>
										<div class="heading_styles"> 
										<?php 
											$nofollow_links=json_decode($site_info[0]["nofollow_link_list"],true);
											foreach ($nofollow_links as $value) 
											{
												echo "<li class='list-group-item'>".$value."</li>";
											}
										?>
										</div>
									</ul>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-12">
									<div class="section-title mt-0 item-header" title="<?php echo $item; ?> : <?php echo $recommendation_word; ?>"><?php echo $recommendation_word; ?></div>
									<div class="section-lead alert alert-light long-recom"><?php echo $long_recommendation; ?></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- NoIndex , NoFollow, DoFollow Links end-->

			<!-- seo friendly link start-->
			<div class="row">
				<div class="col-12">				
					<?php 
						$value=json_decode($site_info[0]["not_seo_friendly_link"],true);
						$check=count($value);
						$item=$this->lang->line("SEO Friendly Links");
						$long_recommendation=$this->lang->line('seo_friendly_recommendation');
						if($check==0) //ok
						{
							$class="success";
							$status="check";
							$short_recommendation=$this->lang->line("Links of your site are SEO friendly.");
						}
						else //error
						{
							$class="danger";
							$status="times";
							$short_recommendation=$this->lang->line("Some links of your site are not SEO friendly.");
						}
					?>
					<div class="card card-<?php echo $class; ?>">
						<div class="card-header">
							<h4 class="text-<?php echo $class; ?>"><i class="fa fa-<?php echo $status;?>"></i> <?php echo $item; ?></h4>
							<div class="card-header-action"><i class="fa fa-minus minus"></i></div>
						</div>

						<div class="card-body chart-responsive minus" <?php if($compare_report == 1 && $is_pdf == 0) echo 'style="height: 780px; overflow-y:auto;"';?>>
							<div class="section-title mt-0"><?php echo $this->lang->line('Short Recommendation'); ?></div>
							<p class="section-lead"><?php echo $short_recommendation; ?></p>

							<?php if($check > 0) { ?>
							<ul class="list-group pdf-heading">
								<li class="list-group-item active"><?php echo $this->lang->line('Not SEO Friendly Links'); ?></li>  
								<div class="heading_styles">
									<?php 
										foreach ($value as $val) {
											echo "<li class='list-group-item'>".$val."</li>";
										}
									?>
								</div>
							</ul>
							<?php } ?>
							<br>
							<div class="section-title mt-0 item-header" title="<?php echo $item; ?> : <?php echo $recommendation_word; ?>"><?php echo $recommendation_word; ?></div>
							<p class="section-lead alert alert-light long-recom"><?php echo $long_recommendation; ?></p>

						</div>
					</div>
				</div>
			</div>
			<!--  seo friendly link end-->

			<!-- favicon start-->
			<div class="row">
				<div class="col-12">				
					<?php 
						$check=$site_info[0]["is_favicon_found"];
						$item=$this->lang->line("Favicon");
						$long_recommendation="<a target='_BLANK' href='http://blog.woorank.com/2014/07/favicon-seo/'><i class='fa fa-hand-o-right'></i>  Learn more</a>";
						if($check=="0") //error
						{
							$class="warning";
							$status="exclamation-circle";
							$short_recommendation=$this->lang->line("Your site does not have favicon.");
						}
						else //ok
						{
							$class="success";
							$status="check";
							$short_recommendation=$this->lang->line("Your site have favicon.");
						}
					?>
					
					<div class="card card-<?php echo $class; ?>">
						<div class="card-header">
							<h4 class="text-<?php echo $class; ?>"><i class="fa fa-<?php echo $status;?>"></i> <?php echo $item; ?></h4>
							<div class="card-header-action"><i class="fa fa-minus minus"></i></div>
						</div>

						<div class="card-body chart-responsive minus" <?php if($compare_report == 1 && $is_pdf == 0) echo 'style="height: 250px; overflow-y:auto;"';?>>
							<div class="section-title mt-0 item-header"><?php echo $this->lang->line('Short Recommendation'); ?></div>
							<p class="section-lead short-recom"><?php echo $short_recommendation; ?></p>

							<div class="section-title mt-0 item-header"><?php echo $recommendation_word ?></div>
							<p class="section-lead alert alert-light logn-recom"><?php echo $long_recommendation; ?></p>
						</div>
					</div>
				</div>
			</div>
			<!--  favicon end-->

			<!-- img alt start-->
			<div class="row">
				<div class="col-12">				
					<?php 
					$value=json_decode($site_info[0]["image_not_alt_list"],true);
					$check=$site_info[0]["image_without_alt_count"];
					$item=$this->lang->line("Image 'alt' Test");
					$long_recommendation=$this->lang->line('img_alt_recommendation');
					if($check=="0") //ok
					{
						$class="success";
						$status="check";
						$short_recommendation=$this->lang->line("Your site does not have any image without alt text.");
					}
					else //warning
					{
						$class="warning";
						$status="exclamation-circle";
						$short_recommendation=$this->lang->line("Your site have").$check.$this->lang->line("images without alt text.");
					}
					?>
					
					<div class="card card-<?php echo $class; ?>">
						<div class="card-header">
							<h4 class="text-<?php echo $class; ?>"><i class="fa fa-<?php echo $status;?>"></i> <?php echo $item; ?></h4>
							<div class="card-header-action"><i class="fa fa-minus minus"></i></div>
						</div>

						<div class="card-body chart-responsive minus" <?php if($compare_report == 1 && $is_pdf == 0) echo 'style="height: 600px; overflow-y:auto;"';?>>
							<div class="section-title mt-0 item-header"><?php echo $this->lang->line('Short Recommendation'); ?></div>
							<p class="section-lead short-recom"><?php echo $short_recommendation; ?></p>

							<?php if($check > 0) { ?>
							<ul class="list-group pdf-heading">
								<li class="list-group-item active"><?php echo $this->lang->line('Images Without alt'); ?></li>  
								<div class="heading_styles">
									<?php 
										foreach ($value as $val) {
											echo "<li class='list-group-item'>".$val."</li>";
										}
									?>
								</div>
							</ul>
							<?php } ?>
							<br>
							<div class="section-title mt-0 item-header" title="<?php echo $item; ?> : <?php echo $recommendation_word; ?>"><?php echo $recommendation_word; ?></div>
							<p class="section-lead alert alert-light long-recom"><?php echo $long_recommendation; ?></p>

						</div>
					</div>
				</div>
			</div>
			<!--  img alt end-->

			<!-- doctype start-->
			<div class="row">
				<div class="col-12">				
					<?php 
					$value=$site_info[0]["doctype"];
					$check=$site_info[0]["doctype_is_exist"]; 
					$item=$this->lang->line("DOC Type");
					$long_recommendation=$this->lang->line('doc_type_recommendation');
					if($check=="0") //error
					{
						$class="danger";
						$status="times";
						$short_recommendation=$this->lang->line("Page do not have doc type");
					}
					else //ok
					{
						$class="success";
						$status="check";
						$short_recommendation=$this->lang->line("Page have doc type.");
					}
					?>
					
					<div class="card card-<?php echo $class; ?>">
						<div class="card-header">
							<h4 class="text-<?php echo $class; ?>"><i class="fa fa-<?php echo $status;?>"></i> <?php echo $item; ?></h4>
							<div class="card-header-action"><i class="fa fa-minus minus"></i></div>
						</div>

						<div class="card-body chart-responsive minus" <?php if($compare_report == 1 && $is_pdf == 0) echo 'style="height: 300px; overflow-y:auto;"';?>>
							<div class="section-title mt-0 item-header"><?php echo "<b>".$item."</b> : ".$value; ?></div>

							<div class="section-title mt-0 item-header"><?php echo $this->lang->line('Short Recommendation'); ?></div>
							<p class="section-lead short-recom"><?php echo $short_recommendation; ?></p>

							<div class="section-title mt-0 item-header" title="<?php echo $item; ?> : <?php echo $recommendation_word; ?>"><?php echo $recommendation_word; ?></div>
							<p class="section-lead alert alert-light long-recom"><?php echo $long_recommendation; ?></p>
						</div>
					</div>
				</div>
			</div>
			<!-- doctype end-->

			<!-- depreciate tag start-->
			<div class="row">
				<div class="col-12">				
					<?php 
					$value=json_decode($site_info[0]["depreciated_html_tag"],true);
					$check=array_sum($value);
					$item=$this->lang->line("Depreciated HTML Tag");
					$long_recommendation=$this->lang->line('depreciated_html_recommendation');
					if($check==0) //ok
					{
						$class="success";
						$status="check";
						$short_recommendation=$this->lang->line("Your site does not have any depreciated HTML tag.");
					}
					else //warning
					{
						$class="danger";
						$status="times";
						$short_recommendation=$this->lang->line("Your site have").$check.$this->lang->line("depreciated HTML tags.");
					}
					?>

					<div class="card card-<?php echo $class; ?>">
						<div class="card-header">
							<h4 class="text-<?php echo $class; ?>"><i class="fa fa-<?php echo $status;?>"></i> <?php echo $item; ?></h4>
							<div class="card-header-action"><i class="fa fa-minus minus"></i></div>
						</div>

						<div class="card-body chart-responsive minus" <?php if($compare_report == 1 && $is_pdf == 0) echo 'style="height: 600px; overflow-y:auto;"';?>>
							<div class="section-title mt-0 item-header"><?php echo $this->lang->line('Short Recommendation'); ?></div>
							<p class="section-lead short-recom"><?php echo $short_recommendation; ?></p>

							<?php if($check > 0) { ?>
							<ul class="list-group pdf-heading">
								<li class="list-group-item active"><?php echo $this->lang->line('Depreciated HTML Tags'); ?></li>  
								<div class="heading_styles">
									<?php 
										foreach ($value as $key=>$val) 
										{
											echo "<li class='list-group-item'>".$key." : ".$val."</li>";
										}
									?>
								</div>
							</ul>
							<?php } ?>
							<br>
							<div class="section-title mt-0 item-header" title="<?php echo $item; ?> : <?php echo $recommendation_word; ?>"><?php echo $recommendation_word; ?></div>
							<p class="section-lead alert alert-light long-recom"><?php echo $long_recommendation; ?></p>

						</div>
					</div>
				</div>
			</div>
			<!--  depreciate tag end-->
	
			<!-- html page size start-->
			<div class="row">
				<div class="col-12">				
					<?php 
					$value=round($site_info[0]["total_page_size_general"])." KB";
					$check=$value; 
					$item=$this->lang->line("HTML Page Size");
					$long_recommendation=$this->lang->line('html_page_size_recommendation');
					if($check>100) // warning
					{
						$class="warning";
						$status="exclamation-circle";
						$short_recommendation=$this->lang->line("HTML page size is > 100KB");
					}
					else // ok
					{
						$class="success";
						$status="check";
						$short_recommendation=$this->lang->line("HTML page size is <= 100KB");
					}
					?>

					<div class="card card-<?php echo $class; ?>">
						<div class="card-header">
							<h4 class="text-<?php echo $class; ?>"><i class="fa fa-<?php echo $status;?>"></i> <?php echo $item; ?></h4>
							<div class="card-header-action"><i class="fa fa-minus minus"></i></div>
						</div>

						<div class="card-body chart-responsive minus" <?php if($compare_report == 1 && $is_pdf == 0) echo 'style="height: 600px; overflow-y:auto;"';?>>
							<div class="section-title mt-0 item-header"><?php echo "<b>".$item."</b> : ".$value; ?></div>

							<div class="section-title mt-0 item-header"><?php echo $this->lang->line('Short Recommendation'); ?></div>
							<p class="section-lead short-recom"><?php echo $short_recommendation; ?></p>

							<div class="section-title mt-0 item-header" title="<?php echo $item; ?> : <?php echo $recommendation_word; ?>"><?php echo $recommendation_word; ?></div>
							<p class="section-lead alert alert-light long-recom"><?php echo $long_recommendation; ?></p>
						</div>
					</div>
				</div>
			</div>
			<!--  html page size end-->

			<!-- GZIP Compression start-->
			<div class="row">
				<div class="col-12">				
					<?php 

					$value=round($site_info[0]["page_size_gzip"])." KB";
					$check=$site_info[0]["is_gzip_enable"]; 
					$item=$this->lang->line("GZIP Compression");
					$item2="GZIP Compressed Size";
					$long_recommendation=$this->lang->line('gzip_recommendation');
					if($check=="0") // warning
					{
						$class="danger";
						$status="times";
						$short_recommendation=$this->lang->line("GZIP compression is disabled.");
					}
					else // ok
					{
						$class="success";
						$status="check";
						$short_recommendation=$this->lang->line("GZIP compression is enabled.");
						if(round($site_info[0]["page_size_gzip"]) > 33) 
						{
							$short_recommendation.=$this->lang->line("GZIP compressed size should be < 33KB");
							$class="danger";
							$status="times";
						}
					}
					?>

					<div class="card card-<?php echo $class; ?>">
						<div class="card-header">
							<h4 class="text-<?php echo $class; ?>"><i class="fa fa-<?php echo $status;?>"></i> <?php echo $item; ?></h4>
							<div class="card-header-action"><i class="fa fa-minus minus"></i></div>
						</div>

						<div class="card-body chart-responsive minus" <?php if($compare_report == 1 && $is_pdf == 0) echo 'style="height: 480px; overflow-y:auto;"';?>>
							<?php if($check == "1") { ?>
								<div class="section-title mt-0 item-header"><?php echo "<b>".$item2."</b> : ".$value; ?></div>
							<?php } ?>

							<div class="section-title mt-0 item-header"><?php echo $this->lang->line('Short Recommendation'); ?></div>
							<p class="section-lead short-recom"><?php echo $short_recommendation; ?></p>

							<div class="section-title mt-0" title="<?php echo $item; ?> : <?php echo $recommendation_word; ?>"><?php echo $recommendation_word; ?></div>
							<p class="section-lead alert alert-light long-recom"><?php echo $long_recommendation; ?></p>
						</div>
					</div>
				</div>
			</div>
			<!-- GZIP Compression end-->

			<!-- inline css start-->
			<div class="row">
				<div class="col-12">				
					<?php 
					$value=json_decode($site_info[0]["inline_css"],true);
					$check=count($value);
					$item=$this->lang->line("Inline CSS");
					$long_recommendation=$this->lang->line('inline_css_recommendation');
					if($check==0) //ok
					{
						$class="success";
						$status="check";
						$short_recommendation=$this->lang->line("Your site does not have any inline css.");
					}
					else //warning
					{
						$class="danger";
						$status="times";
						$short_recommendation=$this->lang->line("Your site have").$check.$this->lang->line("inline css.");
					}
					?>

					<div class="card card-<?php echo $class; ?>">
						<div class="card-header">
							<h4 class="text-<?php echo $class; ?>"><i class="fa fa-<?php echo $status;?>"></i> <?php echo $item; ?></h4>
							<div class="card-header-action"><i class="fa fa-minus minus"></i></div>
						</div>

						<div class="card-body chart-responsive minus" <?php if($compare_report == 1 && $is_pdf == 0) echo 'style="height: 500px; overflow-y:auto;"';?>>
							<div class="section-title mt-0"><?php echo $this->lang->line('Short Recommendation'); ?></div>
							<p class="section-lead"><?php echo $short_recommendation; ?></p>

							<?php if($check > 0) { ?>
							<ul class="list-group pdf-heading">
								<li class="list-group-item active"><?php echo $this->lang->line('Inline CSS'); ?></li>  
								<div class="heading_styles">
									<?php 
										foreach ($value as $val) {
											echo "<li class='list-group-item'>".$val."</li>";
										}
									?>
								</div>
							</ul>
							<?php } ?>
							<br>
							<div class="section-title mt-0 item-header" title="<?php echo $item; ?> : <?php echo $recommendation_word; ?>"><?php echo $recommendation_word; ?></div>
							<p class="section-lead alert alert-light long-recom"><?php echo $long_recommendation; ?></p>

						</div>
					</div>
				</div>
			</div>
			<!-- inline css end-->

			<!--  internal css start-->
			<div class="row">
				<div class="col-12">				
					<?php 
					$value=json_decode($site_info[0]["internal_css"],true);
					$check=count($value);
					$item=$this->lang->line("Internal CSS");
					$long_recommendation=$this->lang->line('internal_css_recommendation');
					if($check==0) //ok
					{
						$class="success";
						$status="check";
						$short_recommendation=$this->lang->line("Your site does not have any internal css.");
					}
					else //warning
					{
						$class="danger";
						$status="times";
						$short_recommendation=$this->lang->line("Your site have").$check.$this->lang->line("internal css.");
					}
					?>

					<div class="card card-<?php echo $class; ?>">
						<div class="card-header">
							<h4 class="text-<?php echo $class; ?>"><i class="fa fa-<?php echo $status;?>"></i> <?php echo $item; ?></h4>
							<div class="card-header-action"><i class="fa fa-minus minus"></i></div>
						</div>

						<div class="card-body chart-responsive minus" <?php if($compare_report == 1 && $is_pdf == 0) echo 'style="height: 300px; overflow-y:auto;"';?>>
							<div class="section-title mt-0 item-header"><?php echo $this->lang->line('Short Recommendation'); ?></div>
							<p class="section-lead short-recom"><?php echo $short_recommendation; ?></p>

							<div class="section-title mt-0 item-header" title="<?php echo $item; ?> : <?php echo $recommendation_word; ?>"><?php echo $recommendation_word; ?></div>
							<p class="section-lead alert alert-light long-recom"><?php echo $long_recommendation; ?></p>

						</div>
					</div>
				</div>
			</div>
			<!--internal css end-->

			<div class="row">
				<div class="col-12">				
					<!-- micro data schema start-->
					<?php 
					$value=json_decode($site_info[0]["micro_data_schema_list"],true);
					$check=count($value);
					$item=$this->lang->line("Micro Data Schema Test");
					$long_recommendation=$this->lang->line('micro_data_recommendation');
					if($check>0) //ok
					{
						$class="success";
						$status="check";
						$short_recommendation=$this->lang->line("Site passed micro data schema test.").$check.$this->lang->line("results found.");
					}
					else //error
					{
						$class="danger";
						$status="times";
						$short_recommendation=$this->lang->line("Site failed micro data schema test.");
					}
					?>
					
					<div class="card card-<?php echo $class; ?>">
						<div class="card-header">
							<h4 class="text-<?php echo $class; ?>"><i class="fa fa-<?php echo $status;?>"></i> <?php echo $item; ?></h4>
							<div class="card-header-action"><i class="fa fa-minus minus"></i></div>
						</div>

						<div class="card-body chart-responsive minus" <?php if($compare_report == 1 && $is_pdf == 0) echo 'style="height: 450px; overflow-y:auto;"';?>>
							<div class="section-title mt-0 item-header"><?php echo $this->lang->line('Short Recommendation'); ?></div>
							<p class="section-lead short-recom"><?php echo $short_recommendation; ?></p>

							<?php if($check > 0) { ?>
							<ul class="list-group pdf-heading">
								<li class="list-group-item active"><?php echo $this->lang->line('Micro data schema list'); ?></li>  
								<div class="heading_styles">
									<?php 
										foreach ($value as $val) {
											echo "<li class='list-group-item'>".$val."</li>";
										}
									?>
								</div>
							</ul>
							<?php } ?>
							<br>
							<div class="section-title mt-0 item-header" title="<?php echo $item; ?> : <?php echo $recommendation_word; ?>"><?php echo $recommendation_word; ?></div>
							<p class="section-lead alert alert-light long-recom"><?php echo $long_recommendation; ?></p>

						</div>
					</div>
				</div>
			</div>
			<!--  micro data schema end-->

			<!-- ip dns start-->
			<div class="row">
				<div class="col-12">				
					<?php 
						$item=$this->lang->line("IP & DNS Report");				
						$class="primary";
						$status="info-circle";
					?>
					
					<div class="card card-<?php echo $class; ?>">
						<div class="card-header">
							<h4 class="text-<?php echo $class; ?>"><i class="fa fa-<?php echo $status;?>"></i> <?php echo $item; ?></h4>
							<div class="card-header-action"><i class="fa fa-minus minus"></i></div>
						</div>

						<div class="card-body chart-responsive minus" <?php if($compare_report == 1 && $is_pdf == 0) echo 'style="height: 620px; overflow-y:auto;"';?>>
							<div class="row">
								<div class="col-12 col-md-6">
									<div class="card card-statistic-1 border">
										<div class="card-icon bg-primary">
											<i class="fas fa-map-marker-alt"></i>
										</div>
										<div class="card-wrap">
											<div class="card-header">
												<h4><?php echo $this->lang->line('IPv4'); ?></h4>
											</div>
											<div class="card-body">
												<?php echo $site_info[0]["ip"];?>
											</div>
										</div>
									</div>
								</div>
								<div class="col-12 col-md-6">
									<div class="card card-statistic-1 border">
										<div class="card-icon bg-<?php echo $class; ?>">
											<i class="fas fa-map-marker-alt"></i>
										</div>
										<div class="card-wrap">
											<div class="card-header">
												<h4><?php echo $this->lang->line('IPv6'); ?></h4>
											</div>
											<div class="card-body">
												<?php if($site_info[0]["is_ipv6_compatiable"]==0) echo "<small>".$this->lang->line("Not Compatiable")."</small>"; else echo $site_info[0]["ipv6"];?>
											</div>
										</div>
									</div>
								</div>
							</div>
							

							<?php 
							$dns_report=json_decode($site_info[0]["dns_report"],true);
							if(count($dns_report)>0)
							{ ?>
								<div class="row">
									<div class="col-12">
										<div class="section-title mt-0 item-header"><?php echo $this->lang->line('DNS Report'); ?></div>
										<div class="section-lead">
											<div class="table-responsive">
												<table class="table table-sm table-bordered table-hover text-center">
													<thead>
														<tr>
															<th><?php echo $this->lang->line("SL"); ?></th>
															<th><?php echo $this->lang->line("Host"); ?></th>
															<th><?php echo $this->lang->line("Class"); ?></th>
															<th><?php echo $this->lang->line("TTL"); ?></th>
															<th><?php echo $this->lang->line("Type"); ?></th>
															<th><?php echo $this->lang->line("PRI"); ?></th>
															<th><?php echo $this->lang->line("Target"); ?></th>
															<th><?php echo $this->lang->line("IP"); ?></th>
														</tr>
													</thead>
													
													<tbody>
														<?php 
															$sl=0;
															foreach ($dns_report as $value) 
															{
																$sl++;
																if(!isset($value["host"]))  $value["host"]="";
																if(!isset($value["class"])) $value["class"]="";
																if(!isset($value["ttl"]))   $value["ttl"]="";
																if(!isset($value["type"]))  $value["type"]="";
																if(!isset($value["pri"])) 	$value["pri"]="";
																if(!isset($value["target"]))$value["target"]="";
																if(!isset($value["ip"])) 	$value["ip"]="";
																if($value["type"]=="AAAA")
																	$value["ip"]=$value["ipv6"];
															
																echo "<tr>";
																	echo "<td>".$sl."</td>";
																	echo "<td>".$value["host"]."</td>";
																	echo "<td>".$value["class"]."</td>";
																	echo "<td>".$value["ttl"]."</td>";
																	echo "<td>".$value["type"]."</td>";
																	echo "<td>".$value["pri"]."</td>";
																	echo "<td>".$value["target"]."</td>";
																	echo "<td>".$value["ip"]."</td>";
																echo "</tr>";
															}
														?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
			<!--  ip dns end-->
	
			<!-- ip can start-->
			<div class="row">
				<div class="col-12">				
					<?php 
					$check=$site_info[0]["is_ip_canonical"]; 
					$item=$this->lang->line("IP Canonicalization Test");
					$long_recommendation=$this->lang->line('ip_canonicalization_recommendation');
					if($check=="0") //error
					{
						$class="danger";
						$status="times";
						$short_recommendation=$this->lang->line("Site failed IP canonicalization test.");
					}
					else //ok
					{
						$class="success";
						$status="check";
						$short_recommendation=$this->lang->line("Site passed IP canonicalization test.");
					}
					?>
					
					<div class="card card-<?php echo $class; ?>">
						<div class="card-header">
							<h4 class="text-<?php echo $class; ?>"><i class="fa fa-<?php echo $status;?>"></i> <?php echo $item; ?></h4>
							<div class="card-header-action"><i class="fa fa-minus minus"></i></div>
						</div>

						<div class="card-body chart-responsive minus" <?php if($compare_report == 1 && $is_pdf == 0) echo 'style="height: 300px; overflow-y:auto;"';?>>
							<div class="section-title mt-0 item-header"><?php echo $this->lang->line('Short Recommendation'); ?></div>
							<p class="section-lead short-recom"><?php echo $short_recommendation; ?></p>

							<div class="section-title mt-0 item-header" title="<?php echo $item; ?> : <?php echo $recommendation_word; ?>"><?php echo $recommendation_word; ?></div>
							<p class="section-lead alert alert-light long-recom"><?php echo $long_recommendation; ?></p>
						</div>
					</div>
				</div>
			</div>
			<!--  ip can end-->

			<!-- url can start-->
			<div class="row">
				<div class="col-12">				
					<?php 
					$check=$site_info[0]["is_url_canonicalized"]; 
					$item=$this->lang->line("URL Canonicalization Test");
					$long_recommendation=$this->lang->line('url_canonicalization_recommendation');
					if($check=="0") //error
					{
						$class="danger";
						$status="times";
						$short_recommendation=$this->lang->line("Site failed URL canonicalization test.");
					}
					else //ok
					{
						$class="success";
						$status="check";
						$short_recommendation=$this->lang->line("Site passed URL canonicalization test.");
					}
					?>

					<div class="card card-<?php echo $class; ?>">
						<div class="card-header">
							<h4 class="text-<?php echo $class; ?>"><i class="fa fa-<?php echo $status;?>"></i> <?php echo $item; ?></h4>
							<div class="card-header-action"><i class="fa fa-minus minus"></i></div>
						</div>

						<div class="card-body chart-responsive minus" <?php if($compare_report == 1 && $is_pdf == 0) echo 'style="height: 450px; overflow-y:auto;"';?>>
							<div class="section-title mt-0 item-header"><?php echo $this->lang->line('Short Recommendation'); ?></div>
							<p class="section-lead short-recom"><?php echo $short_recommendation; ?></p>

							<div class="section-title mt-0 item-header" title="<?php echo $item; ?> : <?php echo $recommendation_word; ?>"><?php echo $recommendation_word; ?></div>
							<p class="section-lead alert alert-light long-recom"><?php echo $long_recommendation; ?></p>
						</div>
					</div>
				</div>
			</div>
			<!--  url can end-->

			<!--  plain email start-->
			<div class="row">
				<div class="col-12">				
					<?php 
					$value=json_decode($site_info[0]["email_list"],true);
					$check=count($value);
					$item=$this->lang->line("Plain Text Email Test");
					$long_recommendation=$this->lang->line('plain_email_recommendation');
					if($check==0) //ok
					{
						$class="success";
						$status="check";
						$short_recommendation=$this->lang->line("Site passed plain text email test. No plain text email found.");
					}
					else //warning
					{
						$class="danger";
						$status="times";
						$short_recommendation=$this->lang->line("Site failed plain text email test.").$check.$this->lang->line("plain text email found.");
					}
					?>

					<div class="card card-<?php echo $class; ?>">
						<div class="card-header">
							<h4 class="text-<?php echo $class; ?>"><i class="fa fa-<?php echo $status;?>"></i> <?php echo $item; ?></h4>
							<div class="card-header-action"><i class="fa fa-minus minus"></i></div>
						</div>

						<div class="card-body chart-responsive minus" <?php if($compare_report == 1 && $is_pdf == 0) echo 'style="height: 600px; overflow-y:auto;"';?>>
							<div class="section-title mt-0 item-header"><?php echo $this->lang->line('Short Recommendation'); ?></div>
							<p class="section-lead short-recom"><?php echo $short_recommendation; ?></p>

							<?php if($check > 0) { ?>
							<ul class="list-group pdf-heading">
								<li class="list-group-item active"><?php echo $this->lang->line('Plain Text Email List'); ?></li>  
								<div class="heading_styles">
									<?php 
										foreach ($value as $val) {
											echo "<li class='list-group-item'>".$val."</li>";
										}
									?>
								</div>
							</ul>
							<?php } ?>
							<br>
							<div class="section-title mt-0 item-header" title="<?php echo $item; ?> : <?php echo $recommendation_word; ?>"><?php echo $recommendation_word; ?></div>
							<p class="section-lead alert alert-light long-recom"><?php echo $long_recommendation; ?></p>

						</div>
					</div>
				</div>
			</div>
			<!--   plain email end-->

			<!-- curl response start-->
			<div class="row">
				<div class="col-12">				
					<?php 
					$item=$this->lang->line("cURL Response");				
					$class="primary";
					$status="info-circle";
					
					?>
					
					<div class="card card-<?php echo $class; ?>">
						<div class="card-header bg-<?php echo $class; ?>">
							<h4 class="text-white"><i class="fa fa-<?php echo $status;?>"></i> <?php echo $item; ?></h4>
							<div class="card-header-action"><i class="fa fa-minus minus"></i></div>
						</div>

						<div class="card-body chart-responsive minus p-0" <?php if($compare_report == 1 && $is_pdf == 0) echo 'style="height: 500px; overflow-y:auto;"';?>>
							<ul class="list-group pdf-heading" style="padding:0 15px;">
								<div class="row">
									<?php $curl_response=json_decode($site_info[0]["general_curl_response"],true); ?>
									<?php $sl =0; ?>
									<?php foreach ($curl_response as $key => $value) { 
										if(is_array($value)) $value=implode(",", $value);
										$sl++;
									?>
										<div class="col-12 col-md-6 p-0">
											<li class="list-group-item rounded-0 border-top-0"><b><?php echo str_replace("_"," ",$key); ?></b> : <span style="font-size:13px;"><?php echo $value; ?></span></li>
										</div>

									<?php } ?>


								</div>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<!-- curl response end-->
		
			<div class="row">
				<div class="col-12">
					<div class="card card-primary">
						<div class="card-header">
							<h4><i class="fas fa-mobile-alt"></i> <?php echo $this->lang->line('PageSpeed Insights (Mobile)'); ?></h4>
							<div class="card-header-action">
								<a data-collapse="#mobile-collapse" href="#"><i class="fas fa-minus"></i></a>
							</div>
						</div>

						<div class="card-body" id="mobile-collapse">
							<?php 							

							   $mobile_lighthouseresult_categories = json_decode($site_info[0]['mobile_lighthouseresult_categories'],true);

							   $mobile_lighthouseresult_configsettings = json_decode($site_info[0]['mobile_lighthouseresult_configsettings'],true);

							   $mobile_loadingexperience_metrics = json_decode($site_info[0]['mobile_loadingexperience_metrics'],true);					   	
							   $mobile_originloadingexperience_metrics = json_decode($site_info[0]['mobile_originloadingexperience_metrics'],true);	

							   $mobile_lighthouseresult_audits = json_decode($site_info[0]['mobile_lighthouseresult_audits'],true);

							   $first_meaningful_paint_mobile = isset($mobile_lighthouseresult_audits['first-meaningful-paint']['score']) ? $mobile_lighthouseresult_audits['first-meaningful-paint']['score'] : 0;
							   $speed_index_mobile = isset($mobile_lighthouseresult_audits['speed-index']['score']) ? $mobile_lighthouseresult_audits['speed-index']['score'] : 0;
							   $first_cpu_idle_mobile = isset($mobile_lighthouseresult_audits['first-cpu-idle']['score']) ? $mobile_lighthouseresult_audits['first-cpu-idle']['score'] : 0;
							   $first_contentful_paint_mobile = isset($mobile_lighthouseresult_audits['first-contentful-paint']['score']) ? $mobile_lighthouseresult_audits['first-contentful-paint']['score'] : 0;
							   $interactive_mobile = isset($mobile_lighthouseresult_audits['interactive']['score']) ? $mobile_lighthouseresult_audits['interactive']['score'] : 0;

							   $mobile_score = ($first_meaningful_paint_mobile*7)+($speed_index_mobile*27)+($first_cpu_idle_mobile*13)+($first_contentful_paint_mobile*20)+($interactive_mobile*33);  				   						   
							   	
							?>
							<?php if (empty($mobile_lighthouseresult_categories)): ?>
								<div class="alert alert-warning alert-has-icon">
								  <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
								  <div class="alert-body" style="word-break: break-word">
								    <div class="alert-title"><?php echo $this->lang->line("Warning"); ?></div>
								    <?php echo isset($site_info[0]['mobile_google_api_error']) ? $site_info[0]['mobile_google_api_error'] : ""; ?><br>
								    <a target='_BLANK' href="https://console.developers.google.com/apis/library"><?php echo $this->lang->line("Enable Google PageInsights API from here"); ?></a>
								  </div>
								</div>
							<?php else: ?>
								<div class="row">
									<div class="col-12 col-md-6">						
										<p style="text-align: center;position: relative;">
										    <div style="display:inline;width:120px;height:120px;"><canvas width="120" height="120"></canvas><input type="text" class="dial knob" data-readonly="true" value="<?php echo $mobile_score; ?>" data-width="120" data-height="120" data-fgcolor="#6777ef" data-thickness=".1" readonly="readonly" style="width: 64px; height: 40px; position: absolute; vertical-align: middle; margin-top: 40px; margin-left: -92px; border: 0px; background: none; font: bold 24px Arial; text-align: center; color: rgb(103, 119, 239); padding: 0px; -webkit-appearance: none;"></div>
										</p>
										<h4 class="text-warning" style="margin-left: 21%"><?php echo $this->lang->line('Performance'); ?></h4>
									</div>
									<div class="col-12 col-md-6">
										<ul class="list-group <?php if($is_pdf == 1) echo "pdf-heading";?>">
											<div class="<?php if($is_pdf == 1) echo "heading_styles";?>" <?php if($is_pdf == 1) echo "style='height:auto'"?>>
												<li class="list-group-item">
													<?php echo $this->lang->line("Emulated Form Factor"); ?>
													<span class="badge badge-primary badge-pill">
														<?php  

															if(isset($mobile_lighthouseresult_configsettings['emulatedFormFactor']))
																echo ucwords($mobile_lighthouseresult_configsettings['emulatedFormFactor']);
														?>
															
														</span>
												</li>
												<li class="list-group-item">
													<?php echo $this->lang->line("Locale") ?>
													<span class="badge badge-primary badge-pill">
														<?php 
															if(isset($mobile_lighthouseresult_configsettings['locale']))
																echo ucwords($mobile_lighthouseresult_configsettings['locale']);
														 ?>
													</span>
												</li>									
												<li class="list-group-item">
													<?php echo $this->lang->line("Category") ?>
													<span class="badge badge-primary badge-pill">
														<?php 
															if(isset($mobile_lighthouseresult_configsettings['onlyCategories'][0]))
																echo ucwords($mobile_lighthouseresult_configsettings['onlyCategories'][0]);
														 ?>
													</span>
												</li>
											</div>
										</ul>
									</div>
								</div>
								<div class="row mt-5">
									<div class="<?php if($compare_report == 1) echo "col-12"; else echo "col-12 col-md-8"; ?> ">
										<ul class="list-group pdf-heading">
											<li class="list-group-item active"><?php echo $this->lang->line("Field Data"); ?>
												<i data-description="<h2 class='section-title'><?php echo $this->lang->line('Field Data'); ?></h2> <p style='font-size: 12px;'><?php echo $this->lang->line('Over the last 30 days, the field data shows that this page has an <b>Moderate</b> speed compared to other pages in the') ?> <b><a target='_BLANK' href='https://developers.google.com/web/tools/chrome-user-experience-report/'></b> <?php echo $this->lang->line('Chrome User Experience Report') ?></a>. <?php echo $this->lang->line('We are showing') ?> <b> <a target='_BLANK' href='https://developers.google.com/speed/docs/insights/v5/about#faq'><?php echo $this->lang->line('the 75th percentile of FCP') ?></b> <b></a> and <a target='_BLANK' href='https://developers.google.com/speed/docs/insights/v5/about#faq'><?php echo $this->lang->line('the 95th percentile of FID') ?></a></b></p>" class="fas fa-info-circle field_data_modal" style="color: #fff;"></i>
											</li>  
											<div class="heading_styles" style="height: auto;">
												<li class="list-group-item">
													<?php echo $this->lang->line('First Contentful Paint (FCP)'); ?>
													<span class="badge badge-primary badge-pill">
													   <?php 
													   if(isset($mobile_loadingexperience_metrics['metrics']['FIRST_CONTENTFUL_PAINT_MS']['percentile'])) {
                                                           echo $mobile_loadingexperience_metrics['metrics']['FIRST_CONTENTFUL_PAINT_MS']['percentile'] . ' ms';
                                                       } else {
													       echo 'N/A';
                                                       }
													    ?>
													        
													</span>
												</li>
												<li class="list-group-item">
													<?php echo $this->lang->line('FCP Metric Category'); ?>
													<span class="badge badge-primary badge-pill">
													    <?php 
													    if(isset($mobile_loadingexperience_metrics['metrics']['FIRST_CONTENTFUL_PAINT_MS']['category'])) {
                                                            echo $mobile_loadingexperience_metrics['metrics']['FIRST_CONTENTFUL_PAINT_MS']['category'];
                                                        } else {
                                                            echo 'N/A';
                                                        }
													     ?>    
													</span>
												</li>
												<li class="list-group-item">
													<?php echo $this->lang->line('First Input Delay (FID)'); ?>
													<span class="badge badge-primary badge-pill">
													   <?php 

													   if(isset($mobile_loadingexperience_metrics['metrics']['FIRST_INPUT_DELAY_MS']['percentile'])) {
                                                           echo $mobile_loadingexperience_metrics['metrics']['FIRST_INPUT_DELAY_MS']['percentile'] . ' ms';
                                                       } else {
                                                           echo 'N/A';
                                                       }
													    ?>
													        
													</span>
												</li>
												<li class="list-group-item">
													<?php echo $this->lang->line('FID Metric Category'); ?>
													<span class="badge badge-primary badge-pill">
													   <?php 

													   if(isset($mobile_loadingexperience_metrics['metrics']['FIRST_INPUT_DELAY_MS']['category'])) {
                                                           echo $mobile_loadingexperience_metrics['metrics']['FIRST_INPUT_DELAY_MS']['category'];
                                                       } else {
                                                           echo 'N/A';
                                                       }
													    ?>
													        
													</span>
												</li>
												<li class="list-group-item">
													<?php echo $this->lang->line('Overall Category'); ?>
													<span class="badge badge-primary badge-pill">
													    <?php 
													    if(isset($mobile_loadingexperience_metrics['overall_category'])) {
                                                            echo $mobile_loadingexperience_metrics['overall_category'];
                                                        } else {
                                                            echo 'N/A';
                                                        }
													     ?>
													        
													</span>
												</li>
											</div>
										</ul>
									</div>
									<div class="<?php if($compare_report ==1) echo "col-12 mt-5"; else echo "col-12 col-md-4 pl-4"; ?> ">
										<div 
											<?php 
												$bgpos = '';
												if($is_pdf == 1) $bgpos = "background-position: top center;text-align:center;padding-left:0 !important;";

												if($compare_report ==1) echo 'style="padding-left:12px;height:530px;background: url('.base_url("assets/images/mobile.png").') no-repeat !important; '.$bgpos.'"'; 
												else echo 'style="padding-left:12px;height:530px;background: url('.base_url("assets/images/mobile.png").') no-repeat !important; '.$bgpos.'"'; 
											?> 
										>
											<?php 
																	
											if(isset($mobile_lighthouseresult_audits['final-screenshot']['details']['data']))
											{

												echo '<img src="'.$mobile_lighthouseresult_audits['final-screenshot']['details']['data'].'" width="225px" style="margin-top:52px;display: inline-block;">';
											} 

											?>
										</div>
									</div>
								</div>
								<div class="row mt-4">
									<div class="<?php if($compare_report ==1) echo "col-12"; else echo "col-12 col-md-6" ?>">
		                                <ul class="list-group pdf-heading">
		                                    <li class="list-group-item active"> <?php echo $this->lang->line('Origin Summary'); ?> <i data-description="<h2 class='section-title'><?php echo $this->lang->line('Origin Summary Data'); ?></h2><p style='font-size: 12px;'> <?php echo $this->lang->line('All pages served from this origin have a <b>Slow</b> speed compared to other pages in the'); ?> <a target='_BLANK' href='https://developers.google.com/web/tools/chrome-user-experience-report/'><?php echo $this->lang->line('Chrome User Experience Report') ?></a> <?php echo $this->lang->line('over the last 30 days.To view suggestions tailored to each page, analyze individual page URLs.') ?></p>" class="fas fa-info-circle field_data_modal" style="color: #fff;"></i>
		                                    </li>
		                                    <div class="heading_styles" style="height: auto;">
		                                    	<li class="list-group-item">
		                                    	    <?php echo $this->lang->line('First Contentful Paint (FCP)'); ?>
		                                    	    <span class="badge badge-primary badge-pill">
		                                    	        <?php 
		                                    	        if(isset($mobile_originloadingexperience_metrics['metrics']['FIRST_CONTENTFUL_PAINT_MS']['percentile'])) {
                                                            echo $mobile_originloadingexperience_metrics['metrics']['FIRST_CONTENTFUL_PAINT_MS']['percentile'] . ' ms';
                                                        } else {
                                                            echo 'N/A';
                                                        }
		                                    	         ?>
		                                    	            
		                                    	    </span>
		                                    	</li>
		                                    	<li class="list-group-item">
		                                    	    <?php echo $this->lang->line('FCP Metric Category'); ?>
		                                    	    <span class="badge badge-primary badge-pill">
		                                    	        <?php 
		                                    	        if(isset($mobile_originloadingexperience_metrics['metrics']['FIRST_CONTENTFUL_PAINT_MS']['category'])) {
                                                            echo $mobile_originloadingexperience_metrics['metrics']['FIRST_CONTENTFUL_PAINT_MS']['category'];
                                                        } else {
                                                            echo 'N/A';
                                                        }
		                                    	         ?>  
		                                    	    </span>
		                                    	</li>
		                                    	<li class="list-group-item">
		                                    	    <?php echo $this->lang->line('First Input Delay (FID)'); ?>
		                                    	    <span class="badge badge-primary badge-pill">
		                                    	       <?php 

		                                    	       if(isset($mobile_originloadingexperience_metrics['metrics']['FIRST_INPUT_DELAY_MS']['percentile'])) {
                                                           echo $mobile_originloadingexperience_metrics['metrics']['FIRST_INPUT_DELAY_MS']['percentile'] . ' ms';
                                                       } else {
                                                           echo 'N/A';
                                                       }
		                                    	        ?>
		                                    	            
		                                    	    </span>
		                                    	</li>                                    
		                                    	<li class="list-group-item">
		                                    	    <?php echo $this->lang->line('FID Metric Category'); ?>
		                                    	    <span class="badge badge-primary badge-pill">
		                                    	       <?php 
		                                    	       if(isset($mobile_originloadingexperience_metrics['metrics']['FIRST_INPUT_DELAY_MS']['category'])) {
                                                           echo $mobile_originloadingexperience_metrics['metrics']['FIRST_INPUT_DELAY_MS']['category'];
                                                       } else {
                                                           echo 'N/A';
                                                       }
		                                    	        ?>
		                                    	            
		                                    	    </span>
		                                    	</li>                                    
		                                    	<li class="list-group-item">
		                                    	    <?php echo $this->lang->line('Overall Category'); ?>
		                                    	    <span class="badge badge-primary badge-pill">
		                                    	        <?php 
		                                    	        if(isset($mobile_originloadingexperience_metrics['overall_category'])) {
                                                            echo $mobile_originloadingexperience_metrics['overall_category'];
                                                        } else {
                                                            echo 'N/A';
                                                        }
		                                    	         ?>
		                                    	            
		                                    	    </span>
		                                    	</li>
		                                    </div>
		                                </ul>

									</div>
									<div class="<?php if($compare_report ==1) echo "col-12 mt-5"; else echo "col-12 col-md-6" ?>">
		                                <ul class="list-group pdf-heading">
		                                    <li class="list-group-item active"> <?php echo $this->lang->line('Lab Data'); ?> 
		                                    </li>
		                                    <div class="heading_styles" style="height:auto">
		                                    	<li class="list-group-item">
		                                    	    <?php echo $this->lang->line('First Contentful Paint'); ?><i data-description="<h2 class='section-title'><?php echo $this->lang->line('First Contentful Paint'); ?></h2> <p style='font-size: 12px;line-height: initial;'><?php echo $this->lang->line('First Contentful Paint marks the time at which the first text or image is painted.'); ?> <b><a target='_BLANK' class='text-danger' href='https://web.dev/first-contentful-paint/?utm_source=lighthouse&utm_medium=unknown'><?php echo $this->lang->line('Learn more'); ?></a></b> </p>" class="fas fa-info-circle field_data_modal" style="margin-right: 282px;"></i>
		                                    	    <span class="badge badge-primary badge-pill">
		                                    	        <?php 
		                                    	        if(isset($mobile_lighthouseresult_audits['first-contentful-paint']['displayValue']))
		                                    	            echo $mobile_lighthouseresult_audits['first-contentful-paint']['displayValue'];
		                                    	         ?>
		                                    	            
		                                    	    </span>
		                                    	</li>
		                                    	<li class="list-group-item">
		                                    	    <?php echo $this->lang->line('First Meaningful Paint'); ?><i data-description="<h2 class='section-title'><?php echo $this->lang->line('First Meaningful Paint'); ?></h2>
		                                    	        <p style='font-size: 12px;line-height: initial;'><?php echo $this->lang->line('First Meaningful Paint measures when the primary content of a page is visible.'); ?> <b><a target='_BLANK' class='text-danger' href='https://web.dev/first-meaningful-paint?utm_source=lighthouse&utm_medium=unknown'><?php echo $this->lang->line('Learn more'); ?></a></b> </p>" class="fas fa-info-circle field_data_modal" style="margin-right: 282px;"></i>
		                                    	    <span class="badge badge-primary badge-pill">
		                                    	        <?php 
		                                    	        if(isset($mobile_lighthouseresult_audits['first-meaningful-paint']['displayValue']))
		                                    	            echo $mobile_lighthouseresult_audits['first-meaningful-paint']['displayValue'];
		                                    	        ?>
		                                    	        
		                                    	    </span>
		                                    	</li>
		                                    	<li class="list-group-item">
		                                    	    <?php echo $this->lang->line('Speed Index'); ?> <i data-description="<h2 class='section-title'><?php echo $this->lang->line('Speed Index'); ?></h2>
		                                    	    <p style='font-size: 12px;line-height: initial;'><?php echo $this->lang->line('Speed Index shows how quickly the contents of a page are visibly populated.'); ?> <b><a target='_BLANK' class='text-danger' href='https://web.dev/speed-index?utm_source=lighthouse&utm_medium=unknown'><?php echo $this->lang->line('Learn more'); ?></a></b> </p>" class="fas fa-info-circle field_data_modal" style="margin-right: 330px"></i>
		                                    	    <span class="badge badge-primary badge-pill">
		                                    	       <?php 

		                                    	       if(isset($mobile_lighthouseresult_audits['speed-index']['displayValue']))
		                                    	         echo $mobile_lighthouseresult_audits['speed-index']['displayValue'];
		                                    	        ?>
		                                    	            
		                                    	    </span>
		                                    	</li>                                    
		                                    	<li class="list-group-item">
		                                    	    <?php echo $this->lang->line('First CPU Idle'); ?> <i data-description="<h2 class='section-title'><?php echo $this->lang->line('First CPU Idle'); ?></h2>
		                                    	    <p style='font-size: 12px;line-height: initial;'><?php echo $this->lang->line('First CPU Idle marks the first time at which the page main thread is quiet enough to handle input.'); ?> <b><a target='_BLANK' class='text-danger' href='https://web.dev/first-cpu-idle?utm_source=lighthouse&utm_medium=unknown'><?php echo $this->lang->line('Learn more'); ?></a></b> </p>" class="fas fa-info-circle field_data_modal" style="margin-right: 320"></i>
		                                    	    <span class="badge badge-primary badge-pill">
		                                    	       <?php 
		                                    	       if(isset($mobile_lighthouseresult_audits['first-cpu-idle']['displayValue']))
		                                    	           echo $mobile_lighthouseresult_audits['first-cpu-idle']['displayValue'];
		                                    	        ?>
		                                    	            
		                                    	    </span>
		                                    	</li>                                    
		                                    	<li class="list-group-item">
		                                    	    <?php echo $this->lang->line('Time to Interactive'); ?> <i class="fas fa-info-circle field_data_modal" style="margin-right: 290px;" data-description="<h2 class='section-title'><?php echo $this->lang->line('Time to Interactive'); ?></h2>
		                                    	    <p style='font-size: 12px;line-height: initial;'><?php echo $this->lang->line('Time to interactive is the amount of time it takes for the page to become fully interactive.'); ?> <b><a target='_BLANK' class='text-danger' href='https://web.dev/interactive/?utm_source=lighthouse&utm_medium=unknown'><?php echo $this->lang->line('Learn more'); ?></a></b> </p>"></i>
		                                    	    <span class="badge badge-primary badge-pill">
		                                    	        <?php 
		                                    	        if(isset($mobile_lighthouseresult_audits['interactive']['displayValue']))
		                                    	            echo $mobile_lighthouseresult_audits['interactive']['displayValue'];
		                                    	         ?>
		                                    	            
		                                    	    </span>
		                                    	</li>                                    

		                                    	<li class="list-group-item">
		                                    	    <?php echo $this->lang->line('Max Potential First Input Delay'); ?> <i class="fas fa-info-circle field_data_modal" style="margin-right: 200px;" data-description="<h2 class='section-title'><?php echo $this->lang->line('Max Potential First Input Delay'); ?></h2>
		                                    	        <p style='font-size: 12px;line-height: initial;'><?php echo $this->lang->line('The maximum potential First Input Delay that your users could experience is the duration, in milliseconds, of the longest task.'); ?> <b><a target='_BLANK' class='text-danger' href='https://web.dev/fid/'><?php echo $this->lang->line('Learn more'); ?></a></b> </p>"></i>
		                                    	    <span class="badge badge-primary badge-pill">
		                                    	        <?php 
		                                    	        if(isset($mobile_lighthouseresult_audits['max-potential-fid']['displayValue']))
		                                    	            echo $mobile_lighthouseresult_audits['max-potential-fid']['displayValue'];
		                                    	         ?>
		                                    	            
		                                    	    </span>
		                                    	</li>
		                                    </div>
		                                </ul>
									</div>
								</div>
								<div class="row mt-5">
									<div class="col-12">
									    <div class="card card-primary">
									        <div class="card-header">
									            <h4 class="text-white" style="color: #6777ef!important;"><?php echo $this->lang->line("Audit Data") ?></h4>
									            <div class="card-header-action"><i class="fa fa-minus minus"></i></div>
									        </div>

									          <div class="card-body chart-responsive minus p-0">
												<div class="row mt-5">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($mobile_lighthouseresult_audits['resource-summary']['title']))
																	    echo $mobile_lighthouseresult_audits['resource-summary']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($mobile_lighthouseresult_audits['resource-summary']['displayValue']))
												                		echo $mobile_lighthouseresult_audits['resource-summary']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($mobile_lighthouseresult_audits['resource-summary']['description'])){

												                $resource_sum = explode('[',$mobile_lighthouseresult_audits['resource-summary']['description']);

												                echo '<p>'.$resource_sum[0].'<b><a class="text-danger" target="_BLANK" href="https://developers.google.com/web/tools/lighthouse/audits/budgets">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>												
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($mobile_lighthouseresult_audits['time-to-first-byte']['title']))
																	    echo $mobile_lighthouseresult_audits['time-to-first-byte']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($mobile_lighthouseresult_audits['time-to-first-byte']['displayValue']))
												                		echo $mobile_lighthouseresult_audits['time-to-first-byte']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($mobile_lighthouseresult_audits['time-to-first-byte']['description'])){

												                $time_to_first_byte = explode('[',$mobile_lighthouseresult_audits['time-to-first-byte']['description']);

												                echo '<p>'.$time_to_first_byte[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/time-to-first-byte">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>												
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($mobile_lighthouseresult_audits['render-blocking-resources']['title']))
																	    echo $mobile_lighthouseresult_audits['render-blocking-resources']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($mobile_lighthouseresult_audits['render-blocking-resources']['displayValue']))
												                		echo $mobile_lighthouseresult_audits['render-blocking-resources']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($mobile_lighthouseresult_audits['render-blocking-resources']['description'])){

												                $render_blocking = explode('[',$mobile_lighthouseresult_audits['render-blocking-resources']['description']);

												                echo '<p>'.$render_blocking[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/render-blocking-resources">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>												
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($mobile_lighthouseresult_audits['uses-optimized-images']['title']))
																	    echo $mobile_lighthouseresult_audits['uses-optimized-images']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($mobile_lighthouseresult_audits['uses-optimized-images']['displayValue']))
												                		echo $mobile_lighthouseresult_audits['uses-optimized-images']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($mobile_lighthouseresult_audits['uses-optimized-images']['description'])){

												                $render_blocking = explode('[',$mobile_lighthouseresult_audits['uses-optimized-images']['description']);

												                echo '<p>'.$render_blocking[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/uses-optimized-images">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>												
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($mobile_lighthouseresult_audits['uses-text-compression']['title']))
																	    echo $mobile_lighthouseresult_audits['uses-text-compression']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($mobile_lighthouseresult_audits['uses-text-compression']['displayValue']))
												                		echo $mobile_lighthouseresult_audits['uses-text-compression']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($mobile_lighthouseresult_audits['uses-text-compression']['description'])){

												                $text_compresseion = explode('[',$mobile_lighthouseresult_audits['uses-text-compression']['description']);

												                echo '<p>'.$text_compresseion[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/uses-text-compression">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>												
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($mobile_lighthouseresult_audits['uses-long-cache-ttl']['title']))
																	    echo $mobile_lighthouseresult_audits['uses-long-cache-ttl']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($mobile_lighthouseresult_audits['uses-long-cache-ttl']['displayValue']))
												                		echo $mobile_lighthouseresult_audits['uses-long-cache-ttl']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($mobile_lighthouseresult_audits['uses-long-cache-ttl']['description'])){

												                $uses_long_cache = explode('[',$mobile_lighthouseresult_audits['uses-long-cache-ttl']['description']);

												                echo '<p>'.$uses_long_cache[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/uses-long-cache-ttl">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>												
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($mobile_lighthouseresult_audits['third-party-summary']['title']))
																	    echo $mobile_lighthouseresult_audits['third-party-summary']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($mobile_lighthouseresult_audits['third-party-summary']['displayValue']))
												                		echo $mobile_lighthouseresult_audits['third-party-summary']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($mobile_lighthouseresult_audits['third-party-summary']['description'])){

												                $third_party_summary = explode('[',$mobile_lighthouseresult_audits['third-party-summary']['description']);

												                echo '<p>'.$third_party_summary[0].'<b><a class="text-danger" target="_BLANK" href="https://developers.google.com/web/fundamentals/performance/optimizing-content-efficiency/loading-third-party-javascript">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>												
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($mobile_lighthouseresult_audits['estimated-input-latency']['title']))
																	    echo $mobile_lighthouseresult_audits['estimated-input-latency']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($mobile_lighthouseresult_audits['estimated-input-latency']['displayValue']))
												                		echo $mobile_lighthouseresult_audits['estimated-input-latency']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($mobile_lighthouseresult_audits['estimated-input-latency']['description'])){

												                $third_party_summary = explode('[',$mobile_lighthouseresult_audits['estimated-input-latency']['description']);

												                echo '<p>'.$third_party_summary[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/estimated-input-latency">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>												
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($mobile_lighthouseresult_audits['first-contentful-paint-3g']['title']))
																	    echo $mobile_lighthouseresult_audits['first-contentful-paint-3g']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($mobile_lighthouseresult_audits['first-contentful-paint-3g']['displayValue']))
												                		echo $mobile_lighthouseresult_audits['first-contentful-paint-3g']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($mobile_lighthouseresult_audits['first-contentful-paint-3g']['description'])){

												                $fcp3g = explode('[',$mobile_lighthouseresult_audits['first-contentful-paint-3g']['description']);

												                echo '<p>'.$fcp3g[0].'<b><a class="text-danger" target="_BLANK" href="https://developers.google.com/web/tools/lighthouse/audits/first-contentful-paint">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>												
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($mobile_lighthouseresult_audits['total-blocking-time']['title']))
																	    echo $mobile_lighthouseresult_audits['total-blocking-time']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($mobile_lighthouseresult_audits['total-blocking-time']['displayValue']))
												                		echo $mobile_lighthouseresult_audits['total-blocking-time']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($mobile_lighthouseresult_audits['total-blocking-time']['description'])){

												                $total_blocking_time1 = explode('[',$mobile_lighthouseresult_audits['total-blocking-time']['description']);

												                echo '<p>'.$total_blocking_time1[0].'</p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($mobile_lighthouseresult_audits['bootup-time']['title']))
																	    echo $mobile_lighthouseresult_audits['bootup-time']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($mobile_lighthouseresult_audits['bootup-time']['displayValue']))
												                		echo $mobile_lighthouseresult_audits['bootup-time']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($mobile_lighthouseresult_audits['bootup-time']['description'])){

												                $boottime = explode('[',$mobile_lighthouseresult_audits['bootup-time']['description']);

												                echo '<p>'.$boottime[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/bootup-time">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>													
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($mobile_lighthouseresult_audits['offscreen-images']['title']))
																	    echo $mobile_lighthouseresult_audits['offscreen-images']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($mobile_lighthouseresult_audits['offscreen-images']['displayValue']))
												                		echo $mobile_lighthouseresult_audits['offscreen-images']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($mobile_lighthouseresult_audits['offscreen-images']['description'])){

												                $offscreen_des = explode('[',$mobile_lighthouseresult_audits['offscreen-images']['description']);

												                echo '<p>'.$offscreen_des[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/offscreen-images">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>												
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($mobile_lighthouseresult_audits['network-server-latency']['title']))
																	    echo $mobile_lighthouseresult_audits['network-server-latency']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($mobile_lighthouseresult_audits['network-server-latency']['displayValue']))
												                		echo $mobile_lighthouseresult_audits['network-server-latency']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($mobile_lighthouseresult_audits['network-server-latency']['description'])){

												                $network_server_lat = explode('[',$mobile_lighthouseresult_audits['network-server-latency']['description']);

												                echo '<p>'.$network_server_lat[0].'<b><a class="text-danger" target="_BLANK" href="https://hpbn.co/primer-on-web-performance/#analyzing-the-resource-waterfall">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>												
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($mobile_lighthouseresult_audits['uses-responsive-images']['title']))
																	    echo $mobile_lighthouseresult_audits['uses-responsive-images']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($mobile_lighthouseresult_audits['uses-responsive-images']['displayValue']))
												                		echo $mobile_lighthouseresult_audits['uses-responsive-images']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($mobile_lighthouseresult_audits['uses-responsive-images']['description'])){

												                $uses_responsive = explode('[',$mobile_lighthouseresult_audits['uses-responsive-images']['description']);

												                echo '<p>'.$uses_responsive[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/uses-responsive-images?utm_source=lighthouse&utm_medium=unknown">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>												
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($mobile_lighthouseresult_audits['unused-css-rules']['title']))
																	    echo $mobile_lighthouseresult_audits['unused-css-rules']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($mobile_lighthouseresult_audits['unused-css-rules']['displayValue']))
												                		echo $mobile_lighthouseresult_audits['unused-css-rules']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($mobile_lighthouseresult_audits['unused-css-rules']['description'])){

												                $unused_css = explode('[',$mobile_lighthouseresult_audits['unused-css-rules']['description']);

												                echo '<p>'.$unused_css[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/unused-css-rules">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>													
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($mobile_lighthouseresult_audits['total-byte-weight']['title']))
																	    echo $mobile_lighthouseresult_audits['total-byte-weight']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($mobile_lighthouseresult_audits['total-byte-weight']['displayValue']))
												                		echo $mobile_lighthouseresult_audits['total-byte-weight']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($mobile_lighthouseresult_audits['total-byte-weight']['description'])){

												                $total_byte = explode('[',$mobile_lighthouseresult_audits['total-byte-weight']['description']);

												                echo '<p>'.$total_byte[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/total-byte-weight">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>													
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($mobile_lighthouseresult_audits['mainthread-work-breakdown']['title']))
																	    echo $mobile_lighthouseresult_audits['mainthread-work-breakdown']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($mobile_lighthouseresult_audits['mainthread-work-breakdown']['displayValue']))
												                		echo $mobile_lighthouseresult_audits['mainthread-work-breakdown']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($mobile_lighthouseresult_audits['mainthread-work-breakdown']['description'])){

												                $mainthred_work = explode('[',$mobile_lighthouseresult_audits['mainthread-work-breakdown']['description']);

												                echo '<p>'.$mainthred_work[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/mainthread-work-breakdown">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>													
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($mobile_lighthouseresult_audits['uses-webp-images']['title']))
																	    echo $mobile_lighthouseresult_audits['uses-webp-images']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($mobile_lighthouseresult_audits['uses-webp-images']['displayValue']))
												                		echo $mobile_lighthouseresult_audits['uses-webp-images']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($mobile_lighthouseresult_audits['uses-webp-images']['description'])){

												                $uses_web_images = explode('[',$mobile_lighthouseresult_audits['uses-webp-images']['description']);

												                echo '<p>'.$uses_web_images[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/uses-webp-images">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>													
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($mobile_lighthouseresult_audits['critical-request-chains']['title']))
																	    echo $mobile_lighthouseresult_audits['critical-request-chains']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($mobile_lighthouseresult_audits['critical-request-chains']['displayValue']))
												                		echo $mobile_lighthouseresult_audits['critical-request-chains']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($mobile_lighthouseresult_audits['critical-request-chains']['description'])){

												                $critical_request_chains = explode('[',$mobile_lighthouseresult_audits['critical-request-chains']['description']);

												                echo '<p>'.$critical_request_chains[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/critical-request-chains">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>													
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($mobile_lighthouseresult_audits['dom-size']['title']))
																	    echo $mobile_lighthouseresult_audits['dom-size']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($mobile_lighthouseresult_audits['dom-size']['displayValue']))
												                		echo $mobile_lighthouseresult_audits['dom-size']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($mobile_lighthouseresult_audits['dom-size']['description'])){

												                $dom_size1 = explode('[',$mobile_lighthouseresult_audits['dom-size']['description']);

												                echo '<p>'.$dom_size1[0].'<b><a class="text-danger" target="_BLANK" href="https://developers.google.com/web/fundamentals/performance/rendering/reduce-the-scope-and-complexity-of-style-calculations">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>												
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($mobile_lighthouseresult_audits['redirects']['title']))
																	    echo $mobile_lighthouseresult_audits['redirects']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($mobile_lighthouseresult_audits['redirects']['displayValue']))
												                		echo $mobile_lighthouseresult_audits['redirects']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($mobile_lighthouseresult_audits['redirects']['description'])){

												                $redirects_des = explode('[',$mobile_lighthouseresult_audits['redirects']['description']);

												                echo '<p>'.$redirects_des[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/redirects">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>													
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($mobile_lighthouseresult_audits['unminified-javascript']['title']))
																	    echo $mobile_lighthouseresult_audits['unminified-javascript']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($mobile_lighthouseresult_audits['unminified-javascript']['displayValue']))
												                		echo $mobile_lighthouseresult_audits['unminified-javascript']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($mobile_lighthouseresult_audits['unminified-javascript']['description'])){

												                $unminified_js = explode('[',$mobile_lighthouseresult_audits['unminified-javascript']['description']);

												                echo '<p>'.$unminified_js[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/unminified-javascript">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>													
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($mobile_lighthouseresult_audits['user-timings']['title']))
																	    echo $mobile_lighthouseresult_audits['user-timings']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($mobile_lighthouseresult_audits['user-timings']['displayValue']))
												                		echo $mobile_lighthouseresult_audits['user-timings']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($mobile_lighthouseresult_audits['user-timings']['description'])){

												                $user_times = explode('[',$mobile_lighthouseresult_audits['user-timings']['description']);

												                echo '<p>'.$user_times[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/user-timings">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>													
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($mobile_lighthouseresult_audits['network-rtt']['title']))
																	    echo $mobile_lighthouseresult_audits['network-rtt']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($mobile_lighthouseresult_audits['network-rtt']['displayValue']))
												                		echo $mobile_lighthouseresult_audits['network-rtt']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($mobile_lighthouseresult_audits['network-rtt']['description'])){

												                $network_rtt = explode('[',$mobile_lighthouseresult_audits['network-rtt']['description']);

												                echo '<p>'.$network_rtt[0].'<b><a class="text-danger" target="_BLANK" href="https://hpbn.co/primer-on-latency-and-bandwidth/">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>	
									          </div>
									     </div>
									</div>
								</div>

							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>			
			<div class="row">
				<div class="col-12">
					<div class="card card-primary">
						<div class="card-header">
							<h4><i class="fas fa-desktop"></i> <?php echo $this->lang->line('PageSpeed Insights (Desktop)'); ?></h4>
							<div class="card-header-action">
								<a data-collapse="#mobile-collapse" href="#"><i class="fas fa-minus"></i></a>
							</div>
						</div>

						<div class="card-body" id="mobile-collapse">
							<?php 							

							   $desktop_lighthouseresult_categories = json_decode($site_info[0]['desktop_lighthouseresult_categories'],true);

							   $desktop_lighthouseresult_configsettings = json_decode($site_info[0]['desktop_lighthouseresult_configsettings'],true);

							   $desktop_loadingexperience_metrics = json_decode($site_info[0]['desktop_loadingexperience_metrics'],true);					   	
							   $desktop_originloadingexperience_metrics = json_decode($site_info[0]['desktop_originloadingexperience_metrics'],true);	

							   $desktop_lighthouseresult_audits = json_decode($site_info[0]['desktop_lighthouseresult_audits'],true);

							   $first_meaningful_paint_desktop = isset($desktop_lighthouseresult_audits['first-meaningful-paint']['score']) ? $desktop_lighthouseresult_audits['first-meaningful-paint']['score'] : 0;
							   $speed_index_desktop = isset($desktop_lighthouseresult_audits['speed-index']['score']) ? $desktop_lighthouseresult_audits['speed-index']['score'] : 0;
							   $first_cpu_idle_desktop = isset($desktop_lighthouseresult_audits['first-cpu-idle']['score']) ? $desktop_lighthouseresult_audits['first-cpu-idle']['score'] : 0;
							   $first_contentful_paint_desktop = isset($desktop_lighthouseresult_audits['first-contentful-paint']['score']) ? $desktop_lighthouseresult_audits['first-contentful-paint']['score'] : 0;
							   $interactive_desktop = isset($desktop_lighthouseresult_audits['interactive']['score']) ? $desktop_lighthouseresult_audits['interactive']['score'] : 0;

							   $desktop_score = ($first_meaningful_paint_desktop*7)+($speed_index_desktop*27)+($first_cpu_idle_desktop*13)+($first_contentful_paint_desktop*20)+($interactive_desktop*33);  				   						   
							   	
							?>
							<?php if (empty($desktop_lighthouseresult_categories)): ?>
								<div class="alert alert-warning alert-has-icon">
								  <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
								  <div class="alert-body" style="word-break: break-word">
								    <div class="alert-title"><?php echo $this->lang->line("Warning"); ?></div>
								    <?php echo isset($site_info[0]['desktop_google_api_error']) ? $site_info[0]['mobile_google_api_error'] : ""; ?><br>
								    <a target='_BLANK' href="https://console.developers.google.com/apis/library"><?php echo $this->lang->line("Enable Google PageInsights API from here"); ?></a>
								  </div>
								</div>
							<?php else: ?>
								<div class="row">
									<div class="col-12 col-md-6">						
										<p style="text-align: center;position: relative;">
										    <div style="display:inline;width:120px;height:120px;"><canvas width="120" height="120"></canvas><input type="text" class="dial knob" data-readonly="true" value="<?php echo $desktop_score; ?>" data-width="120" data-height="120" data-fgcolor="#6777ef" data-thickness=".1" readonly="readonly" style="width: 64px; height: 40px; position: absolute; vertical-align: middle; margin-top: 40px; margin-left: -92px; border: 0px; background: none; font: bold 24px Arial; text-align: center; color: rgb(103, 119, 239); padding: 0px; -webkit-appearance: none;"></div>
										</p>
										<h4 class="text-warning" style="margin-left: 21%"><?php echo $this->lang->line('Performance'); ?></h4>
									</div>
									<div class="col-12 col-md-6">
										<ul class="list-group pdf-heading">
											<div class="heading_styles">
												<li class="list-group-item">
													<?php echo $this->lang->line("Emulated Form Factor"); ?>
													<span class="badge badge-primary badge-pill">
														<?php  

															if(isset($desktop_lighthouseresult_configsettings['emulatedFormFactor']))
																echo ucwords($desktop_lighthouseresult_configsettings['emulatedFormFactor']);
														?>
															
														</span>
												</li>
												<li class="list-group-item">
													<?php echo $this->lang->line("Locale") ?>
													<span class="badge badge-primary badge-pill">
														<?php 
															if(isset($desktop_lighthouseresult_configsettings['locale']))
																echo ucwords($desktop_lighthouseresult_configsettings['locale']);
														 ?>
													</span>
												</li>									
												<li class="list-group-item">
													<?php echo $this->lang->line("Category") ?>
													<span class="badge badge-primary badge-pill">
														<?php 
															if(isset($desktop_lighthouseresult_configsettings['onlyCategories'][0]))
																echo ucwords($desktop_lighthouseresult_configsettings['onlyCategories'][0]);
														 ?>
													</span>
												</li>
											</div>
										</ul>
									</div>
								</div>
								<div class="row mt-5">
									<div class="col-12 col-md-6">
		                                <ul class="list-group pdf-heading">
		                                    <li class="list-group-item active"> <?php echo $this->lang->line('Field Data'); ?> <i data-description="<h2 class='section-title'><?php echo $this->lang->line('Field Data'); ?></h2> <p style='font-size: 12px;'><?php echo $this->lang->line('Over the last 30 days, the field data shows that this page has an <b>Moderate</b> speed compared to other pages in the') ?> <b><a target='_BLANK' href='https://developers.google.com/web/tools/chrome-user-experience-report/'></b> <?php echo $this->lang->line('Chrome User Experience Report') ?></a>. <?php echo $this->lang->line('We are showing') ?> <b> <a target='_BLANK' href='https://developers.google.com/speed/docs/insights/v5/about#faq'><?php echo $this->lang->line('the 75th percentile of FCP') ?></b> <b></a> and <a target='_BLANK' href='https://developers.google.com/speed/docs/insights/v5/about#faq'><?php echo $this->lang->line('the 95th percentile of FID') ?></a></b></p>" class="fas fa-info-circle field_data_modal" style="color: #fff;"></i>
		                                    </li>
		                                    <div class="heading_styles" style="height: auto;">
		                                    	<li class="list-group-item">
		                                    	    <?php echo $this->lang->line('First Contentful Paint (FCP)'); ?>
		                                    	    <span class="badge badge-primary badge-pill">
		                                    	       <?php 
		                                    	       if(isset($desktop_loadingexperience_metrics['metrics']['FIRST_CONTENTFUL_PAINT_MS']['percentile'])) {
                                                           echo $desktop_loadingexperience_metrics['metrics']['FIRST_CONTENTFUL_PAINT_MS']['percentile'] . ' ms';
                                                       }else {
                                                           echo 'N/A';
                                                       }
		                                    	        ?>
		                                    	            
		                                    	    </span>
		                                    	</li>
		                                    	<li class="list-group-item">
		                                    	    <?php echo $this->lang->line('FCP Metric Category'); ?>
		                                    	    <span class="badge badge-primary badge-pill">
		                                    	        <?php 
		                                    	        if(isset($desktop_loadingexperience_metrics['metrics']['FIRST_CONTENTFUL_PAINT_MS']['category'])) {
                                                            echo $desktop_loadingexperience_metrics['metrics']['FIRST_CONTENTFUL_PAINT_MS']['category'];
                                                        }else {
                                                            echo 'N/A';
                                                        }
		                                    	         ?>    
		                                    	    </span>
		                                    	</li>
		                                    	<li class="list-group-item">
		                                    	    <?php echo $this->lang->line('First Input Delay (FID)'); ?>
		                                    	    <span class="badge badge-primary badge-pill">
		                                    	       <?php 

		                                    	       if(isset($desktop_loadingexperience_metrics['metrics']['FIRST_INPUT_DELAY_MS']['percentile'])) {
                                                           echo $desktop_loadingexperience_metrics['metrics']['FIRST_INPUT_DELAY_MS']['percentile'] . ' ms';
                                                       }else {
                                                           echo 'N/A';
                                                       }
		                                    	        ?>
		                                    	            
		                                    	    </span>
		                                    	</li>                                    
		                                    	<li class="list-group-item">
		                                    	    <?php echo $this->lang->line('FID Metric Category'); ?>
		                                    	    <span class="badge badge-primary badge-pill">
		                                    	       <?php 

		                                    	       if(isset($desktop_loadingexperience_metrics['metrics']['FIRST_INPUT_DELAY_MS']['category'])) {
                                                           echo $desktop_loadingexperience_metrics['metrics']['FIRST_INPUT_DELAY_MS']['category'];
                                                       }else {
                                                           echo 'N/A';
                                                       }
		                                    	        ?>
		                                    	            
		                                    	    </span>
		                                    	</li>                                    
		                                    	<li class="list-group-item">
		                                    	    <?php echo $this->lang->line('Overall Category'); ?>
		                                    	    <span class="badge badge-primary badge-pill">
		                                    	        <?php 
		                                    	        if(isset($desktop_loadingexperience_metrics['overall_category'])) {
                                                            echo $desktop_loadingexperience_metrics['overall_category'];
                                                        }else {
                                                            echo 'N/A';
                                                        }
		                                    	         ?>
		                                    	            
		                                    	    </span>
		                                    	</li>
		                                    </div>
		                                </ul>
									</div>
									<div class="col-12 col-md-6 pl-4">
										<?php 
																
										if(isset($desktop_lighthouseresult_audits['final-screenshot']['details']['data']))
										{

											echo '<img src="'.$desktop_lighthouseresult_audits['final-screenshot']['details']['data'].'" class="img-thumbnail">';
										} 

										?>
									</div>
								</div>
								<div class="row mt-5">
									<div class="<?php if($compare_report ==1) echo "col-12"; else echo "col-12 col-md-6" ?>">
		                                <ul class="list-group pdf-heading">
		                                    <li class="list-group-item active"> <?php echo $this->lang->line('Origin Summary'); ?> <i data-description="<h2 class='section-title'><?php echo $this->lang->line('Origin Summary Data'); ?></h2><p style='font-size: 12px;'> <?php echo $this->lang->line('All pages served from this origin have a <b>Slow</b> speed compared to other pages in the'); ?> <a target='_BLANK' href='https://developers.google.com/web/tools/chrome-user-experience-report/'><?php echo $this->lang->line('Chrome User Experience Report') ?></a> <?php echo $this->lang->line('over the last 30 days.To view suggestions tailored to each page, analyze individual page URLs.') ?></p>" class="fas fa-info-circle field_data_modal" style="color: #fff;"></i>
		                                    </li>
		                                    <div class="heading_styles" style="height:auto;">
		                                    	<li class="list-group-item">
		                                    	    <?php echo $this->lang->line('First Contentful Paint (FCP)'); ?>
		                                    	    <span class="badge badge-primary badge-pill">
		                                    	        <?php 
		                                    	        if(isset($desktop_originloadingexperience_metrics['metrics']['FIRST_CONTENTFUL_PAINT_MS']['percentile'])) {
                                                            echo $desktop_originloadingexperience_metrics['metrics']['FIRST_CONTENTFUL_PAINT_MS']['percentile'] . ' ms';
                                                        } else {
		                                    	            echo 'N/A';
                                                        }
		                                    	         ?>
		                                    	            
		                                    	    </span>
		                                    	</li>
		                                    	<li class="list-group-item">
		                                    	    <?php echo $this->lang->line('FCP Metric Category'); ?>
		                                    	    <span class="badge badge-primary badge-pill">
		                                    	        <?php 
		                                    	        if(isset($desktop_originloadingexperience_metrics['metrics']['FIRST_CONTENTFUL_PAINT_MS']['category'])) {
                                                            echo $desktop_originloadingexperience_metrics['metrics']['FIRST_CONTENTFUL_PAINT_MS']['category'];
                                                        }else {
                                                            echo 'N/A';
                                                        }
		                                    	         ?>  
		                                    	    </span>
		                                    	</li>
		                                    	<li class="list-group-item">
		                                    	    <?php echo $this->lang->line('First Input Delay (FID)'); ?>
		                                    	    <span class="badge badge-primary badge-pill">
		                                    	       <?php 

		                                    	       if(isset($desktop_originloadingexperience_metrics['metrics']['FIRST_INPUT_DELAY_MS']['percentile'])) {
                                                           echo $desktop_originloadingexperience_metrics['metrics']['FIRST_INPUT_DELAY_MS']['percentile'] . ' ms';
                                                       } else {
                                                           echo 'N/A';
                                                       }
		                                    	        ?>
		                                    	            
		                                    	    </span>
		                                    	</li>                                    
		                                    	<li class="list-group-item">
		                                    	    <?php echo $this->lang->line('FID Metric Category'); ?>
		                                    	    <span class="badge badge-primary badge-pill">
		                                    	       <?php 
		                                    	       if(isset($desktop_originloadingexperience_metrics['metrics']['FIRST_INPUT_DELAY_MS']['category'])) {
                                                           echo $desktop_originloadingexperience_metrics['metrics']['FIRST_INPUT_DELAY_MS']['category'];
                                                       }else {
                                                           echo 'N/A';
                                                       }
		                                    	        ?>
		                                    	            
		                                    	    </span>
		                                    	</li>                                    
		                                    	<li class="list-group-item">
		                                    	    <?php echo $this->lang->line('Overall Category'); ?>
		                                    	    <span class="badge badge-primary badge-pill">
		                                    	        <?php 
		                                    	        if(isset($desktop_originloadingexperience_metrics['overall_category'])) {
                                                            echo $desktop_originloadingexperience_metrics['overall_category'];
                                                        }else {
                                                            echo 'N/A';
                                                        }
		                                    	         ?>
		                                    	            
		                                    	    </span>
		                                    	</li>
		                                    </div>
		                                </ul>

									</div>
									<div class="<?php if($compare_report ==1) echo "col-12 mt-5"; else echo "col-12 col-md-6"; ?>">
		                                <ul class="list-group pdf-heading">
		                                    <li class="list-group-item active"> <?php echo $this->lang->line('Lab Data'); ?> 
		                                    </li>
		                                    <div class="heading_styles" style="height:auto;">
		                                    	<li class="list-group-item">
		                                    	    <?php echo $this->lang->line('First Contentful Paint'); ?><i data-description="<h2 class='section-title'><?php echo $this->lang->line('First Contentful Paint'); ?></h2> <p style='font-size: 12px;line-height: initial;'><?php echo $this->lang->line('First Contentful Paint marks the time at which the first text or image is painted.'); ?> <b><a target='_BLANK' class='text-danger' href='https://web.dev/first-contentful-paint/?utm_source=lighthouse&utm_medium=unknown'><?php echo $this->lang->line('Learn more'); ?></a></b> </p>" class="fas fa-info-circle field_data_modal" style="margin-right: 282px;"></i>
		                                    	    <span class="badge badge-primary badge-pill">
		                                    	        <?php 
		                                    	        if(isset($desktop_lighthouseresult_audits['first-contentful-paint']['displayValue']))
		                                    	            echo $desktop_lighthouseresult_audits['first-contentful-paint']['displayValue'];
		                                    	         ?>
		                                    	            
		                                    	    </span>
		                                    	</li>
		                                    	<li class="list-group-item">
		                                    	    <?php echo $this->lang->line('First Meaningful Paint'); ?><i data-description="<h2 class='section-title'><?php echo $this->lang->line('First Meaningful Paint'); ?></h2>
		                                    	        <p style='font-size: 12px;line-height: initial;'><?php echo $this->lang->line('First Meaningful Paint measures when the primary content of a page is visible.'); ?> <b><a target='_BLANK' class='text-danger' href='https://web.dev/first-meaningful-paint?utm_source=lighthouse&utm_medium=unknown'><?php echo $this->lang->line('Learn more'); ?></a></b> </p>" class="fas fa-info-circle field_data_modal" style="margin-right: 282px;"></i>
		                                    	    <span class="badge badge-primary badge-pill">
		                                    	        <?php 
		                                    	        if(isset($desktop_lighthouseresult_audits['first-meaningful-paint']['displayValue']))
		                                    	            echo $desktop_lighthouseresult_audits['first-meaningful-paint']['displayValue'];
		                                    	        ?>
		                                    	        
		                                    	    </span>
		                                    	</li>
		                                    	<li class="list-group-item">
		                                    	    <?php echo $this->lang->line('Speed Index'); ?> <i data-description="<h2 class='section-title'><?php echo $this->lang->line('Speed Index'); ?></h2>
		                                    	    <p style='font-size: 12px;line-height: initial;'><?php echo $this->lang->line('Speed Index shows how quickly the contents of a page are visibly populated.'); ?> <b><a target='_BLANK' class='text-danger' href='https://web.dev/speed-index?utm_source=lighthouse&utm_medium=unknown'><?php echo $this->lang->line('Learn more'); ?></a></b> </p>" class="fas fa-info-circle field_data_modal" style="margin-right: 330px"></i>
		                                    	    <span class="badge badge-primary badge-pill">
		                                    	       <?php 

		                                    	       if(isset($desktop_lighthouseresult_audits['speed-index']['displayValue']))
		                                    	         echo $desktop_lighthouseresult_audits['speed-index']['displayValue'];
		                                    	        ?>
		                                    	            
		                                    	    </span>
		                                    	</li>                                    
		                                    	<li class="list-group-item">
		                                    	    <?php echo $this->lang->line('First CPU Idle'); ?> <i data-description="<h2 class='section-title'><?php echo $this->lang->line('First CPU Idle'); ?></h2>
		                                    	    <p style='font-size: 12px;line-height: initial;'><?php echo $this->lang->line('First CPU Idle marks the first time at which the page main thread is quiet enough to handle input.'); ?> <b><a target='_BLANK' class='text-danger' href='https://web.dev/first-cpu-idle?utm_source=lighthouse&utm_medium=unknown'><?php echo $this->lang->line('Learn more'); ?></a></b> </p>" class="fas fa-info-circle field_data_modal" style="margin-right: 320"></i>
		                                    	    <span class="badge badge-primary badge-pill">
		                                    	       <?php 
		                                    	       if(isset($desktop_lighthouseresult_audits['first-cpu-idle']['displayValue']))
		                                    	           echo $desktop_lighthouseresult_audits['first-cpu-idle']['displayValue'];
		                                    	        ?>
		                                    	            
		                                    	    </span>
		                                    	</li>                                    
		                                    	<li class="list-group-item">
		                                    	    <?php echo $this->lang->line('Time to Interactive'); ?> <i class="fas fa-info-circle field_data_modal" style="margin-right: 290px;" data-description="<h2 class='section-title'><?php echo $this->lang->line('Time to Interactive'); ?></h2>
		                                    	    <p style='font-size: 12px;line-height: initial;'><?php echo $this->lang->line('Time to interactive is the amount of time it takes for the page to become fully interactive.'); ?> <b><a target='_BLANK' class='text-danger' href='https://web.dev/interactive/?utm_source=lighthouse&utm_medium=unknown'><?php echo $this->lang->line('Learn more'); ?></a></b> </p>"></i>
		                                    	    <span class="badge badge-primary badge-pill">
		                                    	        <?php 
		                                    	        if(isset($desktop_lighthouseresult_audits['interactive']['displayValue']))
		                                    	            echo $desktop_lighthouseresult_audits['interactive']['displayValue'];
		                                    	         ?>
		                                    	            
		                                    	    </span>
		                                    	</li>                                    

		                                    	<li class="list-group-item">
		                                    	    <?php echo $this->lang->line('Max Potential First Input Delay'); ?> <i class="fas fa-info-circle field_data_modal" style="margin-right: 200px;" data-description="<h2 class='section-title'><?php echo $this->lang->line('Max Potential First Input Delay'); ?></h2>
		                                    	        <p style='font-size: 12px;line-height: initial;'><?php echo $this->lang->line('The maximum potential First Input Delay that your users could experience is the duration, in milliseconds, of the longest task.'); ?> <b><a target='_BLANK' class='text-danger' href='https://web.dev/fid/'><?php echo $this->lang->line('Learn more'); ?></a></b> </p>"></i>
		                                    	    <span class="badge badge-primary badge-pill">
		                                    	        <?php 
		                                    	        if(isset($desktop_lighthouseresult_audits['max-potential-fid']['displayValue']))
		                                    	            echo $desktop_lighthouseresult_audits['max-potential-fid']['displayValue'];
		                                    	         ?>
		                                    	            
		                                    	    </span>
		                                    	</li>
		                                    </div>
		                                </ul>
									</div>
								</div>
								<div class="row mt-5">
									<div class="col-12">
									    <div class="card card-primary">
									        <div class="card-header">
									            <h4 class="text-white" style="color: #6777ef!important;"><?php echo $this->lang->line("Audit Data") ?></h4>
									            <div class="card-header-action"><i class="fa fa-minus minus"></i></div>
									        </div>

									        <div class="card-body chart-responsive minus p-0">
												<div class="row mt-5">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($desktop_lighthouseresult_audits['resource-summary']['title']))
																	    echo $desktop_lighthouseresult_audits['resource-summary']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($desktop_lighthouseresult_audits['resource-summary']['displayValue']))
												                		echo $desktop_lighthouseresult_audits['resource-summary']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($desktop_lighthouseresult_audits['resource-summary']['description'])){

												                $resource_sum = explode('[',$desktop_lighthouseresult_audits['resource-summary']['description']);

												                echo '<p>'.$resource_sum[0].'<b><a class="text-danger" target="_BLANK" href="https://developers.google.com/web/tools/lighthouse/audits/budgets">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>												
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($desktop_lighthouseresult_audits['time-to-first-byte']['title']))
																	    echo $desktop_lighthouseresult_audits['time-to-first-byte']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($desktop_lighthouseresult_audits['time-to-first-byte']['displayValue']))
												                		echo $desktop_lighthouseresult_audits['time-to-first-byte']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($desktop_lighthouseresult_audits['time-to-first-byte']['description'])){

												                $time_to_first_byte = explode('[',$desktop_lighthouseresult_audits['time-to-first-byte']['description']);

												                echo '<p>'.$time_to_first_byte[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/time-to-first-byte">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>												
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($desktop_lighthouseresult_audits['render-blocking-resources']['title']))
																	    echo $desktop_lighthouseresult_audits['render-blocking-resources']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($desktop_lighthouseresult_audits['render-blocking-resources']['displayValue']))
												                		echo $desktop_lighthouseresult_audits['render-blocking-resources']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($desktop_lighthouseresult_audits['render-blocking-resources']['description'])){

												                $render_blocking = explode('[',$desktop_lighthouseresult_audits['render-blocking-resources']['description']);

												                echo '<p>'.$render_blocking[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/render-blocking-resources">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>												
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($desktop_lighthouseresult_audits['uses-optimized-images']['title']))
																	    echo $desktop_lighthouseresult_audits['uses-optimized-images']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($desktop_lighthouseresult_audits['uses-optimized-images']['displayValue']))
												                		echo $desktop_lighthouseresult_audits['uses-optimized-images']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($desktop_lighthouseresult_audits['uses-optimized-images']['description'])){

												                $render_blocking = explode('[',$desktop_lighthouseresult_audits['uses-optimized-images']['description']);

												                echo '<p>'.$render_blocking[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/uses-optimized-images">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>												
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($desktop_lighthouseresult_audits['uses-text-compression']['title']))
																	    echo $desktop_lighthouseresult_audits['uses-text-compression']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($desktop_lighthouseresult_audits['uses-text-compression']['displayValue']))
												                		echo $desktop_lighthouseresult_audits['uses-text-compression']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($desktop_lighthouseresult_audits['uses-text-compression']['description'])){

												                $text_compresseion = explode('[',$desktop_lighthouseresult_audits['uses-text-compression']['description']);

												                echo '<p>'.$text_compresseion[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/uses-text-compression">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>												
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($desktop_lighthouseresult_audits['uses-long-cache-ttl']['title']))
																	    echo $desktop_lighthouseresult_audits['uses-long-cache-ttl']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($desktop_lighthouseresult_audits['uses-long-cache-ttl']['displayValue']))
												                		echo $desktop_lighthouseresult_audits['uses-long-cache-ttl']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($desktop_lighthouseresult_audits['uses-long-cache-ttl']['description'])){

												                $uses_long_cache = explode('[',$desktop_lighthouseresult_audits['uses-long-cache-ttl']['description']);

												                echo '<p>'.$uses_long_cache[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/uses-long-cache-ttl">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>												
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($desktop_lighthouseresult_audits['third-party-summary']['title']))
																	    echo $desktop_lighthouseresult_audits['third-party-summary']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($desktop_lighthouseresult_audits['third-party-summary']['displayValue']))
												                		echo $desktop_lighthouseresult_audits['third-party-summary']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($desktop_lighthouseresult_audits['third-party-summary']['description'])){

												                $third_party_summary = explode('[',$desktop_lighthouseresult_audits['third-party-summary']['description']);

												                echo '<p>'.$third_party_summary[0].'<b><a class="text-danger" target="_BLANK" href="https://developers.google.com/web/fundamentals/performance/optimizing-content-efficiency/loading-third-party-javascript">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>												
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($desktop_lighthouseresult_audits['estimated-input-latency']['title']))
																	    echo $desktop_lighthouseresult_audits['estimated-input-latency']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($desktop_lighthouseresult_audits['estimated-input-latency']['displayValue']))
												                		echo $desktop_lighthouseresult_audits['estimated-input-latency']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($desktop_lighthouseresult_audits['estimated-input-latency']['description'])){

												                $third_party_summary = explode('[',$desktop_lighthouseresult_audits['estimated-input-latency']['description']);

												                echo '<p>'.$third_party_summary[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/estimated-input-latency">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>												
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($desktop_lighthouseresult_audits['first-contentful-paint-3g']['title']))
																	    echo $desktop_lighthouseresult_audits['first-contentful-paint-3g']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($desktop_lighthouseresult_audits['first-contentful-paint-3g']['displayValue']))
												                		echo $desktop_lighthouseresult_audits['first-contentful-paint-3g']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($desktop_lighthouseresult_audits['first-contentful-paint-3g']['description'])){

												                $fcp3g = explode('[',$desktop_lighthouseresult_audits['first-contentful-paint-3g']['description']);

												                echo '<p>'.$fcp3g[0].'<b><a class="text-danger" target="_BLANK" href="https://developers.google.com/web/tools/lighthouse/audits/first-contentful-paint">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>												
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($desktop_lighthouseresult_audits['total-blocking-time']['title']))
																	    echo $desktop_lighthouseresult_audits['total-blocking-time']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($desktop_lighthouseresult_audits['total-blocking-time']['displayValue']))
												                		echo $desktop_lighthouseresult_audits['total-blocking-time']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($desktop_lighthouseresult_audits['total-blocking-time']['description'])){

												                $total_blocking_time1 = explode('[',$desktop_lighthouseresult_audits['total-blocking-time']['description']);

												                echo '<p>'.$total_blocking_time1[0].'</p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($desktop_lighthouseresult_audits['bootup-time']['title']))
																	    echo $desktop_lighthouseresult_audits['bootup-time']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($desktop_lighthouseresult_audits['bootup-time']['displayValue']))
												                		echo $desktop_lighthouseresult_audits['bootup-time']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($desktop_lighthouseresult_audits['bootup-time']['description'])){

												                $boottime = explode('[',$desktop_lighthouseresult_audits['bootup-time']['description']);

												                echo '<p>'.$boottime[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/bootup-time">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>													
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($desktop_lighthouseresult_audits['offscreen-images']['title']))
																	    echo $desktop_lighthouseresult_audits['offscreen-images']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($desktop_lighthouseresult_audits['offscreen-images']['displayValue']))
												                		echo $desktop_lighthouseresult_audits['offscreen-images']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($desktop_lighthouseresult_audits['offscreen-images']['description'])){

												                $offscreen_des = explode('[',$desktop_lighthouseresult_audits['offscreen-images']['description']);

												                echo '<p>'.$offscreen_des[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/offscreen-images">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>												
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($desktop_lighthouseresult_audits['network-server-latency']['title']))
																	    echo $desktop_lighthouseresult_audits['network-server-latency']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($desktop_lighthouseresult_audits['network-server-latency']['displayValue']))
												                		echo $desktop_lighthouseresult_audits['network-server-latency']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($desktop_lighthouseresult_audits['network-server-latency']['description'])){

												                $network_server_lat = explode('[',$desktop_lighthouseresult_audits['network-server-latency']['description']);

												                echo '<p>'.$network_server_lat[0].'<b><a class="text-danger" target="_BLANK" href="https://hpbn.co/primer-on-web-performance/#analyzing-the-resource-waterfall">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>												
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($desktop_lighthouseresult_audits['uses-responsive-images']['title']))
																	    echo $desktop_lighthouseresult_audits['uses-responsive-images']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($desktop_lighthouseresult_audits['uses-responsive-images']['displayValue']))
												                		echo $desktop_lighthouseresult_audits['uses-responsive-images']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($desktop_lighthouseresult_audits['uses-responsive-images']['description'])){

												                $uses_responsive = explode('[',$desktop_lighthouseresult_audits['uses-responsive-images']['description']);

												                echo '<p>'.$uses_responsive[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/uses-responsive-images?utm_source=lighthouse&utm_medium=unknown">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>												
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($desktop_lighthouseresult_audits['unused-css-rules']['title']))
																	    echo $desktop_lighthouseresult_audits['unused-css-rules']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($desktop_lighthouseresult_audits['unused-css-rules']['displayValue']))
												                		echo $desktop_lighthouseresult_audits['unused-css-rules']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($desktop_lighthouseresult_audits['unused-css-rules']['description'])){

												                $unused_css = explode('[',$desktop_lighthouseresult_audits['unused-css-rules']['description']);

												                echo '<p>'.$unused_css[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/unused-css-rules">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>													
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($desktop_lighthouseresult_audits['total-byte-weight']['title']))
																	    echo $desktop_lighthouseresult_audits['total-byte-weight']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($desktop_lighthouseresult_audits['total-byte-weight']['displayValue']))
												                		echo $desktop_lighthouseresult_audits['total-byte-weight']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($desktop_lighthouseresult_audits['total-byte-weight']['description'])){

												                $total_byte = explode('[',$desktop_lighthouseresult_audits['total-byte-weight']['description']);

												                echo '<p>'.$total_byte[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/total-byte-weight">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>													
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($desktop_lighthouseresult_audits['mainthread-work-breakdown']['title']))
																	    echo $desktop_lighthouseresult_audits['mainthread-work-breakdown']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($desktop_lighthouseresult_audits['mainthread-work-breakdown']['displayValue']))
												                		echo $desktop_lighthouseresult_audits['mainthread-work-breakdown']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($desktop_lighthouseresult_audits['mainthread-work-breakdown']['description'])){

												                $mainthred_work = explode('[',$desktop_lighthouseresult_audits['mainthread-work-breakdown']['description']);

												                echo '<p>'.$mainthred_work[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/mainthread-work-breakdown">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>													
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($desktop_lighthouseresult_audits['uses-webp-images']['title']))
																	    echo $desktop_lighthouseresult_audits['uses-webp-images']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($desktop_lighthouseresult_audits['uses-webp-images']['displayValue']))
												                		echo $desktop_lighthouseresult_audits['uses-webp-images']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($desktop_lighthouseresult_audits['uses-webp-images']['description'])){

												                $uses_web_images = explode('[',$desktop_lighthouseresult_audits['uses-webp-images']['description']);

												                echo '<p>'.$uses_web_images[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/uses-webp-images">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>													
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($desktop_lighthouseresult_audits['critical-request-chains']['title']))
																	    echo $desktop_lighthouseresult_audits['critical-request-chains']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($desktop_lighthouseresult_audits['critical-request-chains']['displayValue']))
												                		echo $desktop_lighthouseresult_audits['critical-request-chains']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($desktop_lighthouseresult_audits['critical-request-chains']['description'])){

												                $critical_request_chains = explode('[',$desktop_lighthouseresult_audits['critical-request-chains']['description']);

												                echo '<p>'.$critical_request_chains[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/critical-request-chains">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>													
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($desktop_lighthouseresult_audits['dom-size']['title']))
																	    echo $desktop_lighthouseresult_audits['dom-size']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($desktop_lighthouseresult_audits['dom-size']['displayValue']))
												                		echo $desktop_lighthouseresult_audits['dom-size']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($desktop_lighthouseresult_audits['dom-size']['description'])){

												                $dom_size1 = explode('[',$desktop_lighthouseresult_audits['dom-size']['description']);

												                echo '<p>'.$dom_size1[0].'<b><a class="text-danger" target="_BLANK" href="https://developers.google.com/web/fundamentals/performance/rendering/reduce-the-scope-and-complexity-of-style-calculations">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>												
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($desktop_lighthouseresult_audits['redirects']['title']))
																	    echo $desktop_lighthouseresult_audits['redirects']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($desktop_lighthouseresult_audits['redirects']['displayValue']))
												                		echo $desktop_lighthouseresult_audits['redirects']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($desktop_lighthouseresult_audits['redirects']['description'])){

												                $redirects_des = explode('[',$desktop_lighthouseresult_audits['redirects']['description']);

												                echo '<p>'.$redirects_des[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/redirects">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>													
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($desktop_lighthouseresult_audits['unminified-javascript']['title']))
																	    echo $desktop_lighthouseresult_audits['unminified-javascript']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($desktop_lighthouseresult_audits['unminified-javascript']['displayValue']))
												                		echo $desktop_lighthouseresult_audits['unminified-javascript']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($desktop_lighthouseresult_audits['unminified-javascript']['description'])){

												                $unminified_js = explode('[',$desktop_lighthouseresult_audits['unminified-javascript']['description']);

												                echo '<p>'.$unminified_js[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/unminified-javascript">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>													
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($desktop_lighthouseresult_audits['user-timings']['title']))
																	    echo $desktop_lighthouseresult_audits['user-timings']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($desktop_lighthouseresult_audits['user-timings']['displayValue']))
												                		echo $desktop_lighthouseresult_audits['user-timings']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($desktop_lighthouseresult_audits['user-timings']['description'])){

												                $user_times = explode('[',$desktop_lighthouseresult_audits['user-timings']['description']);

												                echo '<p>'.$user_times[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/user-timings">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>													
												<div class="row mt-3">
												    <div class="col-12">
												        <div class="card card-success">
												            <div class="card-header">
												                <h4 class="text-success"><i class="fa fa-check"></i> 
																	<?php 
																	 if(isset($desktop_lighthouseresult_audits['network-rtt']['title']))
																	    echo $desktop_lighthouseresult_audits['network-rtt']['title']; 
																	 ?>
												                </h4>
												                <div class="card-header-action">
												                	<code>
												                		<?php 
												                		if(isset($desktop_lighthouseresult_audits['network-rtt']['displayValue']))
												                		echo $desktop_lighthouseresult_audits['network-rtt']['displayValue'];
												                		 ?>
												                	</code>
												                </div>
												            </div>

												            <div class="card-body chart-responsive minus">
															  <?php

												                if(isset($desktop_lighthouseresult_audits['network-rtt']['description'])){

												                $network_rtt = explode('[',$desktop_lighthouseresult_audits['network-rtt']['description']);

												                echo '<p>'.$network_rtt[0].'<b><a class="text-danger" target="_BLANK" href="https://hpbn.co/primer-on-latency-and-bandwidth/">'.$this->lang->line("Learn More").'</a></b></p>';
												                }

												                ?>            
												            </div>
												        </div>
												    </div>
												</div>	
									        </div>
									     </div>
									</div>
								</div>

							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>

		</section>

	</div>
	


<script>
    $(function() {
        $(".dial").knob();
    });
</script>

<?php if($load_css_js!=1) { ?>
<script>
	$j(document).ready(function(){
	    $('[data-toggle="popover"]').popover(); 

	    $(".minus").click(function() {
	    	$(this).parent().parent().next(".box-body").toggle();
		});

	    $(".minus").click(function() {
	    	$(this).parent().parent().next(".card-body").toggle();
		});
		$(".recommendation_link").click(function() {
	    	$(this).next(".recommendation").toggle();
		});

		$(document).on('click','#download_list',function(){
			var direct_download="<?php echo $direct_download;?>";

			if(direct_download=="1") 
			{			
				// $("#subscribe_div").html('<div class="box-body chart-responsive minus"><div class="col-12"><div style="font-size:18px" class="alert text-center"><img class="center-block" src="<?php echo site_url();?>assets/pre-loader/Fading squares.gif" alt="Processing..."><br/><?php echo $this->lang->line("this may take a while to generate pdf"); ?> </div></div></div>');

				$("#subscribe_div").html('<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center" style="font-size:60px"></i></div><div class="text-center"><?php echo $this->lang->line("this may take a while to generate pdf"); ?> </div>');
				$("#subscribe_div").addClass("alert alert-light");
				// $("#subscribe_div").css("background","#f1f1f1");
				$("#subscribe_div").show();
				var hidden_id=$("#hidden_id").val();
				// $(this).addClass('btn-progress');
				var base_url="<?php echo site_url();?>";
				$.ajax({
				url:base_url+'sitedoctor/direct_download',
				type:'POST',
				data:{hidden_id:hidden_id},
				success:function(response){
					$("#subscribe_div").html(response);
				  }
			   });	
			}
		});


	});
</script>

<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-36251023-1']);
  _gaq.push(['_setDomainName', 'jqueryscript.net']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
<?php } ?>
<?php if($compare_report == 0 && $load_css_js==1) echo "</body></html>";?>

<script type="text/javascript">
  $("document").ready(function(){

    $(document).on('click','.field_data_modal',function(e){
        e.preventDefault();
        $("#field_data_modal").modal();
        var data_description = $(this).attr("data-description");
        $('.modal_value').html(data_description);
      });    
    
  });
</script>

<div class="modal" id="field_data_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #fff;">
        <h5 class="modal-title"> <i class="fab fa-google"></i> <?php echo $this->lang->line("Google PageSpeed Insights");?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#000!important;">
          <span aria-hidden="true">×</span>
        </button>
      </div>

      <div class="modal-body">    
        <div class="section modal_value">                
            

        </div>
      </div>
      <div class="modal-footer">
              <a style="margin-right: 131px;" class="btn btn-outline-secondary" data-dismiss="modal"><i class="fas fa-times"></i> <?php echo $this->lang->line("Close") ?></a>
      </div>
    </div>
  </div>
</div>