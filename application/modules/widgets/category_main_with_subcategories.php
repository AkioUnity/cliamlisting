<!-- full file updated on version 1.5 -->
<?php
$CI = get_instance();
$CI->load->model('user/post_model');
$parent_categories = $CI->post_model->get_all_parent_categories();
?>

<div class="block-heading-two">
    <h3><span><i class="fa fa-folder"></i> <?php echo lang_key('categories') ?></span></h3>
</div>

<div class="icon-box-1 text-center">
    <?php 
    $i = 0;
    foreach ($parent_categories->result() as $parent) {
        $i++;
    ?>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <!-- Icon Box One Item -->
        <div class="icon-box-1-item category-item">
            <!-- Icon Box One Icon -->
            <?php
            	$class = '';
                if($i%4 == 1) 
                    $class = "red";
                else if($i%4 == 2)
                    $class = "green";
                else if($i%4 == 3)
                    $class = "orange";
                else
                    $class = "blue";
            ?>
            <a href="<?php echo site_url('show/categoryposts/'.$parent->id.'/'.dbc_url_title(lang_key($parent->title)));?>"><i class="fa <?php echo $parent->fa_icon.' '.$class;?>"></i></a>
            <div class="category-bor bg-<?php echo $class;?>"></div>
            <!-- Icon Box One Headeing -->
            <h4><a href="<?php echo site_url('show/categoryposts/'.$parent->id.'/'.dbc_url_title(lang_key($parent->title)));?>"><?php echo lang_key($parent->title);?></a></h4>
            <!-- Icon Box One Paragraph -->
            <?php
            $child_categories = $CI->post_model->get_all_child_categories($parent->id, 5);
            $total = $child_categories->num_rows();
            ?>
            <ul>

                <?php
                $j=1;
                foreach ($child_categories->result() as $child) :
                    if($j<=4)
                    {
                ?>

                <li>
                    <a class="" title="View posts in <?php echo lang_key($child->title);?>" href="<?php echo site_url('show/categoryposts/'.$child->id.'/'.dbc_url_title(lang_key($child->title)));?>">
                        <?php echo lang_key($child->title);?>
                        <span dir="rtl" class="category-counter <?php echo $class;?>">(<?php echo $CI->post_model->count_post_by_category_id($child->id);?>) </span>
                    </a>
                </li>

                <?php
                    }
                    $j++;
                endforeach;

                if($total>=5)
                {
                ?>
                <li>
                    <a class="<?php echo $class;?> see_all_sub_cat"  title="<?php echo lang_key('see_all');?>" href="<?php echo site_url('show/allsubcat/'.$parent->id);?>">
                        <?php echo lang_key('view_all');?>
                        <span dir="rtl" class="category-counter <?php echo $class;?> color">&nbsp;</span>
                    </a>
                </li>
                <?php
                }
                ?>
            </ul>
            <div class="clearfix"></div>
        </div>
    </div>
    <?php }?>
</div>
<div class="clearfix"></div>
<!-- category box end -->

 <div id="category-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true" style="display: none;">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

                    <h4 class="modal-title" id="myModalLabel2"><?php echo lang_key('all_sub_categories'); ?> </h4>

                </div>

                <div class="modal-body">

                    
                </div>

                <div class="modal-footer">

                </div>

            </div>

            <!-- /.modal-content -->

        </div>

        <!-- /.modal-dialog -->

    </div>

<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('.see_all_sub_cat').click(function(e){
            e.preventDefault();
            jQuery('#category-modal').modal('show');

            var load_url = jQuery(this).attr('href');
              jQuery.post(
                  load_url,
                  {},
                  function(responseText){   
                    jQuery('#category-modal  .modal-body').html(responseText);
                  }
              ); 
          
        });

    });

</script>