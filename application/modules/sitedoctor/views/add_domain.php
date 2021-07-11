<?php 
	if($this->session->userdata('trans_error') == 1) 
	{
		echo "<div class='alert alert-danger text-center'><h4 style='margin:0;'><i class='fa fa-remove'></i> ".$this->lang->line("your data has been failed to stored into the database.")."</h4></div>";
		$this->session->unset_userdata('trans_error');
	}
?>
<style>
	#copy_button {
		background: white;
        color: black;
        padding-left: 5px;
        padding-right: 5px;
        margin-top: -15px;
        margin-right: -15px;
	}

	#copy_button:hover {
		cursor: pointer;
		background: orange;
		color: blue;
	}
</style>

<?php 

	if($compare == 1) {

		$red_uri = "sitedoctor/comparative_check_report";
		$bred_text = $this->lang->line("Comparative Health Report");
		$btn_lang=$this->lang->line('Compare');
		$head_lang=$this->lang->line('Type Competitor web address');
		
	} else {

		$red_uri = "sitedoctor/checked_website_lists";
		$bred_text = $this->lang->line("Website Health Report");
		$btn_lang=$this->lang->line('Check');
		$head_lang=$this->lang->line('Type web address');
	}

?>

<section class="section section_custom">
	<div class="section-header">
		<h1><i class="fas fa-stethoscope"></i> <?php echo $page_title; ?></h1>
		<div class="section-header-breadcrumb">
			<div class="breadcrumb-item"><a href="<?php echo base_url("menu_loader/analysis_tools"); ?>"><?php echo $this->lang->line("Analysis Tools"); ?></a></div>
			<div class="breadcrumb-item">
				<a href="<?php echo base_url($red_uri) ?>"><?php echo $bred_text; ?></a>
			</div>
			<div class="breadcrumb-item"><?php echo $page_title; ?></div>
		</div>
	</div>

	<div class="section-body">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-body">
						<div class="row">
						    <div class="col-12">
						        <div class="form-group">
						            <div class="input-group mb-3">
						                <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-signature"></i></div></div>
						                <?php $direct_site = isset($direct_site_check_url) ? $direct_site_check_url : "";?>
						                <input type="text" class="form-control" value="<?php echo $direct_site;?>" placeholder="<?php echo $head_lang ?>" id="page_search" name="page_search">
						                <div class="input-group-append">
						                    <button class="btn btn-primary float-left" id="search"><i class="fas fa-hourglass-half"></i> <?php echo $btn_lang; ?></button>
						                </div>
						            </div>
						        </div>
						    </div>
						</div>
					</div>

					<div class="row" id="result" style="padding:0 25px 20px 25px;margin-bottom: 30px;">
						<div class="col-12 text-center">
						    <span style="font-size: 18px!important;font-weight:bold" id="domain_name_show"></span>
						</div>
						<div class="col-12 text-center" id="domain_success_msg"></div> 
						<div class="col-12 text-center" id="progress_msg">
							<b><span id="domain_progress_msg_text"></span></b>
							<div class="progress" style="display: none;height: 22px;" id="domain_progress_bar_con"> 
								<div style="width:3%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="3" role="progressbar" class="progress-bar progress-bar-info progress-bar-striped progress-bar-animated"><b><span>1%</span></b></div> 
							</div>
						</div>	
					</div>
			</div>
		</div>
	</div>
</section>


<script type="text/javascript">

	$(document).ready(function(){

		// if($('#page_search').val() != "")
		// 	$("#search").click();
	});

	$("#result").hide();
	
	var interval="";

	function get_bulk_progress()
	{
		var base_url="<?php echo base_url(); ?>";			
		$.ajax({
			url:base_url+'sitedoctor/progress_count',
			type:'POST',
			dataType:'json',
			success:function(response){
				var search_complete=response.search_complete;
				var search_total=response.search_total;
				$("#domain_progress_msg_text").html(search_complete +" / "+ search_total +" <?php echo $this->lang->line('step completed') ?>");
				var width=(search_complete*100)/search_total;
				width=Math.round(width);	
				var details_url = response.details_url;		
				var width_per=width+"%";
				if(width<3)
				{
					$("#domain_progress_bar_con div").css("width","3%");
					$("#domain_progress_bar_con div").attr("aria-valuenow","3");
					$("#domain_progress_bar_con div span").html("1%");
				}
				else
				{
					$("#domain_progress_bar_con div").css("width",width_per);
					$("#domain_progress_bar_con div").attr("aria-valuenow",width);
					$("#domain_progress_bar_con div span").html(width_per);
				}
				if(width>=98) 
				{											
					$("#domain_progress_bar_con div").removeClass('progress-bar-animated');
					$("#domain_progress_msg_text").html("<?php echo $this->lang->line('you will be redirected to report page within few seconds')?>");
					clearInterval(interval);	
					$("#domain_progress_bar_con div").css("width","100%");
					$("#domain_progress_bar_con div").attr("aria-valuenow","100");
					$("#domain_progress_bar_con div span").html("100%");
					$("#domain_success_msg").html('<center><h2 class="violet"><?php echo $this->lang->line("completed") ?></h2></center>');						
					var delay=3000;
					setTimeout(function() {
						window.location.href=details_url;
					}, delay);						
				}				
				
			}
		});
	}


	var base_url = "<?php echo base_url(); ?>";
	var compare = "<?php echo $compare;?>";
	var base_site = "<?php echo $base_site;?>";

	$(document).on('click','#search',function(){

		var website = $("#page_search").val();
		$("#domain_name_show").html(website);

		if(website == '')
		{
			swal('<?php echo $this->lang->line("Warning"); ?>', '<?php echo $this->lang->line("Please enter Your web address"); ?>', 'warning');
			return;
		}

		
		$("#domain_progress_bar_con div").css("width","3%");
		$("#domain_progress_bar_con div").attr("aria-valuenow","3");
		$("#domain_progress_bar_con div span").html("1%");	
		$("#domain_progress_bar_con").show();
		$("#domain_progress_msg_text").html('<span><?php echo $this->lang->line("please wait"); ?></span>');
		$("#domain_progress_msg_text").html('');

		interval=setInterval(get_bulk_progress, 5000);
					
		$("#domain_success_msg").html('<img width="20%" class="center-block" src="'+base_url+'assets/pre-loader/loading-animations.gif" alt="Processing...">');

		$("#result").show();
		$.ajax({
			url:base_url+'sitedoctor/add_domain_action',
			type:'POST',
			data:{website:website,base_site:base_site,compare:compare},
			dataType:'json',
			success:function(response){	
				if(response.status=="0")
				{
					$("#domain_progress_bar_con").hide();
					alert(response.message);
					window.location.href=base_url+'sitedoctor/recent_check_report';
				}
				else
				{
					$("#domain_progress_msg_text").html("<?php echo $this->lang->line('you will be redirected to report page within few seconds')?>");
					clearInterval(interval);
					$("#domain_progress_bar_con div").css("width","100%");
					$("#domain_progress_bar_con div").attr("aria-valuenow","100");
					$("#domain_progress_bar_con div span").html("100%");
					$("#domain_success_msg").html('<center><h2 class="violet"><?php echo $this->lang->line("completed") ?></h2></center>');
					
					var delay=5000;
					setTimeout(function() {
						window.location.href=response.details_url;
					}, delay);

				}
			}

		});
	});

	
</script>