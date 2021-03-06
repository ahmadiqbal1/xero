<?php 
  $this->load->view("include/upload_js");
  if($this->config->item("xeroseo_file_upload_limit") != "") {
    $file_upload_limit = $this->config->item("xeroseo_file_upload_limit");
  }
  else{
    $file_upload_limit = 4;
  }
  
?>

<style type="text/css">
  .multi_layout{margin:0;background: #fff}
  .multi_layout .card{margin-bottom:0;border-radius: 0;}
  .multi_layout p, .multi_layout ul:not(.list-unstyled), .multi_layout ol{line-height: 15px;}
  .multi_layout .list-group li{padding: 25px 10px 12px 25px;}
  .multi_layout{border:.5px solid #dee2e6;}
  .multi_layout .collef,.multi_layout .colmid{padding-left: 0px; padding-right: 0px;border-right: .6px solid #dee2e6;border-bottom: .6px solid #dee2e6;}
  .multi_layout .colmid .card-icon{border:.5px solid #dee2e6;}
  .multi_layout .colmid .card-icon i{font-size:30px !important;}
  .multi_layout .main_card{min-height: 450px;}
  .multi_layout .collef .makeScroll{max-height:430px;overflow:auto;}
  .multi_layout .list-group .list-group-item{border-radius: 0;border:.5px solid #dee2e6;border-left:none;border-right:none;z-index: 0;}
  .multi_layout .list-group .list-group-item:first-child{border-top:none;}
  .multi_layout .list-group .list-group-item:last-child{border-bottom:none;}
  .multi_layout .list-group .list-group-item.active{border:.5px solid #6777EF;}
  .multi_layout .mCSB_inside > .mCSB_container{margin-right: 0;}
  .multi_layout .card-statistic-1{border:.5px solid #dee2e6;border-radius: 4px;}
  .multi_layout h6.page_name{font-size: 14px;}
  .multi_layout .card .card-header input{max-width: 100% !important;}
  .multi_layout .card-primary{margin-top: 35px;margin-bottom: 15px;}
  .multi_layout .product-details .product-name{font-size: 12px;}
  .multi_layout .margin-top-50 {margin-top: 70px;}
  .multi_layout .waiting {height: 100%;width:100%;display: table;}
  .multi_layout .waiting i{font-size:60px;display: table-cell; vertical-align: middle;padding:10px 0;}
  .waiting {padding-top: 200px;}
  .check_box{position: absolute !important;top: 0 !important;right: 0 !important;margin: 3px;}
  .check_box_background{position: absolute;height: 60px;width: 60px;top: 0;right: 0;font-size: 13px;}
  .profile-widget { margin-top: 0;}
  .profile-widget .profile-widget-items:after {content: ' ';position: absolute;bottom: 0;left: 0px;right: 0;height: 1px;background-color: #f2f2f2;}
  .profile-widget .profile-widget-items:before {content: ' ';position: absolute;top: 0;left: 0px;right: 0;height: 1px;background-color: #f2f2f2;}
  .profile-widget .profile-widget-items .profile-widget-item {flex: 1;text-align: center;padding: 10px 0;}
  .article .article-header {overflow: unset !important;}
  .description_info {padding: 20px;line-height: 17px;font-size: 13px;margin: 0;}
  .option_dropdown {position: absolute;top: 0;left: 0;height: 20px;width: 22px;background-color: #f7fefe;border-radius: 24%;padding-top: 0px;margin-top: 3px;margin-left: 3px;border: 1px solid #4e6e7e;}
  .video_option_background{position: absolute;height: 60px;width: 60px;top: 0;left: 0;}
  .selectric .label {min-height: 0 !important;}
  .opt_btn{border-radius: 30px !important;padding-left: 25px !important;padding-right: 25px !important;}
  .generic_message_block textarea{height: 100px !important;}
  .filter_message_block textarea{height: 100px !important;margin-bottom: 30px;}
  .single_card .card-body .form-group{margin-bottom: 10px;}
  .single_card .card-body{padding-bottom: 0 !important;}
  .bootstrap-tagsinput{height: 100px !important;}
  .profile-widget .profile-widget-items .profile-widget-item .profile-widget-item-value {font-weight: 300;font-size: 13px;
  }
  .padding-0{padding: 0px;}
  .bck_clr{background: #ffffff!important;}
  .mt-30{margin-top: 30px!important;}
  .mt-66{margin-top: 66px!important;}
    .ajax-file-upload{
      bottom: 12px;
  }
</style>




<section class="section">
  <div class="section-header">
    <h1><i class="fab fa-js"></i> <?php echo $page_title;?></h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><a href="<?php echo base_url('menu_loader/code_minifier') ?>"><?php echo $this->lang->line("Code Minifier");?></a></div>
      <div class="breadcrumb-item"><?php echo $page_title;?></div>
    </div>
  </div>
</section>
  

<div class="row multi_layout">

  <div class="col-12 col-md-6 col-lg-6 collef">
    <div class="card main_card">
      <div class="card-header">
        <h4><i class="fas fa-info-circle"></i> <?php echo $this->lang->line('Info'); ?></h4>
      </div>
      <form enctype="multipart/form-data" method="POST"  id="new_search_form">
        


        <div class="card-body">

          <div class="form-group">
            <label class="form-label"> <?php echo $this->lang->line("Js Code Minifier"); ?> <a href="#" data-placement="top" data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("Js Code Minifier") ?>" data-content='<?php echo $this->lang->line("Write your js code here... ") ?>'><i class='fa fa-info-circle'></i> </a></label>
            <textarea id="js_code" name="js_code" class="form-control" style="width:100%;min-height: 140px;" rows="10"></textarea>
          </div>

          <div class="form-group">
                <label> <?php echo $this->lang->line('Files');?> <a href="#" data-placement="top" data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("Files") ?>" data-content='<?php echo $this->lang->line("To minify your single or multiple js files, please select your files by clicking the upload button bellow. ") ?>'><i class='fa fa-info-circle'></i> </a></label>
                  <div id="file_upload_url" class="form-control"><?php echo $this->lang->line('Upload');?></div>
          </div> 
      
        </div>

        <div class="card-footer bg-whitesmoke mt-66">

            <button type="button"  id="minify_js" class="btn btn-primary "> <?php echo $this->lang->line("Minify"); ?></button>
            <button class="btn btn-secondary btn-md float-right" onclick="goBack('menu_loader/code_minifier')" type="button"> <?php echo $this->lang->line('Cancel'); ?></button>
        </div>

      </form>
    </div>          
  </div>

  <div class="col-12 col-md-6 col-lg-6 colmid">
    <div id="custom_spinner"></div>
    <div id="middle_column_content" style="background: #ffffff!important;">

      <div class="card">
        <div class="card-header">
          <h4> <i class="fab fa-js"></i> <?php echo $this->lang->line('JS Minifier Results'); ?></h4>
          
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-6 col-lg-12 bck_clr" id="nodata">

        <div class="empty-state">
          <img class="img-fluid" src="<?php echo base_url("assets/img/drawkit/revenue-graph-colour.svg"); ?>" style="height: 300px" alt="image">
        

        </div>

      </div>
    </div>
  </div>
</div>

<script>       
  var base_url="<?php echo site_url(); ?>";
</script>



<script type="text/javascript">

  $("document").ready(function(){

    $(document).on('click', '#minify_js', function(event) {
      event.preventDefault();

        var js_code=$("#js_code").val().trim();
        
        if(js_code==''){
          swal("<?php echo $this->lang->line('Error'); ?>", "<?php echo $this->lang->line('Please Write JS First'); ?>", 'error');
          return false;
        }

        $('#middle_column_content').html("");
        $("#minify_js").addClass('btn-progress');
        $("#custom_spinner").html('<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center"></i></div><br/>');


        $.ajax({
          url:base_url+'code_minifier/js_minifier_textarea',
          type:'POST',
          data:{js_code:js_code},
          success:function(response){ 
              $("#minify_js").removeClass('btn-progress');
              $("#custom_spinner").html("");
              $("#middle_column_content").html(response.trim())
            
          }
          
        });
        
    });


     var file_upload_limit = "<?php echo $file_upload_limit; ?>";
     var files_list = [];
      $("#file_upload_url").uploadFile({
        url:base_url+"code_minifier/read_text_file_js",
        fileName:"myfile",
        maxFileSize:file_upload_limit*1024*1024,
        showPreview:false,
        returnType: "json",
        dragDrop: true,
        showDelete: true,
        multiple:true,
        maxFileCount:5,
        acceptFiles:".js",
        deleteCallback: function (data, pd) {
            var delete_url="<?php echo site_url('code_minifier/read_after_delete_js');?>";
              $.post(delete_url, {op: "delete",name: data.file_name},
                  function (resp,textStatus, jqXHR) {

                    var item_to_delete =data.content;
                    files_list = files_list.filter(item => item !== item_to_delete);
                    $("#js_code").val(files_list.join());

                  });

         },
         onSuccess:function(files,data,xhr,pd)
           {
               if (data.are_u_kidding_me =="yarki") {
               swal("<?php echo $this->lang->line('Error'); ?>", "<?php echo $this->lang->line('Something went wrong, please choose valid file'); ?>", 'error');
                return false;
               }

               $("#js_code").val(data.content);
               var data_modified = data.content;
               files_list.push(data_modified);
               $("#js_code").val(files_list.join());
                  
            
           }
    });

  });  

</script>
<script src="<?=base_url()?>js/clipboard.min.js"></script>

