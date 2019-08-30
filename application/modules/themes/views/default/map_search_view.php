<!-- file updated on version 1.8 -->
<style>
    #classify-main-map{
        background-color: #e5e3df;
        height: 450px;
    }
    #classify-main-map img { max-width: none; }
    .map-search-panel{
        background: none repeat scroll 0 0 #fff;
        float: left;
        height: 450px;
        left: 0;
        position: absolute;
        top: 154px;
        width: 265px;
    }
    .hide-search-panel{
        position: relative;
        float: right;
        top:8px;
        right: 10px;
    }

</style>
<link href="<?php echo theme_url();?>/assets/jquery-ui/jquery-ui.css" rel="stylesheet">
<script src="<?php echo theme_url();?>/assets/jquery-ui/jquery-ui.js"></script>

<link href="<?php echo theme_url();?>/assets/css/select2.css" rel="stylesheet">
<script src="<?php echo theme_url();?>/assets/js/select2.js"></script>

<?php 
$filter_type = get_settings('banner_settings','search_panel_filter_type','basic_options'); // added on version 1.8
$state_active = get_settings('business_settings', 'show_state_province', 'yes'); //added on version 1.7
?>

<div class="row">

              <!-- Sidebar column -->
              <div class="col-md-3">
                  <!-- Nav tab style 2 starts -->
                  <div class="nav-tabs-two">
                      <!-- Nav tabs -->
                      <ul class="nav nav-tabs">
                          <li class="active"><a href="nav-tabs.html#home-2" data-toggle="tab"><?php echo lang_key('search'); ?></a></li>
                          <li><a href="nav-tabs.html#profile-2" data-toggle="tab"><?php echo lang_key('results'); ?></a></li>

                      </ul>
                      <!-- Tab content -->
                      <div class="tab-content" >
                          <div class="tab-pane fade in active" id="home-2">
                              <form action="<?php echo site_url('show/getresult_ajax/grid/all/json');?>" method="post" id="advance-search-form" class="form">
                                     
                                  <!-- Search Widget -->
                                  <div class="form-group">
                                    <div class="input-group">
                                      <input class="form-control" type="text" placeholder="<?php echo lang_key('type_anything');?>" value="<?php echo (isset($data['plainkey']))?rawurldecode($data['plainkey']):'';?>" name="plainkey">
                                      <span class="input-group-btn">
                                          <button type="submit" class="btn btn-color"><?php echo lang_key('search'); ?></button>
                                      </span>
                                    </div>
                                  </div>
                                  <!-- Form Group -->

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
                                      <select name="city" class="form-control chosen-select">
                                          <option data-name="" value="any"><?php echo lang_key('any_city');?></option>
                                          <?php foreach (get_all_cities_by_use()->result() as $row) {
                                              $sel = ($row->id==set_value('city'))?'selected="selected"':'';
                                              ?>
                                              <option data-name="<?php echo $row->name;?>" class="cities city-<?php echo $row->parent;?>" value="<?php echo $row->id;?>" <?php echo $sel;?>><?php echo lang_key($row->name);?></option>
                                          <?php }?>
                                      </select>
                                  </div>
                        <?php }?>
                                  
                                  <div class="form-group">
                                      <select name="category" class="form-control chosen-select">
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
                              </form>
                          </div>
                          <div class="tab-pane fade" id="profile-2">
                              <div id="content-details" class="content-pane">
                                  <div class="left-pane-data">
                                      <ul id="marker_list" class="listing-details">
                                      </ul>
                                  </div>
                              </div>
                          </div>

                      </div>
                  </div>
                  <!-- Nav tab style 2 ends -->
                  
                  


              </div> <!-- end of left bar -->

              <!-- Mainbar column -->
              <div class="col-md-9">
                <h5><span style="position:relative;top:0px;"><?php echo lang_key('results'); ?></span>
                  <div class="pull-right map-switcher">
                    <a class="show-plain" view_type="grid" href="#"><i class="fa fa-th "></i></a>
                    <a class="show-plain" view_type="list" href="#"><i class="fa fa-th-list"></i></a>
                    <a class="result-map selected" href="#"><i class="fa fa-map-marker "></i></a>

                    <form id="toggle-form" action="<?php echo site_url('show/toggle/plain');?>" method="post">
                      <input type="hidden" name="url" value="<?php echo current_url();?>">
                      <input type="hidden" name="view_type" id="view_type" value="grid">
                    </form>
                  </div>
                </h5>

                <span class="results">
                    <section id="big-map">
                        <div id="classify-main-map">
                        </div>
                    </section>
                </span>
                
              </div> <!-- end of main content -->
              
              
            </div>

