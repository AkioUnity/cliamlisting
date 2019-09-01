<script type="text/javascript">
    var menu_title = '<?php echo lang_key("MENU");?>';
</script>
<!-- Header two Starts -->
<div class="header-2">

    <!-- Container -->
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-3">
                <!-- Logo section -->
                <!-- updated on version 1.6 -->
                <div class="logo">
                    <?php 
                    $logo_type = get_settings('site_settings','logo_type','Image');
                    if($logo_type=='Image')
                    {
                    ?>
                    <h3>
                        <a href="<?php echo site_url();?>">
                        <img src="<?php echo get_site_logo();?>" alt="Logo" style="height:63px">
                        </a>
                    </h3>
                    <?php 
                    }else{
                    ?>
                    <h1 class="logo-text" style="color:<?php echo get_settings('site_settings','logo_text_color','#222');?>">
                        <?php echo  get_settings('site_settings','logo_text','No Logo')?>
                    </h1>
                    <?php 
                    }
                    ?>
                </div>
                <!-- end -->
            </div>
            <div class="col-md-9 col-sm-9">

                <!-- Navigation starts.  -->
                <div class="navy">
                    <ul class="pull-right">
                        <?php
                            $CI = get_instance();
                            $CI->load->model('admin/page_model');
                            $CI->page_model->init();
                        ?>



                        <?php 
                            $alias = (isset($alias))?$alias:'';
                            foreach ($CI->page_model->get_menu() as $li) 
                            {
                                if($li->parent==0)
                                $CI->page_model->render_top_menu($li->id,0,$alias);
                            }
                        ?>

                        <?php if(!is_loggedin()){?>
                        <?php if(get_settings('business_settings','enable_signup','Yes')=='Yes'){?>
                        <li class="">
                            <a class="signup" href="<?php echo site_url('account/signupform');?>"><?php echo lang_key('signup')?></a>
                        </li>
                        <?php }?>
                        <li class="">
                            <a class="signin" href="#"><?php echo lang_key('signin');?></a>
                        </li>
                        <?php }else{ ?>
                        <li class="">
                            <a class="signup" href="<?php echo site_url('admin');?>"><?php echo lang_key('user_panel');?></a>
                        </li>
                        <li class="">
                            <a class="signup" href="<?php echo site_url('account/logout');?>"><?php echo lang_key('logout');?></a>
                        </li>
                        <?php }?>

                    </ul>
                </div>
                <!-- Navigation ends -->

            </div>

        </div>
    </div>
</div>



