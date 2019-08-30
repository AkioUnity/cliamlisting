<!-- Page heading two starts -->
<div class="page-heading-two">
    <div class="container">
        <h2><?php echo lang_key('categories'); ?></h2>
        <div class="breads">
            <a href="<?php echo site_url(); ?>"><?php echo lang_key('home'); ?></a> / <?php echo lang_key('categories'); ?>
        </div>
        <div class="clearfix"></div>
    </div>
</div>

<!-- Container -->
<div class="container">

    <div class="row">

        <div class="col-md-9 col-sm-12 col-xs-12">
            <?php
            if($categories->num_rows()<=0){
                ?>
                <div class="alert alert-warning"><?php echo lang_key('no_location_found'); ?></div>
            <?php
            }
            else
            foreach($categories->result() as $category){ 
                // print_r($category);
                // die;
                ?>
                <h4><a href="<?php echo site_url('show/categoryposts/'.$category->id.'/'.dbc_url_title(lang_key($category->title)));?>"><i class="fa <?php echo $category->fa_icon;?> color"></i> <?php echo lang_key($category->title); ?> <span dir="rtl">(<?php echo $this->post_model->count_post_by_category_id($category->id); ?>)</span></a></h4>
                <div class="divider-5"></div>
                <div class="clearfix"></div>

                <?php
                $sub_categories = $this->post_model->get_all_child_categories($category->id,'all');
                foreach($sub_categories->result() as $sub_category){ ?>
                    
                    <div class="col-md-4 col-sm-4">
                        <ul class="list-2">
                            <li><a href="<?php echo site_url('show/categoryposts/'.$sub_category->id.'/'.dbc_url_title(lang_key($sub_category->title)));?>"><?php echo lang_key($sub_category->title); ?> <span dir="rtl">(<?php echo $this->post_model->count_post_by_category_id($sub_category->id); ?>)</span></a></li>
                        </ul>
                    </div>

                <?php }  ?>
                <div class="clearfix"></div>
            <?php } ?>


            <div class="clearfix"></div>
        </div>


        <div class="col-md-3 col-sm-12 col-xs-12">
            <div class="sidebar">
                <?php render_widgets('right_bar_categories');?>
            </div>
        </div>

    </div>
</div>
