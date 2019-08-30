<script type="text/javascript">
    var menu_title = '<?php echo lang_key("MENU");?>';
</script>
<!-- Top bar starts -->
<div class="top-bar">
    <div class="container">

        <?php render_widget('top_bar'); ?>

        

        <!-- Langauge starts -->
        <div class="tb-language dropdown pull-right">
            <?php 
            $CI         = get_instance();
            $uri        = current_url();
            $curr_lang  = get_current_lang();
            $CI->load->config('business_directory');
            $languages = $CI->config->item('active_languages');
            ?>
            <a href="#" data-target="#" data-toggle="dropdown"><i class="fa fa-globe"></i> <?php echo (isset($languages[$curr_lang]))?$languages[$curr_lang]:'language';?> <i class="fa fa-angle-down color"></i></a>
            <!-- Dropdown menu with languages -->

            <?php
            if($CI->uri->segment(1)=='')
                $uri .= '/'.default_lang();            
            echo '<ul class="dropdown-menu dropdown-mini" role="menu">';
            $url = $uri;

            foreach ($languages as $short_name=>$long_name) {   

                $uri = str_replace_second('/'.$curr_lang,'/'.$short_name,$url);
               // $uri = str_replace('/'.$curr_lang,'/'.$short_name,$url);
                $sel = ($curr_lang==$short_name)?'active':'';
                echo '<li class="'.$sel.'"><a href="'.$uri.'">'.$long_name.'</a></li>';
            }
            echo '</ul>';
            ?>

        </div>
        <!-- Language ends -->

        <!-- Search section for responsive design -->
        <!--div class="tb-search pull-left">
            <a href="#" class="b-dropdown"><i class="fa fa-search square-2 rounded-1 bg-color white"></i></a>
            <div class="b-dropdown-block">
                <form role="form">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Type Something">
									<span class="input-group-btn">
										<button class="btn btn-color" type="button"><?php echo lang_key('search');?></button>
									</span>
                    </div>
                </form>
            </div>
        </div-->
        <!-- Search section ends -->

        <?php render_widget('top_bar_social'); ?>


        <div class="clearfix"></div>
    </div>
</div>

<!-- Top bar ends -->
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



