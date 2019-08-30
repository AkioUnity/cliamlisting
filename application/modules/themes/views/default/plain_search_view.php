<!-- file updated on version 1.8 -->
<link href="<?php echo theme_url();?>/assets/jquery-ui/jquery-ui.css" rel="stylesheet">
<script src="<?php echo theme_url();?>/assets/jquery-ui/jquery-ui.js"></script>

<link href="<?php echo theme_url();?>/assets/css/select2.css" rel="stylesheet">
<script src="<?php echo theme_url();?>/assets/js/select2.js"></script>

<?php $per_page = get_settings('business_settings', 'posts_per_page', 6); ?>
<?php 
$filter_type = get_settings('banner_settings','search_panel_filter_type','basic_options'); // added on version 1.8
$state_active = get_settings('business_settings', 'show_state_province', 'yes'); //added on version 1.7
?>
<div class="row">
    <!-- Sidebar column -->
    <div class="col-md-3 col-sm-3">
        <div class="sidebar">
            <form action="<?php echo site_url('show/getresult_ajax/grid/'.$per_page);?>" method="post" id="advance-search-form" class="form">

                <div class="s-widget">
                    <h5><i class="fa fa-search color"></i>&nbsp; <?php echo lang_key('search_filters'); ?></h5>
                    <!-- Form Group -->
                    <div class="widget-content search">

                        <!-- Search Widget -->
                        <div class="form-group">
                          <div class="input-group">
                            <input class="form-control" type="text" placeholder="<?php echo lang_key('type_anything');?>" value="<?php echo (isset($data['plainkey']))?rawurldecode($data['plainkey']):'';?>" name="plainkey">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-color"><?php echo lang_key('search'); ?></button>
                            </span>
                          </div>
                        </div>

                        <?php if($filter_type=='advanced_options_with_country_state'){?>
                        <?php $country_temp = (isset($data['country']))?$data['country']:'';?>
                        <div class="form-group">
                            <select name="country" id="country" class="form-control chosen-select">
                                <option data-name="" value=""><?php echo lang_key('select_country');?></option>
                                <?php foreach (get_all_locations_by_type('country')->result() as $row) {
                                    $sel = ($row->id==$country_temp)?'selected="selected"':'';
                                    ?>
                                    <option data-name="<?php echo $row->name;?>" value="<?php echo $row->id;?>" <?php echo $sel;?>><?php echo lang_key($row->name);?></option>
                                <?php }?>
                            </select>
                        </div>

                        <?php if($state_active=='yes'){?>
                        <div>
                            <div class="form-group">
                                 <?php $state_temp = (isset($data['state']))?$data['state']:'';?>
                                <select name="state" id="state" class="form-control chosen-select">
                                    <option value="<?php echo $state_temp?>" selected="selected"></option>
                                </select>
                            </div>
                        </div>
                        <?php }?>

                        <div>
                            <div class="form-group">
                                <?php $city_field_type = 'dropdown'; ?>
                                <input type="hidden" id="selected_city" value="<?php echo(set_value('selected_city')!='')?set_value('selected_city'):'';?>">
                                <?php if ($city_field_type=='dropdown') {?>
                                <?php $city_temp = (isset($data['city']))?$data['city']:'';?>
                                <select name="city" id="city_dropdown" class="form-control chosen-select">                                        
                                    <option value=""><?php echo lang_key('select_one');?></option>
                                    <option value="<?php echo $city_temp?>" selected="selected"></option>
                                </select>
                                <?php }else {?>
                                <input type="text" id="city" name="city" value="<?php echo(set_value('city')!='')?set_value('city'):'';?>" placeholder="<?php echo lang_key('city');?>" class="form-control" >
                                <span class="help-inline city-loading">&nbsp;</span>
                                <?php }?>
                            </div>
                        </div>

                        <?php }else{?>

                        <div class="form-group">
                            <?php $city_temp = (isset($data['city']))?$data['city']:'any';?>
                            <select name="city" class="form-control chosen-select">
                                <option data-name="" value="any"><?php echo lang_key('any_city');?></option>
                                <?php foreach (get_all_cities_by_use()->result() as $row) {
                                    $sel = ($row->id==$city_temp)?'selected="selected"':'';
                                    ?>
                                    <option data-name="<?php echo $row->name;?>" class="cities city-<?php echo $row->parent;?>" value="<?php echo $row->id;?>" <?php echo $sel;?>><?php echo lang_key($row->name);?></option>
                                <?php }?>
                            </select>
                        </div>

                        <?php }?>

                        <div class="form-group">
                            <?php $category_temp = (isset($data['category']))?$data['category']:-1;?>
                            <select name="category" class="form-control chosen-select">
                                <option value="any"><?php echo lang_key('any_category');?></option>
                                <?php foreach ($categories as $row) {
                                    $sub = ($row->parent!=0)?'--':'';
                                    $sel = ($category_temp==$row->id)?'selected="selected"':'';
                                    ?>
                                    <option value="<?php echo $row->id;?>" <?php echo $sel;?>><?php echo $sub.lang_key($row->title);?></option>
                                <?php
                                }?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label><?php echo lang_key('distance_around_my_position'); ?>: <span class="price-range-amount-view" id="amount"></span></label>
                            <div class="clearfix"></div>
                            <a href="javascript:void(0);" onclick="findLocation()" class="btn btn-orange btn-xs find-my-location"><i class="fa fa-location-arrow"></i></a>
                            <div id="slider-price-sell" class="price-range-slider"></div>
                            <input type="hidden" id="price-slider-sell" name="distance" value="">
                            <input type="hidden" id="geo_lat" name="geo_lat" value="<?php echo (isset($data['geo_lat']))?$data['geo_lat']:''; ?>">
                            <input type="hidden" id="geo_lng" name="geo_lng" value="<?php echo (isset($data['geo_lng']))?$data['geo_lng']:''; ?>">
                        </div>


                        <div class="form-group">
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
                        <!-- Button -->
                        <button class="btn btn-red submit-search-button" type="submit"><?php echo lang_key('search');?></button>&nbsp;
                        <button class="btn btn-default reset" type="reset"><?php echo lang_key('reset');?></button>
                    </div>

                </div>

            </form>
        </div>
        


    </div> <!-- end of left bar -->

    <!-- Mainbar column -->
    <div class="col-md-9 col-sm-9">
      <h5><span style="position:relative;top:12px;"><?php echo lang_key('results'); ?></span>
        <div class="pull-right list-switcher">
          <a class="result-grid" href="#"><i class="fa fa-th "></i></a>
          <a class="result-list" href="#"><i class="fa fa-th-list "></i></a>
          <a class="result-map" href="#"><i class="fa fa-map-marker "></i></a>
          <form id="toggle-form" action="<?php echo site_url('show/toggle/map');?>" method="post">
            <input type="hidden" name="url" value="<?php echo current_url();?>">
          </form>
        </div>
      </h5>
      <span class="results">   
      </span>
      <div class="ajax-loading recent-loading"><img src="<?php echo theme_url();?>/assets/img/loading.gif" alt="loading..."></div>
      <a href="" class="load-more-recent btn btn-blue" style="width:100%"><?php echo lang_key('load_more_posts');?></a>
    </div> <!-- end of main content -->
