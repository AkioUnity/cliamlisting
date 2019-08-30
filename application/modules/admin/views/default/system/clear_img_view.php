<!-- file added on version 1.5 -->
<div class="row">
  <div class="col-md-12">
    <div class="box">
      <div class="box-title">
        <h3><i class="fa fa-bars"></i><?php echo lang_key("clear_unused_img"); ?></h3>
        <div class="box-tool">
          <a href="#" data-action="collapse"><i class="fa fa-chevron-up"></i></a>

        </div>
      </div>
      <div class="box-content">
        <div class="alert alert-info">
        <?php echo lang_key('unused_info_msg');?>
        </div>
        <?php echo (isset($msg))?$msg:'';?>
        <?php if(isset($files) && count($files)>0){?>
        <div class="alert alert-danger">
        <?php echo count($files);?> <?php echo lang_key('unused_files_found');?>
        </div>
        <div class="col-md-12 unused-list-container">
          <ol class="unused-file-list inline">
            <?php foreach ($files as $file) {
              ?>
            <li>
              <img src="<?php echo str_replace('./',base_url(), $file['path']).'/'.$file['name'];?>" style="width:50px;">
              <span><?php echo $file['path'].'/'.$file['name'];?></span>
            </li>            
            <?php }?>
          </ol>
          <div style="clear:both"></div>
        </div> 
        <a class="btn btn-success" type="button" href="<?php echo site_url('admin/system/clearunusedimg/clear');?>"><?php echo lang_key("clear_unused_img"); ?></a>
        <?php }else{?>
        <div class="alert alert-success"><?php echo lang_key('no_unused_img');?></div>
        <?php }?>
       </div>
    </div>
  </div>
</div> 
<style type="text/css">
.unused-file-list li img{
  margin-bottom: 10px;
}
.unused-list-container{
  height: 300px;
  overflow: auto;
  margin-bottom: 20px;
  border:1px solid #ddd;
  padding-top: 20px;
}
</style>