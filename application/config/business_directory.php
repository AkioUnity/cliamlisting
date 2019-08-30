<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['blog_post_types']	= array('blog'=>'blog_post','article'=>'article','news'=>'news');


$config['decimal_point'] = '.';
$config['thousand_separator'] = ',';


#setting this config value to non empty will override the packge price currency.
#so if you have paypal enabled then remeber to use a currency which is supported by paypal. Otherwise your paypal payment will not work
#use this settings only if you want to enable bank payment and disable paypal payment
$config['package_currency'] = '';

#example
#$config['package_currency'] = 'USD';


#active languages 
$config['active_languages'] = array('en'=>'English','es'=>'Spanish','ru'=>'Russian','ar'=>'Arabic','de'=>'German','fr'=>'French','it'=>'Italian','pt'=>'Portuguese','zh'=>'Chinese (Simplified)','tr'=>'Turkish','hi'=>'Hindi','bn'=>'Bangla');
//$config['active_languages'] = array('en'=>'English');


#use ssl
$config['use_ssl'] = 'no';

#distance search slider settings
$config['min_distance'] = 1;
$config['max_distance'] = 500;
$config['default_distance'] = 25;

#hide opening hours
$config['hide_opening_hours'] = 0; // set the value to 1 if you want to hide opening hour

$config['sharethis_publisher_id'] = 'd866253d-fd08-403f-a8d1-bcd324c8163c'; // set your sharethis publisher id here

$config['send_notification_before_post_expiration'] = 'yes'; //set 'yes' or 'no'
$config['send_notification_before'] = 2; // no of days

// added on version 1.5
$config['opening_hour_format'] = 24; // 24 = 24 hours , 12 = 12 hours format

//added on version 1.6
$config['camera_spin_options'] = array('0','45','90','135','180','225','270','315'); 