</div><!-- end of row -->
<?php
//added on version  1.7
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

    var per_page = '<?php echo $per_page;?>';
    var recent_count = '<?php echo $per_page;?>';

    $(document).ready(function(){
        if(!isAndroid) {
            $('.chosen-select').select2({
                theme: "classic"
            });
        }

        <?php $distance = (isset($data['distance']))? $data['distance'] != '' ?  $data['distance']  :  $this->config->item('default_distance') : $this->config->item('default_distance');  ?>

        var distance = parseInt('<?php echo $distance; ?>');

        var distance_unit = '<?php echo lang_key(get_settings("business_settings", "show_distance_in", "miles")); ?>';

        $("#slider-price-sell").slider({

            min: <?php echo $this->config->item('min_distance');?>,

            max: <?php echo $this->config->item('max_distance');?>,

            value: distance,

            slide: function (event, ui) {

                $("#price-slider-sell").val(ui.value);
                $("#amount").html( ui.value + ' ' + distance_unit );

            }

        });
        $("#price-slider-sell").val(distance);
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

</script>

<script type="text/javascript">
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
                    var sel_country = '<?php echo (isset($data["country"]))?$data["country"]:"";?>';
                    var sel_state   = '<?php echo (isset($data["state"]))?$data["state"]:"";?>';
                    if(val==sel_country)
                    jQuery('#state').val(sel_state);
                    else
                    jQuery('#state').val('');
                    jQuery('#state').focus();
                    jQuery('#state').trigger('change');
                    <?php }else{?>
                    var sel_country = '<?php echo (isset($data["country"]))?$data["country"]:"";?>';
                    var sel_city   = '<?php echo (isset($data["selected_city"]))?$data["selected_city"]:"";?>';
                    var city   = '<?php echo (isset($data["city"]))?$data["city"]:"";?>';
                    if(city_field_type=='dropdown')
                    populate_city(val); //populate the city drop down
                    if(val==sel_country)
                    {
                        jQuery('#selected_city').val(sel_city);
                        jQuery('#city_dropdown').val(city);
                    }
                    else
                    {
                        jQuery('#selected_city').val(sel_city);
                        jQuery('#city_dropdown').val('');            
                    }
                    <?php }?>

                }
            );
         }).change();

        var city_field_type =  'dropdown' ;

            jQuery('#state').change(function(){
                <?php if($state_active=='yes'){?>
                var val = jQuery(this).val();
                var sel_state   = '<?php echo (isset($data["state"]))?$data["state"]:"";?>';
                var sel_city   = '<?php echo (isset($data["selected_city"]))?$data["selected_city"]:"";?>';
                var city   = '<?php echo (isset($data["city"]))?$data["city"]:"";?>';
                
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
                var sel_state   = '<?php echo (isset($data["state"]))?$data["state"]:"";?>';
                var sel_country = '<?php echo (isset($data["country"]))?$data["country"]:"";?>';
                var sel_city    = '<?php echo (isset($data["city"]))?$data["city"]:"";?>';
                <?php if($state_active=='yes'){?>
                if(parent==sel_state)
                {
                  jQuery('#city_dropdown').val(sel_city);
                  if(!isAndroid) {
                    $("#city_dropdown").select2("val", sel_city);
                  }                  
                }
                else
                {
                  jQuery('#city_dropdown').val('');
                  if(!isAndroid) {
                    $("#city_dropdown").select2("val", '');
                  }                                    
                }
                <?php }else{?>
                if(parent==sel_country)
                {
                  jQuery('#city_dropdown').val(sel_city);
                  if(!isAndroid) {
                    $("#city_dropdown").select2("val", sel_city);
                  }                  
                }
                else
                {
                  jQuery('#city_dropdown').val('');
                  if(!isAndroid) {
                    $("#city_dropdown").select2("val", '');
                  }                                    
                }
                <?php }?>
            }
        );
}

