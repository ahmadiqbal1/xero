
<style>
	::placeholder{color:#adadad !important;}
	.ml-10{margin-left: 10px;}
	#website_searching{max-width: 50% !important;}
	@media (max-width: 575.98px) {
	  #website_searching{max-width: 77% !important;}
	}
</style>

<section class="section section_custom">
  <div class="section-header">
    <h1><i class="fas fa-compress-arrows-alt"></i> <?php echo $page_title; ?></h1>
      <div class="section-header-button">
        <a class="btn btn-primary" href="<?php echo base_url("sitedoctor/add_domain") ?>">
          <i class="fas fa-plus-circle"></i> <?php echo $this->lang->line("Check Website Health"); ?>
        </a> 
      </div>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><a href="<?php echo base_url('menu_loader/sitedoc') ?>"><?php echo $this->lang->line('Sitedoctor'); ?></a></div>
      <div class="breadcrumb-item"><?php echo $page_title; ?></div>
    </div>
  </div>

  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body data-card">
            <div class="row">

                <div class="col-md-6 col-12">
                    <div class="input-group float-left" id="searchbox">

                        <input type="text" class="form-control" id="base_website" name="base_website" placeholder="<?php echo $this->lang->line('Website'); ?>" aria-label="" aria-describedby="basic-addon2">
                        <input type="text" class="form-control" id="competitor_website" name="competitor_website" placeholder="<?php echo $this->lang->line('Competitor Website'); ?>" aria-label="" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                        	<a href="" class="btn btn-lg btn-primary" id="hit_search"><i class="fas fa-search"></i> <?php echo $this->lang->line('Search'); ?></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <a href="javascript:;" id="post_date_range" class="btn btn-primary btn-lg icon-left float-right btn-icon"><i class="fas fa-calendar"></i> <?php echo $this->lang->line("Choose Date");?></a><input type="hidden" id="comaparative_health_checker_post_date_range_val">
                    </a>
                </div>
            </div>
            
            <div class="table-responsive2">
            	<table class="table table-bordered" id="mytable_comparative_health_checker">
                <thead>
                	<tr>
						<th>#</th> 
						<th><?php echo $this->lang->line('ID');?></th>
						<th><?php echo $this->lang->line('Website - competitor website');?></th>
						<th><?php echo $this->lang->line('warning');?></th>
						<th><?php echo $this->lang->line('Mobile score');?></th>
						<th><?php echo $this->lang->line('Actions');?></th>
						<th><?php echo $this->lang->line('Desktop score');?></th>
						<th><?php echo $this->lang->line('overall score');?></th>
						<th><?php echo $this->lang->line('compared at');?></th>
                	</tr>
                </thead>
                <tbody>
                </tbody>
            	</table>
            </div>             
          </div>
        </div>
      </div>
    </div> 
  </div>
</section> 

<script>
	$(document).ready(function($) {
		var base_url = '<?php echo base_url(); ?>';	

		var Doyouwanttodeletethisrecordfromdatabase = "<?php echo $this->lang->line('Do you want to detete this record?'); ?>";
		var Doyouwanttodeletealltheserecordsfromdatabase = "<?php echo $this->lang->line('Do you want to detete all the records from the database?'); ?>";

		setTimeout(function(){ 
		  $('#post_date_range').daterangepicker({
		    ranges: {
		      '<?php echo $this->lang->line("Last 30 Days");?>': [moment().subtract(29, 'days'), moment()],
		      '<?php echo $this->lang->line("This Month");?>'  : [moment().startOf('month'), moment().endOf('month')],
		      '<?php echo $this->lang->line("Last Month");?>'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
		    },
		    startDate: moment().subtract(29, 'days'),
		    endDate  : moment()
		  }, function (start, end) {
		    $('#comaparative_health_checker_post_date_range_val').val(start.format('YYYY-M-D') + '|' + end.format('YYYY-M-D')).change();
		  });
		}, 2000);

		var perscroll1;
		var comparative_report_table = $("#mytable_comparative_health_checker").DataTable({
			serverSide: true,
			processing:true,
			bFilter: false,
			order: [[ 1, "desc" ]],
			pageLength: 10,
			ajax: 
			{
				"url": base_url+'sitedoctor/comparative_check_report_data',
				"type": 'POST',
				data: function ( d )
				{
					d.base_website = $('#base_website').val();
					d.competitor_website = $('#competitor_website').val();
					d.post_date_range = $('#comaparative_health_checker_post_date_range_val').val();
				}
			},
			language: 
			{
				url: "<?php echo base_url('assets/modules/datatables/language/'.$this->language.'.json'); ?>"
			},
			dom: '<"top"f>rt<"bottom"lip><"clear">',
			columnDefs: [
			{
				targets: [1],
				visible: false
			},
			{
				targets: [0,1,3,4,5,6,7,8],
				className: 'text-center'
			},
			{
				targets: [0,1,2,3,4,5,6,7,8],
				sortable: false
			}
			],
			fnInitComplete:function(){  // when initialization is completed then apply scroll plugin
				if(areWeUsingScroll)
				{
					if (perscroll1) perscroll1.destroy();
					perscroll1 = new PerfectScrollbar('#mytable_comparative_health_checker_wrapper .dataTables_scrollBody');
				}
			},
			scrollX: 'auto',
			fnDrawCallback: function( oSettings ) { //on paginition page 2,3.. often scroll shown, so reset it and assign it again 
				if(areWeUsingScroll)
				{ 
					if (perscroll1) perscroll1.destroy();
					perscroll1 = new PerfectScrollbar('#mytable_comparative_health_checker_wrapper .dataTables_scrollBody');
				}
			}
		});


		$(document).on('click', '#hit_search', function(event) {
			event.preventDefault(); 
			comparative_report_table.draw();
		});

		$(document).on('change', '#comaparative_health_checker_post_date_range_val', function(event) {
			event.preventDefault(); 
			comparative_report_table.draw();
		});

		

		$(document).on('click','.delete_website',function(e){
		    e.preventDefault();
		    swal({
		        title: '<?php echo $this->lang->line("Are you sure?"); ?>',
		        text: Doyouwanttodeletethisrecordfromdatabase,
		        icon: 'warning',
		        buttons: true,
		        dangerMode: true,
		    })
		    .then((willDelete) => {
		        if (willDelete) 
		        {
		            var table_id = $(this).attr('table_id');

		            $.ajax({
		                context: this,
		                type:'POST' ,
		                url:"<?php echo base_url('sitedoctor/delete_website_health_report')?>",
		                data:{table_id:table_id},
		                success:function(response){ 

		                    if(response == '1')
		                    {
		                        iziToast.success({title: '',message: '<?php echo $this->lang->line('API Information has been Deleted Successfully.'); ?>',position: 'bottomRight'});
		                    } else
		                    {
		                        iziToast.error({title: '',message: '<?php echo $this->lang->line('Something went wrong, please try once again.'); ?>',position: 'bottomRight'});
		                    }
		                    comparative_report_table.draw();
		                }
		            });
		        } 
		    });
		});
	});
</script>

