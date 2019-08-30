<!-- file updated on version 1.8 -->
<?php $CI = get_instance(); ?>
<?php 
$filter_type = get_settings('banner_settings','search_panel_filter_type','basic_options'); // added on version 1.5
$css_class = ($filter_type=='basic_options')?'col-md-3 col-sm-6 col-sx-12':'col-md-4 col-sm-4 col-xs-12';
$state_active = get_settings('business_settings', 'show_state_province', 'yes'); //added on version 1.7
?>
<link href="<?php echo theme_url();?>/assets/jquery-ui/jquery-ui.css" rel="stylesheet">
<script src="<?php echo theme_url();?>/assets/jquery-ui/jquery-ui.js"></script>

<link href="<?php echo theme_url();?>/assets/css/select2.css" rel="stylesheet">
<script src="<?php echo theme_url();?>/assets/js/select2.js"></script>
<div class="real-estate">
    <div class="re-big-form">
        <div class="container">
            <!-- Nav tab style 2 starts -->
            <div class="nav-tabs-two buy-sell-tab">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs">
                </ul>
                <!-- Tab content -->
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="tab-1">

                        <form role="form" action="<?php echo site_url('show/advfilter')?>" method="post">
                            <div class="row">
                                
                                <!-- added on version 1.7 -->
                                <?php if($filter_type=='advanced_options_with_country_state'){?>
                                <div class="<?php echo $css_class;?>">
                                    <div class="form-group">
                                        <label for="input-11"><?php echo lang_key('select_country');?></label>
                                        <select name="country" id="country" class="form-control chosen-select">
                                            <option data-name="" value=""><?php echo lang_key('select_country');?></option>
                                            <?php foreach (get_all_locations_by_type('country')->result() as $row) {
                                                $sel = ($row->id==set_value('country'))?'selected="selected"':'';
                                                ?>
                                                <option data-name="<?php echo $row->name;?>" value="<?php echo $row->id;?>" <?php echo $sel;?>><?php echo lang_key($row->name);?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>

                                <?php if($state_active=='yes'){?>
                                <div class="<?php echo $css_class;?>">
                                    <div class="form-group">
                                        <label for="input-11"><?php echo lang_key('select_state');?></label>
                                        <select name="state" id="state" class="form-control chosen-select">
                                            
                                        </select>
                                    </div>
                                </div>
                                <?php }?>

                                <div class="<?php echo $css_class;?>">
                                    <div class="form-group">
                                        <label for="input-11"><?php echo lang_key('select_city');?></label>
                                        <?php $city_field_type = get_settings('business_settings', 'city_dropdown', 'autocomplete'); ?>
                                        <input type="hidden" id="selected_city" value="<?php echo(set_value('selected_city')!='')?set_value('selected_city'):'';?>">
                                        <select name="city" id="city_dropdown" class="form-control chosen-select">                                        
                                            <option value=""><?php echo lang_key('select_one');?></option>
                                        </select>
                                    </div>
                                </div>

                                <?php }?>

                                <?php if($filter_type!='advanced_options_with_country_state'){?>
                                <div class="<?php echo $css_class;?>">
                                    <div class="form-group">
                                        <label for="input-11"><?php echo lang_key('select_city');?></label>
                                        <select id="input-11" name="city" class="form-control chosen-select">
                                            <option data-name="" value="any"><?php echo lang_key('any_city');?></option>
                                              <?php foreach (get_all_cities_by_use()->result() as $row) {
                                                  $sel = ($row->id==set_value('city'))?'selected="selected"':'';
                                                  ?>
                                                  <option data-name="<?php echo $row->name;?>" class="cities city-<?php echo $row->parent;?>" value="<?php echo $row->id;?>" <?php echo $sel;?>><?php echo lang_key($row->name);?></option>
                                              <?php }?>
                                        </select>
                                    </div>
                                </div>
                                <?php }?>

                                <?php if($filter_type=='advanced_options_with_country_state' && $state_active=='yes'){?>
                                <div class="clearfix"></div>
                                <?php }?>
                                
                                <!-- added on version 1.5 -->
                                <?php if($filter_type=='advanced_options' || $filter_type=='advanced_options_with_country_state'){?>
                                <div class="<?php echo $css_class;?>">
                                    <!-- Search Widget -->
                                    <div class="form-group">
                                        <label for="input-14"><?php echo lang_key('key_words');?></label>
                                        <input class="form-control" type="text" placeholder="<?php echo lang_key('type_anything');?>" value="<?php echo (isset($data['plainkey']))?rawurldecode($data['plainkey']):'';?>" name="plainkey">
                                    </div>
                                </div>
                                <?php }?>
                                <!-- end -->

                                <?php if($filter_type=='advanced_options_with_country_state' && $state_active!='yes'){?>
                                <div class="clearfix"></div>
                                <?php }?>

                                <div class="<?php echo $css_class;?>">
                                    <div class="form-group">
                                        <label for="input-14"><?php echo lang_key('select_category');?></label>
                                        <?php
                                        $CI = get_instance();
                                        $CI->load->model('user/post_model');
                                        $categories = $CI->post_model->get_all_categories();
                                        ?>
                                        <select id="input-14" name="category" class="form-control chosen-select">
                                            <option value="any"><?php echo lang_key('any_category');?></option>
                                              <?php foreach ($categories as $row) {
                                                  $sub = ($row->parent!=0)?'--':'';
                                                  $sel = (set_value('category')==$row->id)?'selected="selected"':'';
                                              ?>
                                                  <option value="<?php echo $row->id;?>" <?php echo $sel;?>><?php echo $sub.lang_key($row->title);?></option>
                                              <?php
                                              }?>
                                        </select>
                                    </div>
                                </div>
                                
                                <?php if($filter_type=='advanced_options'){?>
                                <div class="clearfix"></div>
                                <?php }?>

                                <!-- added on version 1.5 -->
                                <?php if($filter_type=='advanced_options' || $filter_type=='advanced_options_with_country_state'){?>
                                <!--div style="clear:both">
                                </div-->
                                <?php }?>
                                <!-- end -->

                                <div class="<?php echo $css_class;?>">
                                    <div class="form-group">
                                        <label><?php echo lang_key('distance_around_my_position'); ?>: <span class="price-range-amount-view" id="amount"></span></label>
                                        <div class="clearfix"></div>
                                        <a href="javascript:void(0);" onclick="findLocation()" class="btn btn-orange btn-xs find-my-location" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo lang_key('identify_my_location');?>"><i class="fa fa-location-arrow"></i></a>
                                        <div id="slider-price-sell" class="price-range-slider"></div>
                                        <input type="hidden" id="price-slider-sell" name="distance" value="">
                                        <input type="hidden" id="geo_lat" name="geo_lat" value="">
                                        <input type="hidden" id="geo_lng" name="geo_lng" value="">

                                    </div>
                                </div>

                                <?php if($filter_type=='advanced_options_with_country_state' && $state_active=='yes'){?>
                                <div class="clearfix"></div>
                                <?php }?>

                                <!-- added on version 1.5 -->
                                <?php if($filter_type=='advanced_options' || $filter_type=='advanced_options_with_country_state'){?>
                                
                                <div class="<?php echo $css_class;?>">
                                 <div class="form-group">
                                    <label for="input-14"><?php echo lang_key('sorting_order');?></label>
                                    <?php $sort_by_temp = (isset($data['sort_by']))?$data['sort_by']:'';?>
                                    <?php $options = array('rating_asc','rating_desc','id_asc','id_desc');?>
                                    <select name="sort_by" class="form-control chosen-select">
                                        <option value=""><?php echo lang_key('order_by');?></option>
                                        <?php foreach ($options as $row) {
                                            $sel = ($row==$sort_by_temp)?'selected="selected"':'';
                                            ?>
                                            <option value="<?php echo $row;?>" <?php echo $sel;?>><?php echo lang_key($row);?></option>
                                        <?php }?>
                                    </select>
                                </div>
                                </div>

                                <?php if($filter_type=='advanced_options_with_country_state' && $state_active!='yes'){?>
                                <div class="clearfix"></div>
                                <?php }?>

                                <?php }?>
                                <!-- end -->                                


                                
                                <div class="<?php echo $css_class;?>">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-color"><i class="fa fa-search"></i>&nbsp; <?php echo lang_key('search_businesses'); ?></button>
                                    </div>
                                </div>


                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<?php
$isSsl = (strpos('-'.base_url(), 'https://')>0)?'1':'0';
?>
<script type="text/javascript">

    var ua = navigator.userAgent.toLowerCase();
    var isAndroid = ua.indexOf("android") > -1; //&& ua.indexOf("mobile");

    // added on version 1.5
    var isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);
    var isSsl = '<?php echo $isSsl;?>';
    //end

    jQuery(window).resize(function(){
        if(!isAndroid) {
            $('.chosen-select').select2({
                theme: "classic"
            });
        }
    });

    $(document).ready(function(){
        if(!isAndroid) {
            $('.chosen-select').select2({
                theme: "classic"
            });
        }
        
        var distance_unit = '<?php echo lang_key(get_settings("business_settings", "show_distance_in", "miles")); ?>';

        $("#slider-price-sell").slider({

            min: <?php echo $this->config->item('min_distance');?>,

            max: <?php echo $this->config->item('max_distance');?>,

            value: <?php echo $this->config->item('default_distance');?>,

            slide: function (event, ui) {

                $("#price-slider-sell").val(ui.value);
                $("#amount").html( ui.value + ' ' + distance_unit );

            }

        });
        $("#amount").html($( "#slider-price-sell" ).slider( "value") + ' ' + distance_unit);


    });

    // updated on version 1.5
    function findLocation()
    {
        if(isChrome==true && isSsl==0)
        {
            var r = confirm("<?php echo lang_key('location_chorome_msg')?>");
            if(r==true)
            {
                $.get("//ipinfo.io", function(response) {
                var arr = response.loc.split(",");

                        $('#geo_lat').val(arr[0]);
                        $('#geo_lng').val(arr[1]);

                }, "jsonp");
                
            }
        }
        else
        {
            if(!!navigator.geolocation) {

                navigator.geolocation.getCurrentPosition(function(position) {

                    $('#geo_lat').val(position.coords.latitude);
                    $('#geo_lng').val(position.coords.longitude);


                });

            } else {
                alert('No Geolocation Support.');
            }            
        }
    }
    //end

    $(document).ready(function(){

        var site_url = '<?php echo site_url();?>';
        jQuery('#country').change(function(){
            // jQuery('#city').val('');
            // jQuery('#selected_city').val('');
            var val = jQuery(this).val();
            
            var loadUrl = site_url+'/show/get_locations_by_parent_ajax/'+val;

            jQuery.post(
                loadUrl,
                {},
                function(responseText){
                    <?php if($state_active=='yes'){?>
                    jQuery('#state').html(responseText);
                    var sel_country = '<?php echo (set_value("country")!='')?set_value("country"):"";?>';
                    var sel_state   = '<?php echo (set_value("state")!='')?set_value("state"):"";?>';
                    if(val==sel_country)
                    jQuery('#state').val(sel_state);
                    else
                    jQuery('#state').val('');
                    jQuery('#state').focus();
                    jQuery('#state').trigger('change');
                    <?php }else{?>
                    var sel_country = '<?php echo (set_value("country")!='')?set_value("country"):"";?>';
                    var sel_city   = '<?php echo (set_value("selected_city")!='')?set_value("selected_city"):"";?>';
                    var city   = '<?php echo (set_value("city")!='')?set_value("city"):"";?>';
                    if(city_field_type=='dropdown')
                    populate_city(val); //populate the city drop down
                    if(val==sel_country)
                    {
                        jQuery('#selected_city').val(sel_city);
                        jQuery('#city').val(city);
                    }
                    else
                    {
                        jQuery('#selected_city').val(sel_city);
                        jQuery('#city').val('');            
                    }
                    <?php }?>

                }
            );
         }).change();

        var city_field_type =  'dropdown' ;

            jQuery('#state').change(function(){
                <?php if($state_active=='yes'){?>
                var val = jQuery(this).val();
                var sel_state   = '<?php echo (set_value("state")!='')?set_value("state"):"";?>';
                var sel_city   = '<?php echo (set_value("selected_city")!='')?set_value("selected_city"):"";?>';
                var city   = '<?php echo (set_value("city")!='')?set_value("city"):"";?>';
                
                if(city_field_type=='dropdown')
                populate_city(val); //populate the city drop down

                if(val==sel_state)
                {
                    jQuery('#selected_city').val(sel_city);
                    jQuery('#city').val(city);
                }
                else
                {
                    jQuery('#selected_city').val('');
                    jQuery('#city').val('');            
                }
                <?php }?>

            }).change();

    });

function populate_city(parent) {
    var site_url = '<?php echo site_url();?>';
    var loadUrl = site_url+'/show/get_city_val_dropdown_by_parent_ajax/'+parent;
        jQuery.post(
            loadUrl,
            {},
            function(responseText){
                jQuery('#city_dropdown').html(responseText);
                var sel_city   = '<?php echo (set_value("city")!='')?set_value("city"):"";?>';
                jQuery('#city_dropdown').val(sel_city);
            }
        );
}
</script>
<!-- property search big form -->