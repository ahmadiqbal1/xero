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
	echo "<input type='hidden' value='".$comparision_info[0]["id"]."' id='hidden_id'/>";		
	?>


	<?php 
		$path = 'assets/img/logo.png';
		$type = pathinfo($path, PATHINFO_EXTENSION);
		$data = file_get_contents($path);
		if($load_css_js==1) 
		{			
			$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
			echo "<a style='text-decoration:none;' href='".base_url()."'><img style='margin-left:242px;max-width:200px;' src='".$base64."' alt='".$this->config->item("institute_address1")."'></a</p>>";
			echo "<p align='center'>Powered by <a style='text-align:center;text-decoration:none;' href='".base_url()."'>".base_url()."</a>"." (".$this->config->item("institute_address1").")";		
			echo '<br><br>';
		}
		else
		{
			$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
			echo "<br><br><a href='".base_url()."'><img style='max-width:200px;' class='center-block' src='".$base64."' alt='".$this->config->item("institute_address1")."'></a></p>";			
			echo "<p align='center'>Powered by <a style='text-align:center;text-decoration:none;' href='".base_url()."'>".base_url()."</a>"." (".$this->config->item("institute_address1").")";			
			echo '<div class="space"></div>';
		}
	?>


	<?php 

		$headline=$this->lang->line("health report");
		$searched_at=$this->lang->line('examined at');
	?>

	<h4 id="" align="center"><?php echo $headline; ?> : <a href="<?php echo $site_info[0]["domain_name"]; ?>"  target="_BLANK"><?php echo $site_info[0]["domain_name"]; ?></a> Vs. <a href="<?php echo $site_info2[0]["domain_name"]; ?>"  target="_BLANK"><?php echo $site_info2[0]["domain_name"]; ?></a></h4>
	<p align="center"> <?php echo $searched_at." : ".$comparision_info[0]["searched_at"]; ?></p> 
	

	<div class="container-fluid boss-container">
		<?php if($load_css_js!=1) {?>
			<div class="row">
				<div class="col-xs-12 col-sm-12 share-container text-center" style="margin-bottom:50px;"><?php include("application/modules/sitedoctor/views/share_button.php");?></div>

				<div class="col-xs-12 col-sm-12 col-lg-2 col-md-2 share-sibing text-center">
					<a class="btn btn-lg btn-info" href="<?php echo site_url("sitedoctor/comparative_check_report/");?>"> <i class="fa fa-arrow-circle-left"></i> <?php echo $this->lang->line("go back");?></a>
				</div>

				<div class="col-xs-12 col-sm-12 col-lg-2 col-md-2 col-md-offset-8 col-lg-offset-8 share-sibing text-center">
					<a target="_blank" href="<?php echo base_url("sitedoctor/comparision_report_pdf/".$comparision_info[0]['id']); ?>" class="btn btn-lg btn-success"> <i class="fas fa-cloud-download-alt"></i> <?php echo $this->lang->line("download pdf");?></a>
				</div>
				<div class="col-xs-12" id="subscribe_div" style="display:none;"></div>			
			</div>
			<hr style="margin:0 0 20px 0">
		<?php } ?>


		<div class="row">
			<div class="col-xs-12 col-md-6">
				<?php include("application/modules/sitedoctor/views/report.php");; ?>

			</div>
			<div class="col-xs-12 col-md-6">
				<?php $site_info = $site_info2; ?>
				<?php include("application/modules/sitedoctor/views/report.php");; ?>
			</div>
		</div>
	</div>


	<?php if($load_css_js!=1) { ?>
		<script>
		    $(function() {
		        $(".dial").knob();
		    });
		</script>

		<script>
		$(document).ready(function(){
		    $('[data-toggle="popover"]').popover(); 

		    $(".minus").click(function() {
		    	$(this).parent().parent().next(".card-body").toggle();
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

<?php if($load_css_js==1) echo "</body></html>";?>