jQuery(document).ready(function(){

  jQuery('.reset').click(function(e){
    e.preventDefault();
    jQuery('#advance-search-form input').each(function(){
      jQuery(this).val('');
    });

    jQuery('select[name=city]').select2("val", "any");
    jQuery('select[name=category]').select2("val", "any");
    jQuery('select[name=sort_by]').select2("val", "");
    
    jQuery('#advance-search-form').submit();
  });

  jQuery('#advance-search-form').submit(function(e){
    e.preventDefault();
    var loadUrl = jQuery('#advance-search-form').attr('action');
    var data = jQuery('#advance-search-form').serialize();
    jQuery('.recent-loading').show(); 

    jQuery.post(
        loadUrl,
        data,
        function(result){          
           
           if(result.url!=window.location){
             window.history.pushState({path:result.url},'',result.url);
           }
           
           jQuery('.results').html(result.content);
           jQuery('.recent-loading').hide(); 
           
           if(jQuery('.results .img-responsive').length<recent_count)
           {
                jQuery('.load-more-recent').hide();
           }
           fix_grid_height();
          
        },
        'json'
    );

  });

  jQuery('.load-more-recent').click(function(e){
      e.preventDefault();
      var next = parseInt(recent_count)+parseInt(per_page);

      var url = jQuery('#advance-search-form').attr('action');
      url = url.replace('/'+recent_count,'/'+next);
      jQuery('#advance-search-form').attr('action',url);
      recent_count = next;

      jQuery('#advance-search-form').submit();  
  });

  jQuery('.result-grid').click(function(e){
      e.preventDefault();
      jQuery('.result-grid').addClass('selected');
      jQuery('.result-list').removeClass('selected');

      var url = jQuery('#advance-search-form').attr('action');
      var action = url.replace('/list/','/grid/');
      jQuery('#advance-search-form').attr('action',action);
      jQuery('#advance-search-form').submit();
    });

  jQuery('.result-list').click(function(e){
    e.preventDefault();
    jQuery('.result-grid').removeClass('selected');
    jQuery('.result-list').addClass('selected');

    var url = jQuery('#advance-search-form').attr('action');
    var action = url.replace('/grid/','/list/');
    jQuery('#advance-search-form').attr('action',action);
    jQuery('#advance-search-form').submit();

  });

  jQuery('.result-map').click(function(e){
    e.preventDefault();
    jQuery('#toggle-form').submit();
  });

  var initialURL = location.href;

});


</script>