<?php
//added on version 1.7
$isSsl = (strpos('-'.base_url(), 'https://')>0)?'1':'0';
?>
<?php
// added on version 1.6
$map_api_key = get_settings('banner_settings','map_api_key','');
$api_key_text = ($map_api_key!='')?"&key=$map_api_key":'';
?>
<script src="//maps.googleapis.com/maps/api/js?v=3.exp&libraries=places<?php echo $api_key_text;?>"></script>
<script src="<?php echo theme_url();?>/assets/js/markercluster.min.js"></script>
<script src="<?php echo theme_url();?>/assets/js/map-icons.min.js"></script>
<script src="<?php echo theme_url();?>/assets/js/map_config.js"></script>

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
                var sel_city   = '<?php echo (isset($data["city"]))?$data["city"]:"";?>';
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
            }
        );
}
  jQuery('.show-plain').click(function(e){
    e.preventDefault();
    var view_type = jQuery(this).attr('view_type');
    jQuery('#view_type').val(view_type);
    jQuery('#toggle-form').submit();
  });

var markers = [];
var map;

function result_js(result)
{


    var ul = document.getElementById("marker_list");
    ul.innerHTML = '';
    pointMap(result.data);
//  alert(result.data);
}

function initialize() {


    var myLatitude = parseFloat('<?php echo get_settings("banner_settings","map_latitude", 37.2718745); ?>');
    var myLongitude = parseFloat('<?php echo get_settings("banner_settings","map_longitude", -119.2704153); ?>');
    var zoomLevel = parseInt('<?php echo get_settings("banner_settings","map_zoom",8); ?>');

    var myLatlng = new google.maps.LatLng(myLatitude,myLongitude);
    var mapOptions = {
        scrollwheel: false,
        zoom: zoomLevel,
        center: myLatlng,
        mapTypeControl: true,
        mapTypeControlOptions: {
            style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
            position: google.maps.ControlPosition.RIGHT_BOTTOM
        },
        zoomControl: true,
        zoomControlOptions: {
            style: google.maps.ZoomControlStyle.SMALL,
            position: google.maps.ControlPosition.RIGHT_CENTER
        },
        panControl: true,
        panControlOptions: {
            position: google.maps.ControlPosition.RIGHT_TOP
        },
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        styles: MAP_STYLE
    }
    map = new google.maps.Map(document.getElementById('classify-main-map'), mapOptions);


}

