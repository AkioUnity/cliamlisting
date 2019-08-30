<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-title">
                <h3><i class="fa fa-bars"></i><?php echo lang_key("debug_email") ?> </h3>

                <div class="box-tool">
                    <a href="#" data-action="collapse"><i class="fa fa-chevron-up"></i></a>
                    <a href="#" data-action="close"><i class="fa fa-times"></i></a></div>
            </div>
            <div class="box-content">

            <form action="<?php echo site_url('admin/system/debugemail');?>" method="post">
                <label><?php echo lang_key('email_to');?></label>
                <input type="text" name="to_email" class="form-control">
                <div class="clearfix" style="margin-top:10px;"></div>
                <input type="submit" value="<?php echo lang_key('send')?>" class="btn">
            </form>
            
            <div class="clearfix" style="margin-top:10px;"></div>            
            <?php echo isset($result)?$result:'';?>
               

            </div>
        </div>
    </div>
</div>




<script type="text/javascript">
    jQuery('#sel_tmpl').change(function(){
        jQuery('#edit_tmpl').attr('href',"<?php echo site_url('admin/system/emailtmpl');?>"+"/"+jQuery(this).val());
    });
</script>
