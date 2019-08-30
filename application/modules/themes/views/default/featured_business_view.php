<!-- Page heading two starts -->
<div class="page-heading-two">
    <div class="container">
        <h2><?php echo lang_key('featured_businesses'); ?></h2>
        <div class="breads">
            <a href="<?php echo site_url(); ?>"><?php echo lang_key('home'); ?></a> / <?php echo lang_key('featured_businesses'); ?>
        </div>
        <div class="clearfix"></div>
    </div>
</div>

<!-- Container -->
<div class="container">

    <div class="row">

        <div class="col-md-12 col-sm-12 col-xs-12">
            <?php render_widget('featured_posts_main');?>
            <div class="clearfix"></div>
        </div>
        
    </div>
</div>