function pointMap(map_data){
    setAllMap(null);
    markers = [];

    if(map_data.posts.length > 0){
        var infowindow = new google.maps.InfoWindow({
            content: "Hello World"
        });

        var marker, i;

        var infoContentString = [];
        console.log(map);
        map.setCenter(new google.maps.LatLng(map_data.posts[0].latitude,map_data.posts[0].longitude));
        for (i = 0; i < map_data.posts.length; i++) {

            marker = new Marker({
                position: new google.maps.LatLng(map_data.posts[i].latitude, map_data.posts[i].longitude),
                map: map,
                title: map_data.posts[i].post_title,
                zIndex: 9,
                icon: {
                    path: SQUARE_PIN,
                    fillColor: map_data.posts[i].fa_color,
                    fillOpacity: 1,
                    strokeColor: '',
                    strokeWeight: 0,
                    scale: 1/3
                },
                map_icon_label: '<i class="fa '+ map_data.posts[i].fa_icon + '"></i>'
            });
            infoContentString[i] = '<div class="clearfix"></div><div class="img-box-4 text-center map-grid"><div class="img-box-4-item"><div class="image-style-one"><a href="'+ map_data.posts[i].detail_link + '"><img class="img-responsive" alt="" src="'+ map_data.posts[i].featured_image_url + '"></a></div>'
            + '<div class="img-box-4-content"><h4 class="item-title"><a href="'+ map_data.posts[i].detail_link + '">'+ map_data.posts[i].post_title + '</a></h4><div class="bor bg-red"></div><div class="row"><div class="info-dta info-price">'
            + map_data.posts[i].price + '</div></div><div class="row"><div class="info-dta info-price">'+ map_data.posts[i].post_short_address + '</div></div>' + '</div></div></div>';

            google.maps.event.addListener(marker, 'click', (function (marker, i) {
                return function () {
                    infowindow.setContent(infoContentString[i]);
                    infowindow.open(map, marker);
                    map.setCenter(this.getPosition());

                }
            })(marker, i));
            createMarkerButton(marker, map_data.posts[i]);
            markers.push(marker);
//                infoContentString.push(contentString);
        }
    }


    var markerCluster = new MarkerClusterer(map, markers);
}


function createMarkerButton(marker, post) {
    //Creates a sidebar button
    var ul = document.getElementById("marker_list");
    var li = document.createElement("li");
    li.className = "listing-single-item";

    var inner_html = '<span class="property-name">' + post.post_title + '</span>' +
        '<div class="property-details clearFix">' + '<div class="property-img floatLeft">' +
        '<img width="40" height="40" src="'+ post.featured_image_url + '">' + '</div>' + '<div class="property-info floatLeft">' +
        '<div class="property-cost"><i class="fa fa-star active-star-color"></i> ' + post.rating + '</div>' + '<div class="property-location">' +
        post.post_short_address + '</div>' + '<div class="property-posted-date">' + post.parent_category +
        '</div></div></div>';

    li.innerHTML = inner_html;

    ul.appendChild(li);
    google.maps.event.addDomListener(li, "click", function(){
        google.maps.event.trigger(marker, "click");
    });
}

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

                        var lat = arr[0];
                        var lng = arr[1];

                        $('#geo_lat').val(lat);
                        $('#geo_lng').val(lng);

                        var geolocate = new google.maps.LatLng(lat, lng);
                        marker = new google.maps.Marker({
                        position: new google.maps.LatLng(lat , lng),
                        map: map,
                        title: 'My Location'

                      });
                      map.setCenter(geolocate);
                      map.setZoom(12);

                }, "jsonp");
                
            }
        }
        else
        {
            if(!!navigator.geolocation) {

                navigator.geolocation.getCurrentPosition(function(position) {

                      $('#geo_lat').val(position.coords.latitude);
                      $('#geo_lng').val(position.coords.longitude);
                      var geolocate = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                      marker = new google.maps.Marker({
                          position: new google.maps.LatLng(position.coords.latitude , position.coords.longitude),
                          map: map,
                          title: 'My Location'

                      });
                      map.setCenter(geolocate);
                      map.setZoom(12);


                });

            } else {
                alert('No Geolocation Support.');
            }            
        }
    }
    //end




function setAllMap(map) {
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(map);
    }
}



google.maps.event.addDomListener(window, 'load', initialize);

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


          jQuery.post(
              loadUrl,
              data,
              function(result){
                  if(result.url!=window.location){
                      window.history.pushState({path:result.url},'',result.url);
                  }
                  result_js(result);
              },
              'json'
          );

      });


      var initialURL = location.href;

      $(window).load(function(){
          $(".content-pane").mCustomScrollbar();
      });

      jQuery('#advance-search-form').submit();
  });
</script>