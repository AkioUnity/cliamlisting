<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('get_location_name_by_id'))
{
	function get_location_name_by_id ($id)
	{
		if($id==0)
			return '';

		$CI = get_instance();
		$CI->load->database();
		$query = $CI->db->get_where('locations',array('id'=>$id));
		if($query->num_rows()>0)
		{
			$row = $query->row();
			$name =  trim(preg_replace('/\s\s+/', ' ',$row->name));
			$name = str_replace("'", "", $name);
			return lang_key($name);
		}
		else
		{
			return 'N/A';
		}
	}
}

if ( ! function_exists('show_price'))
{
	function show_price ($price,$contact_for_price='')
	{
		$CI = get_instance();
		$CI->config->load('business_directory');
		$decimal_point = ($CI->config->item('decimal_point')!='')?$CI->config->item('decimal_point'):'.';
		$thousand_separator = ($CI->config->item('thousand_separator')!='')?$CI->config->item('thousand_separator'):',';
		$currency_placing = get_settings('business_settings','currency_placing','before_with_no_gap');

		if($contact_for_price==1) {
			return lang_key('contact_for_price');
		}
		else if($currency_placing=='before_with_no_gap')
		{
			return $CI->session->userdata('system_currency').''.number_format($price, 0, $decimal_point, $thousand_separator);
		}
		else if($currency_placing=='before_with_gap')
		{
			return $CI->session->userdata('system_currency').' '.number_format($price, 0, $decimal_point, $thousand_separator);
		}
		else if($currency_placing=='after_with_no_gap')
		{
			return number_format($price, 0, $decimal_point, $thousand_separator).''.$CI->session->userdata('system_currency');
		}
		else
		{
			return number_format($price, 0, $decimal_point, $thousand_separator).' '.$CI->session->userdata('system_currency');
		}
	}
}

if ( ! function_exists('show_package_price'))
{
	function show_package_price ($price)
	{
		$CI = get_instance();
		$currency_placing = get_settings('business_settings','currency_placing','before_with_no_gap');
		$currency_type = get_settings('business_settings','system_currency_type','1');

		$bank_currency = get_settings('package_settings','bank_currency','use_paypal');
		if($bank_currency!='use_paypal')
			$currency = $bank_currency;
		else		
			$currency = get_settings('paypal_settings','currency','USD');

		if($currency_type!=1)
		{
			$currency = get_currency_icon($currency);
		}
		else
			$currency = lang_key($currency);
		
		if($currency_placing=='before_with_no_gap')
		{
			return $currency.''.number_format($price,2);
		}
		else if($currency_placing=='before_with_gap')
		{
			return $currency.' '.number_format($price,2);
		}
		else if($currency_placing=='after_with_no_gap')
		{
			return number_format($price,2).''.$currency;
		}
		else
		{
			return number_format($price,2).' '.$currency;
		}
	}
}

if ( ! function_exists('is_user_package_expired'))
{
	function is_user_package_expired ($user_id)
	{

		$CI = get_instance();
		$CI->load->database();
		$query = $CI->db->get_where('users',array('id'=>$user_id));
		if($query->num_rows()>0)
		{
			$row = $query->row();
			if($row->user_type==1)
				return 0; #admin will have no expire date
		}

		$expirtion_date = get_user_meta($user_id,'expirtion_date','');
		if($expirtion_date=='')
			return 1;
		elseif (strtotime($expirtion_date)<time()) 
		{
			return 1;
		}
		else
			return 0;
	}
}

if ( ! function_exists('get_package_name_by_id'))
{
	function get_package_name_by_id ($id)
	{
		$CI = get_instance();
		$query = $CI->db->get_where('packages',array('id'=>$id));
		if($query->num_rows()>0)
		{
			$row = $query->row();
			return $row->title;
		}
		else
		{
			return 'N/A';
		}
	}
}

if ( ! function_exists('get_user_properties_count'))
{
	function get_user_properties_count ($user_id)
	{
		$CI = get_instance();
		$CI->load->database();
		$CI->db->where('created_by',$user_id);
		$query = $CI->db->get_where('posts',array('status'=>1));
		return $query->num_rows();
	}
}

if ( ! function_exists('get_all_currencies'))
{
	function get_all_currencies($key=0)
	{
		$currencies= array(
			"ALL"=> array("Albania, Leke", "4c, 65, 6b"),
			"AFN"=> array("Afghanistan, Afghanis", "60b"),
			"ARS"=> array("Argentina, Pesos", "24"),
			"AWG"=> array("Aruba, Guilders (also called Florins)", "192"),
			"AUD"=> array("Australia, Dollars", "24"),
			"AZN"=> array("Azerbaijan, New Manats", "43c, 430, 43d"),
			"BSD"=> array("Bahamas, Dollars", "24"),
			"BBD"=> array("Barbados, Dollars", "24"),
			"BYR"=> array("Belarus, Rubles", "70, 2e"),
			"BZD"=> array("Belize, Dollars", "42, 5a, 24"),
			"BMD"=> array("Bermuda, Dollars", "24"),
			"BOB"=> array("Bolivia, Bolivianos", "24, 62"),
			"BAM"=> array("Bosnia and Herzegovina, Convertible Marka", "4b, 4d"),
			"BWP"=> array("Botswana, Pulas", "50"),
			"BGN"=> array("Bulgaria, Leva", "43b, 432"),
			"BRL"=> array("Brazil, Reais", "52, 24"),
			"BND"=> array("Brunei Darussalam, Dollars", "24"),
			"KHR"=> array("Cambodia, Riels", "17db"),
			"XOF"=> array("Cameroon, CFA franc",""),
			"CAD"=> array("Canada, Dollars", "24"),
			"KYD"=> array("Cayman Islands, Dollars", "24"),
			"CLP"=> array("Chile, Pesos", "24"),
			"CNY"=> array("China, Yuan Renminbi", "a5"),
			"COP"=> array("Colombia, Pesos", "24"),
			"CRC"=> array("Costa Rica, Colón", "20a1"),
			"HRK"=> array("Croatia, Kuna", "6b, 6e"),
			"CUP"=> array("Cuba, Pesos", "20b1"),
			"CZK"=> array("Czech Republic, Koruny", "4b, 10d"),
			"DKK"=> array("Denmark, Kroner", "6b, 72"),
			"DOP"=> array("Dominican Republic, Pesos", "52, 44, 24"),
			"XCD"=> array("East Caribbean, Dollars", "24"),
			"EGP"=> array("Egypt, Pounds", "a3"),
			"SVC"=> array("El Salvador, Colones", "24"),
			"EEK"=> array("Estonia, Krooni", "6b, 72"),
			"EUR"=> array("Euro", "20ac"),
			"FKP"=> array("Falkland Islands, Pounds", "a3"),
			"FJD"=> array("Fiji, Dollars", "24"),
			"GHC"=> array("Ghana, Cedis", "a2"),
			"GIP"=> array("Gibraltar, Pounds", "a3"),
			"GTQ"=> array("Guatemala, Quetzales", "51"),
			"GGP"=> array("Guernsey, Pounds", "a3"),
			"GYD"=> array("Guyana, Dollars", "24"),
			"HNL"=> array("Honduras, Lempiras", "4c"),
			"HKD"=> array("Hong Kong, Dollars", "24"),
			"HUF"=> array("Hungary, Forint", "46, 74"),
			"ISK"=> array("Iceland, Kronur", "6b, 72"),
			"INR"=> array("India, Rupees", "20a8"),
			"IDR"=> array("Indonesia, Rupiahs", "52, 70"),
			"IRR"=> array("Iran, Rials", "fdfc"),
			"IMP"=> array("Isle of Man, Pounds", "a3"),
			"ILS"=> array("Israel, New Shekels", "20aa"),
			"JMD"=> array("Jamaica, Dollars", "4a, 24"),
			"JPY"=> array("Japan, Yen", "a5"),
			"JEP"=> array("Jersey, Pounds", "a3"),
			"KZT"=> array("Kazakhstan, Tenge", "43b, 432"),
			"KES"=> array("Kenyan Shilling", "4b, 73, 68, 73"),
			"KGS"=> array("Kyrgyzstan, Soms", "43b, 432"),
			"LAK"=> array("Laos, Kips", "20ad"),
			"LVL"=> array("Latvia, Lati", "4c, 73"),
			"LBP"=> array("Lebanon, Pounds", "a3"),
			"LRD"=> array("Liberia, Dollars", "24"),
			"LTL"=> array("Lithuania, Litai", "4c, 74"),
			"MKD"=> array("Macedonia, Denars", "434, 435, 43d"),
			"MYR"=> array("Malaysia, Ringgits", "52, 4d"),
			"MUR"=> array("Mauritius, Rupees", "20a8"),
			"MXN"=> array("Mexico, Pesos", "24"),
			"MNT"=> array("Mongolia, Tugriks", "20ae"),
			"MZN"=> array("Mozambique, Meticais", "4d, 54"),
			"NAD"=> array("Namibia, Dollars", "24"),
			"NPR"=> array("Nepal, Rupees", "20a8"),
			"ANG"=> array("Netherlands Antilles, Guilders (also called Florins)", "192"),
			"NZD"=> array("New Zealand, Dollars", "24"),
			"NIO"=> array("Nicaragua, Cordobas", "43, 24"),
			"NGN"=> array("Nigeria, Nairas", "20a6"),
			"KPW"=> array("North Korea, Won", "20a9"),
			"NOK"=> array("Norway, Krone", "6b, 72"),
			"OMR"=> array("Oman, Rials", "fdfc"),
			"PKR"=> array("Pakistan, Rupees", "20a8"),
			"PAB"=> array("Panama, Balboa", "42, 2f, 2e"),
			"PYG"=> array("Paraguay, Guarani", "47, 73"),
			"PEN"=> array("Peru, Nuevos Soles", "53, 2f, 2e"),
			"PHP"=> array("Philippines, Pesos", "50, 68, 70"),
			"PLN"=> array("Poland, Zlotych", "7a, 142"),
			"QAR"=> array("Qatar, Rials", "fdfc"),
			"RON"=> array("Romania, New Lei", "6c, 65, 69"),
			"RUB"=> array("Russia, Rubles", "440, 443, 431"),
			"SHP"=> array("Saint Helena, Pounds", "a3"),
			"SAR"=> array("Saudi Arabia, Riyals", "fdfc"),
			"RSD"=> array("Serbia, Dinars", "414, 438, 43d, 2e"),
			"SCR"=> array("Seychelles, Rupees", "20a8"),
			"SGD"=> array("Singapore, Dollars", "24"),
			"SBD"=> array("Solomon Islands, Dollars", "24"),
			"SOS"=> array("Somalia, Shillings", "53"),
			"ZAR"=> array("South Africa, Rand", "52"),
			"KRW"=> array("South Korea, Won", "20a9"),
			"LKR"=> array("Sri Lanka, Rupees", "20a8"),
			"SEK"=> array("Sweden, Kronor", "6b, 72"),
			"CHF"=> array("Switzerland, Francs", "43, 48, 46"),
			"SRD"=> array("Suriname, Dollars", "24"),
			"SYP"=> array("Syria, Pounds", "a3"),
			"TWD"=> array("Taiwan, New Dollars", "4e, 54, 24"),
			"THB"=> array("Thailand, Baht", "e3f"),
			"TTD"=> array("Trinidad and Tobago, Dollars", "54, 54, 24"),
			"TRY"=> array("Turkey, Lira", "54, 4c"),
			"TRL"=> array("Turkey, Liras", "20a4"),
			"TVD"=> array("Tuvalu, Dollars", "24"),
			"UAH"=> array("Ukraine, Hryvnia", "20b4"),
			"AED"=>array("United Arab Emirates, Dirham","62f, 2e, 625"),
			"GBP"=> array("United Kingdom, Pounds", "a3"),
			"USD"=> array("United States of America, Dollars", "24"),
			"UYU"=> array("Uruguay, Pesos", "24, 55"),
			"UZS"=> array("Uzbekistan, Sums", "43b, 432"),
			"VEF"=> array("Venezuela, Bolivares Fuertes", "42, 73"),
			"VND"=> array("Vietnam, Dong", "20ab"),
			"YER"=> array("Yemen, Rials", "fdfc"),
			"ZWD"=> array("Zimbabwe, Zimbabwe Dollars", "5a, 24"));

		return $currencies;
	}
}

if ( ! function_exists('get_currency_icon'))
{
	function get_currency_icon($currency = null)
	{
		$currencies = get_all_currencies();
		$currencySymbol = '';
	
		if($currency == null) {
    		return 'N/A';
      	}

    	$symbol = $currencies[$currency][1];
    	if($symbol=='')
    		return $currency;
    	$symbols = explode(', ', $symbol);
	    if(is_array($symbols)) {
	      $symbol = "";
	      foreach($symbols as $temp) {
	        $symbol .= '&#x'.$temp.';';
	        }
	    }
	    else {
	      $symbol = '&#x'.$symbol.';';
	    }

	    return $symbol;
	}
}

if ( ! function_exists('get_payment_status_title_by_value'))
{
	function get_payment_status_title_by_value($key=0,$label='')
	{
		$types = array("DBC_DELETED","DBC_ACTIVE","DBC_PENDING");
		if($label=='labelled') {
			if($key==0) {
				return '<span class="label label-danger">'.lang_key($types[$key]).'</span>';
			}
			else if($key==1) {
				return '<span class="label label-success">'.lang_key($types[$key]).'</span>';
			}
			return (isset($types[$key]))?'<span class="label label-info">'.lang_key($types[$key]).'</span>':'N/A';
		}
		return (isset($types[$key]))?lang_key($types[$key]):'N/A';
	}
}

if ( ! function_exists('get_status_title_by_value'))
{
	function get_status_title_by_value($key=0)
	{
		$types = array("DBC_DELETED","DBC_ACTIVE","DBC_PENDING","DBC_PAYMENT_PENDING","DBC_EXPIRED");
		
		$front = '';
		$back  = '</span>';
		if($types[$key]=='DBC_DELETED')
		{
			$front = '<span class="label label-danger">';
		}
		else if($types[$key]=='DBC_ACTIVE')
		{
			$front = '<span class="label label-success">';
		}
		else if($types[$key]=='DBC_PENDING')
		{
			$front = '<span class="label label-primary">';
		}
		else if($types[$key]=='DBC_PAYMENT_PENDING')
		{
			$front = '<span class="label label-primary">';
		}
		else if($types[$key]=='DBC_EXPIRED')
		{
			$front = '<span class="label label-error">';
		}
		return (isset($types[$key]))?$front.lang_key($types[$key]).$back:'N/A';
	}
}

if ( ! function_exists('get_all_countries'))
{
	function get_all_countries()
	{
		$CI = get_instance();
		$CI->load->database();
		$CI->db->order_by('name', 'asc');
		$query = $CI->db->get_where('locations',array('type'=>'country','status'=>1));
		return $query;
	}
}

if ( ! function_exists('get_all_locations_by_type'))
{
	function get_all_locations_by_type($type='country')
	{
		$CI = get_instance();
		$CI->load->database();
		$CI->db->order_by('name', 'asc');
		$query = $CI->db->get_where('locations',array('type'=>$type,'status'=>1));
		return $query;
	}
}

if ( ! function_exists('get_all_cities_by_use'))
{
	function get_all_cities_by_use()
	{
		$CI = get_instance();
		$CI->load->database();


		$query = $CI->db->get_where('posts',array('status'=>1));
		$cities = array();
		foreach ($query->result() as $post) {
			if(!in_array($post->city,$cities))
				array_push($cities, $post->city);
		}
		
		$CI->db->order_by('name', 'asc');
		if(count($cities)>0)
		$CI->db->where_in('id',$cities);
		$query = $CI->db->get_where('locations',array('status'=>1,'type'=>'city'));
		return $query;
	}
}

if ( ! function_exists('get_all_child_of_location'))
{
	function get_all_child_of_location($type='state', $id= 0)
	{
		$CI = get_instance();
		$CI->load->database();
		$CI->db->order_by('name', 'asc');
		$query = $CI->db->get_where('locations',array('type'=>$type,'status'=>1, 'parent'=> $id));
		return $query;
	}
}

if ( ! function_exists('get_all_location_of_parent_country'))
{
	function get_all_location_of_parent_country($type='state', $id= 0)
	{
		$CI = get_instance();
		$CI->load->database();
		$CI->db->order_by('name', 'asc');
		$query = $CI->db->get_where('locations',array('type'=>$type,'status'=>1, 'parent_country'=> $id));
		return $query;
	}
}

if ( ! function_exists('get_post_count_by_location'))
{
	function get_post_count_by_location($id= 0, $type = '')
	{
		$CI = get_instance();
		$CI->load->database();
		$query = $CI->db->get_where('posts',array('status'=>1, $type=> $id));
		return $query->num_rows();
	}
}

if ( ! function_exists('add_user_meta'))
{
	function add_user_meta ($user_id,$key,$value)
	{
		$CI = get_instance();
		$CI->load->database();
		$query = $CI->db->get_where('user_meta',array('user_id'=>$user_id,'key'=>$key));
		if($query->num_rows()>0)
		{
			$CI->db->update('user_meta',array('value'=>$value),array('user_id'=>$user_id,'key'=>$key));
		}
		else
		{
			$CI->db->insert('user_meta',array('user_id'=>$user_id,'key'=>$key,'value'=>$value));
		}
	}
}

if ( ! function_exists('get_user_meta'))
{
	function get_user_meta ($user_id,$key,$default='')
	{
		$CI = get_instance();
		$CI->load->database();
		$query = $CI->db->get_where('user_meta',array('user_id'=>$user_id,'key'=>$key));
		if($query->num_rows()>0)
		{
			$row = $query->row();
			return $row->value;
		}
		else
		{
			return $default;
		}
	}
}

#-----------------

if ( ! function_exists('add_post_meta'))
{
	function add_post_meta ($post_id,$key,$value)
	{
		$CI = get_instance();
		$CI->load->database();
		$query = $CI->db->get_where('post_meta',array('post_id'=>$post_id,'key'=>$key));
		if($query->num_rows()>0)
		{
			$CI->db->update('post_meta',array('value'=>$value),array('post_id'=>$post_id,'key'=>$key));
		}
		else
		{
			$CI->db->insert('post_meta',array('post_id'=>$post_id,'key'=>$key,'value'=>$value));
		}
	}
}

if ( ! function_exists('get_post_meta'))
{
	function get_post_meta ($post_id,$key,$default='n/a')
	{
		$CI = get_instance();
		$CI->load->database();
		$query = $CI->db->get_where('post_meta',array('post_id'=>$post_id,'key'=>$key));
		if($query->num_rows()>0)
		{
			$row = $query->row();
			return $row->value;
		}
		else
		{
			return $default;
		}
	}
}

if ( ! function_exists('get_post_custom_value'))
{
	function get_post_custom_value ($post_id,$key,$field,$default='n/a')
	{
		$CI = get_instance();
		$CI->load->database();
		$query = $CI->db->get_where('post_meta',array('post_id'=>$post_id,'key'=>$key));
		if($query->num_rows()>0)
		{
			$row = $query->row();
			if($row->value!='')
			{
				$val = (array)json_decode($row->value);
				return (isset($val[$field]))?$val[$field]:$default;
			}
		}
		else
		{
			return $default;
		}
	}
}

#----------------
// added on version 1.5
if ( ! function_exists('get_meta_photo_by_id'))
{
	function get_meta_photo_by_id($img='')
	{
		if($img=='')
		return base_url('assets/admin/img/preview.jpg');
		else
		{
			if (file_exists('./uploads/images/'.$img)) 
			{
			    return base_url('uploads/images/'.$img);
			} 
			else 
			{
				return get_featured_photo_by_id($img);
			}
		}
	}
}
// end

if ( ! function_exists('get_featured_photo_by_id'))
{
	function get_featured_photo_by_id($img='')
	{
		if($img=='')
		return base_url('assets/admin/img/preview.jpg');
		else
		return base_url('uploads/thumbs/'.$img);
	}
}

if ( ! function_exists('get_slider_photo_by_name'))
{
	function get_slider_photo_by_name($img='')
	{
		if($img=='')
		return base_url('assets/admin/img/preview.jpg');
		else
		return base_url('uploads/slider/'.$img);
	}
}


if ( ! function_exists('get_title_for_edit_by_id_lang'))
{
	function get_title_for_edit_by_id_lang($id,$lang)
	{
		$CI = get_instance();
		$CI->load->database();
		$query = $CI->db->get_where('post_meta',array('post_id'=>$id,'key'=>'title','status'=>1));
		if($query->num_rows()>0)
		{
			$row = $query->row();
			$data = ($row->value=='')?array():(array)json_decode($row->value);
			if(isset($data[$lang]) && $data[$lang]!='')
				return $data[$lang];
			else
			{
				$text = '';
				foreach ($data as $key => $value) {
					$text = $value;break;	
				}
				return $text;
			}
		}
		else
			return 'N/A';

		return $query;
	}
}

if ( ! function_exists('get_blog_data_by_lang'))
{
	function get_blog_data_by_lang($post,$column='title')
	{
		if($column=='title')
		{
			$titles = json_decode($post->title);
			$lang = get_current_lang();
			if(isset($titles->{$lang}) && $titles->{$lang}!='')
				return $titles->{$lang};
			else
				return $titles->{default_lang()};			
		}
		else
		{
			$descriptions = json_decode($post->description);
			$lang = get_current_lang();
			if(isset($descriptions->{$lang}) && $descriptions->{$lang}!='')
				return $descriptions->{$lang};
			else
				return $descriptions->{default_lang()};					
		}
	}
}

if ( ! function_exists('format_long_text'))
{
	function format_long_text($string,$length=20)
	{
		$string = strip_tags($string);

		if (strlen($string) > $length) {

		    $string = substr($string, 0, $length).'...';
		}
		return $string;	
	}
}

if ( ! function_exists('get_post_data_by_lang'))
{
	function get_post_data_by_lang($post,$column='title',$lang='')
	{
		if($lang=='')
			$lang = get_current_lang();

		if($column=='title')
		{
			$titles = json_decode($post->title);
			if(isset($titles->{$lang}) &&  $titles->{$lang}!='')
				return $titles->{$lang};
			else
				return $titles->{default_lang()};
		}
		else if($column=='address')
		{
			$titles = json_decode($post->address);
			if(isset($titles->{$lang}) &&  $titles->{$lang}!='')
				return $titles->{$lang};
			else
				return $titles->{default_lang()};
		}
		else
		{
			$descriptions = json_decode($post->description);
			if(isset($descriptions->{$lang}) &&  $descriptions->{$lang}!='')
				return $descriptions->{$lang};
			else
				return $descriptions->{default_lang()};
		}
	}
}

if ( ! function_exists('get_description_for_edit_by_id_lang'))
{
	function get_description_for_edit_by_id_lang($id,$lang)
	{
		$CI = get_instance();
		$CI->load->database();
		$query = $CI->db->get_where('post_meta',array('post_id'=>$id,'key'=>'description','status'=>1));
		if($query->num_rows()>0)
		{
			$row = $query->row();
			$data = ($row->value=='')?array():(array)json_decode($row->value);
			if(isset($data[$lang]) && $data[$lang]!='')
				return $data[$lang];
			else
			{
				$text = '';
				foreach ($data as $key => $value) {
					$text = $value;break;	
				}
				return $text;
			}
		}
		else
			return 'N/A';

		return $query;
	}
}



if ( ! function_exists('create_square_thumb'))
{
	function create_square_thumb($img,$dest)
	{
		$seg = explode('.',$img);
		$thumbType    = 'jpg';
		$thumbSize    = 300;
		$thumbPath    = $dest;
		$thumbQuality = 100;

		$last_index = count($seg);
		$last_index--;

		if($seg[$last_index]=='jpg' || $seg[$last_index]=='JPG' || $seg[$last_index]=='jpeg')
		{
			if (!$full = imagecreatefromjpeg($img)) {
			    return 'error';
			}			
		}
		else if($seg[$last_index]=='png')
		{
			if (!$full = imagecreatefrompng($img)) {
			    return 'error';
			}			
		}
		else if($seg[$last_index]=='gif')
		{
			if (!$full = imagecreatefromgif($img)) {
			    return 'error';
			}			
		}
		 
	    $width  = imagesx($full);
	    $height = imagesy($full);
		 
	    /* work out the smaller version, setting the shortest side to the size of the thumb, constraining height/wight */
	    if ($height > $width) {
	      $divisor = $width / $thumbSize;
	    } else {
	      $divisor = $height / $thumbSize;
	    }
		 
	    $resizedWidth   = ceil($width / $divisor);
	    $resizedHeight  = ceil($height / $divisor);
		 
	    /* work out center point */
	    $thumbx = floor(($resizedWidth  - $thumbSize) / 2);
	    $thumby = floor(($resizedHeight - $thumbSize) / 2);
		 
	    /* create the small smaller version, then crop it centrally to create the thumbnail */
	    $resized  = imagecreatetruecolor($resizedWidth, $resizedHeight);
	    $thumb    = imagecreatetruecolor($thumbSize, $thumbSize);

	    imagealphablending( $resized, false );
		imagesavealpha( $resized, true );

		imagealphablending( $thumb, false );
		imagesavealpha( $thumb, true );

	    imagecopyresized($resized, $full, 0, 0, 0, 0, $resizedWidth, $resizedHeight, $width, $height);
	    imagecopyresized($thumb, $resized, 0, 0, $thumbx, $thumby, $thumbSize, $thumbSize, $thumbSize, $thumbSize);
		 
		 $name = name_from_url($img);

	    imagejpeg($thumb, $thumbPath.str_replace('_large.jpg', '_thumb.jpg', $name), $thumbQuality);
	}
	
}

if ( ! function_exists('humanTiming'))
{
	function humanTiming ($time)
	{

	    $time = time() - $time; // to get the time since that moment

	    $tokens = array (
	        31536000 => 'year',
	        2592000 => 'month',
	        604800 => 'week',
	        86400 => 'day',
	        3600 => 'hour',
	        60 => 'minute',
	        1 => 'second'
	    );

	    foreach ($tokens as $unit => $text) {
	        if ($time < $unit) continue;
	        $numberOfUnits = floor($time / $unit);
	        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'').' ago';
	    }

	}
}

// updated on version 1.5
if ( ! function_exists('social_sharing_meta_tags_for_post'))
{

	function social_sharing_meta_tags_for_post($post='')
	{
		if($post!='' && $post->num_rows()>0)
		{
			//update on version 1.7
			$CI = get_instance();
			$post = $post->row();
			$curr_lang = get_current_lang();
			$site_title = get_settings('site_settings','site_title','justmy');
			$title = get_post_data_by_lang($post,'title');
            $detail_link = post_detail_url($post);
            $description = truncate(strip_tags(get_post_data_by_lang($post,'description')),160,'');
            $tags = $post->tags;
			$meta = '<meta name="twitter:card" content="photo" />'."\n".
					'<meta name="twitter:site" content="'.$site_title.'" />'."\n".
					'<meta name="twitter:image" content="'.get_meta_photo_by_id($post->featured_img).'" />'."\n".
					'<meta property="og:title" content="'.$title.'" />'."\n".
					'<meta property="og:site_name" content="'.$site_title.'" />'."\n".
					'<meta property="og:url" content="'.$detail_link.'" />'."\n".
					'<meta property="og:description" content="'.$description.'" />'."\n".
					'<meta property="og:type" content="article" />'."\n".
					'<meta property="og:image" content="'.get_meta_photo_by_id($post->featured_img).'" />'."\n".
					'<link rel="canonical" href="'.$detail_link.'"/>'."\n".
					'<meta name="description" content="'.$description.'">'."\n".
				    '<meta name="keywords" content="'.$tags.'"/>'."\n";

			$fb_app_id = get_settings('business_settings','fb_app_id','none');
			if($fb_app_id!='' && $fb_app_id!='none')
				$meta .= '<meta property="fb:app_id" content="'.get_settings('business_settings','fb_app_id','none').'" />';
		 
		 	//end
		 	return $meta;
		}
		else
			return '';
	}
}

if(!function_exists('social_sharing_meta_tags_for_blog'))
{

    function social_sharing_meta_tags_for_blog($blog_meta='')
    {
        if($blog_meta!='')
        {
        	//update on version 1.7

            $site_title = get_settings('site_settings','site_title','justmy');
            $title = get_post_data_by_lang($blog_meta,'title');
            $detail_link = site_url('post-detail/'.$blog_meta->id.'/'.dbc_url_title($title));
            $image_path=(!empty($blog_meta->featured_img)? base_url().'uploads/thumbs/'.$blog_meta->featured_img : base_url().'assets/admin/img/preview.jpg');

            $remove_tag_text = (!empty($blog_meta->description)?strip_tags(get_post_data_by_lang($blog_meta,'description')):'');
            $description = truncate($remove_tag_text, 160 ," ");

            $meta = '<meta name="twitter:card" content="photo" />'."\n".
                '<meta name="twitter:site" content="'.$site_title.'" />'."\n".
                '<meta name="twitter:image" content="'.$image_path.'" />'."\n".
                '<meta property="og:title" content="'.$title.'" />'."\n".
                '<meta property="og:site_name" content="'.$site_title.'" />'."\n".
                '<meta property="og:url" content="'.$detail_link.'" />'."\n".
                '<meta property="og:description" content="'.$description.'" />'."\n".
                '<meta property="og:type" content="article" />'."\n".
                '<meta property="og:image" content="'.$image_path.'" />'."\n".
                '<link rel="canonical" href="'.$detail_link.'"/>'."\n".
                '<meta name="description" content="'.$description.'">'."\n".
			    '<meta name="keywords" content="'.get_settings('site_settings', 'key_words', 'Search,list,get,business,local,area').'"/>';

			
			$fb_app_id = get_settings('business_settings','fb_app_id','none');
			if($fb_app_id!='' && $fb_app_id!='none')
				$meta .= '<meta property="fb:app_id" content="'.get_settings('business_settings','fb_app_id','none').'" />';
			//end

            return $meta;

        }
        else
            return '';
    }
}
// end

if ( ! function_exists('word_limiter'))
{
    function word_limiter($str, $limit = 100, $end_char = '&#8230;')
    {
        if (trim($str) == '')
        {
            return $str;
        }

        preg_match('/^\s*+(?:\S++\s*+){1,'.(int) $limit.'}/', $str, $matches);

        if (strlen($str) == strlen($matches[0]))
        {
            $end_char = '';
        }

        return rtrim($matches[0]).$end_char;
    }
}


if ( ! function_exists('fileinfo_from_url'))
{

	function fileinfo_from_url($filePath)
	{
	 $fileParts = pathinfo($filePath);

	 if(!isset($fileParts['filename']))
	 {$fileParts['filename'] = substr($fileParts['basename'], 0, strrpos($fileParts['basename'], '.'));}
	 
	 return $fileParts;
	}
}

if ( ! function_exists('name_from_url'))
{

	function name_from_url($filePath)
	{
	 $fileParts = pathinfo($filePath);

	 if(!isset($fileParts['filename']))
	 {$fileParts['filename'] = substr($fileParts['basename'], 0, strrpos($fileParts['basename'], '.'));}
	 
	 return $fileParts['basename'];
	}
}


if ( ! function_exists('image_from_url'))
{
	function image_from_url ($url,$name='')
	{
		if($name=='')
		$name = name_from_url($url);
		$ch = curl_init($url);
		$fp = fopen('uploads/url/'.$name, 'wb');
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);

		return base_url('uploads/url/'.$name);
	}
}


if ( ! function_exists('gif2jpeg'))
{
	function gif2jpeg($p_fl, $p_new_fl='', $bgcolor=false)
	{
	  	list($wd, $ht, $tp, $at)=getimagesize($p_fl);
		$img_src=imagecreatefromgif($p_fl);
		$img_dst=imagecreatetruecolor($wd,$ht);
		$clr['red']=255;
		$clr['green']=255;
		$clr['blue']=255;
		
		if(is_array($bgcolor)) $clr=$bgcolor;
		$kek=imagecolorallocate($img_dst,
		$clr['red'],$clr['green'],$clr['blue']);
		imagefill($img_dst,0,0,$kek);
		imagecopyresampled($img_dst, $img_src, 0, 0, 0, 0, $wd, $ht, $wd, $ht);
	  	$draw=true;
		if(strlen($p_new_fl)>0)
		{
		    if($hnd=fopen($p_new_fl,'w'))
		    {
		    	$draw=false;
		    	fclose($hnd);
		    }
		}
		
		if(true==$draw)
		{
			header("Content-type: image/jpeg");
		    imagejpeg($img_dst);
		}
		else 
			imagejpeg($img_dst, $p_new_fl);
		  
		imagedestroy($img_dst);
		imagedestroy($img_src);
	}
}


if ( ! function_exists('resized_to_fixed_width'))
{

	function resized_to_fixed_width($img,$width=500)
	{
		$CI = get_instance();
		$config['image_library'] = 'gd2';
		$config['source_image'] = $img;
		$config['maintain_ratio'] = TRUE;
		$config['width'] = $width;

		$CI->load->library('image_lib', $config);

		$CI->image_lib->resize();
	}
}

if ( ! function_exists('create_rect_thumb'))
{

	function create_rect_thumb($img,$dest,$ratio=3)
	{

		$seg = explode('.',$img);	//explde the source to get the image extension
		$thumbType    = 'jpg';		//generated thumb will be of type jpg
		$thumbPath    = $dest;	//destination path of the thumb -- original image name will be appended
		$thumbQuality = 80;				//quality of the thumbnail (in percent)

		//chech the image type and create image accordingly
		if($seg[2]=='jpg' || $seg[2]=='JPG' || $seg[2]=='jpeg')
		{
			if (!$full = imagecreatefromjpeg($img)) {
			    return 'error';
			}
		}
		else if($seg[2]=='png')
		{
			if (!$full = imagecreatefrompng($img)) {
			    return 'error';
			}			
		}
		else if($seg[2]=='gif')
		{
			if (!$full = imagecreatefromgif($img)) {
			    return 'error';
			}			
		}

	    $width  = imagesx($full);
	    $height = imagesy($full);

	    /*wourk out the thumbnail size*/
	    $resizedHeight	= min($width*$ratio/8,$height);
	    $resizedWidth	= $width;
		 
	    /* work out starting point */
	    $thumbx = 0;	// x always starts at zero -- the thumbnail gets the same width as the source image
	    $extra_height = $height - $resizedHeight;
	    $thumby = floor(($extra_height) / 2);
		 
	    /* create the small smaller version, then crop it centrally to create the thumbnail */
	    $resized  = imagecreatetruecolor($resizedWidth, $resizedHeight);
	    imagecopy($resized, $full,0,0,$thumbx,$thumby,$resizedWidth,$resizedHeight);
		 
		$name = name_from_url($img);

	    imagejpeg($resized, $thumbPath.str_replace('_large.jpg', '_thumb.jpg', $name), $thumbQuality);
	}
}



if ( ! function_exists('put_watermark'))
{
	function put_watermark($src,$text='')
	{
		$CI = get_instance();
		$CI->load->library('image_lib');
		$config['source_image'] = $src;
		$config['wm_text'] = $text;
		$config['wm_type'] = 'text';
		$config['wm_font_path'] = './system/fonts/texb.ttf';
		$config['wm_font_size'] = '16';
		$config['wm_font_color'] = 'ffffff';
		$config['wm_vrt_alignment'] = 'bottom';
		$config['wm_hor_alignment'] = 'center';
		$config['wm_padding'] = '0';

		$CI->image_lib->initialize($config);

		$CI->image_lib->watermark();
	}
}

if ( ! function_exists('filePath'))
{
	function filePath($filePath)
	{
		$fileParts = pathinfo($filePath);

		if(!isset($fileParts['filename']))
		{
			$fileParts['filename'] = substr($fileParts['basename'], 0, strrpos($fileParts['basename'], '.'));
		}
	 
		return $fileParts;
	}
}


if ( ! function_exists('is_animated'))
{
	function is_animated($filename)
	{
        $filecontents=file_get_contents($filename);

        $str_loc=0;
        $count=0;
        while ($count < 2) # There is no point in continuing after we find a 2nd frame
        {
            $where1=strpos($filecontents,"\x00\x21\xF9\x04",$str_loc);
            if ($where1 === FALSE)
            {
                break;
            }
            else
            {
                $str_loc=$where1+1;
                $where2=strpos($filecontents,"\x00\x2C",$str_loc);
                if ($where2 === FALSE)
                {
                    break;
                }
                else
                {
                    if ($where1+8 == $where2)
                    {
                        $count++;
                    }
                                $str_loc=$where2+1;
                }
            }
        }

        if ($count > 1)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
	}
}

if ( ! function_exists('videoType'))
{
	function videoType($url) 
	{
	    if (strpos($url, 'youtube') > 0) 
	    {
	        return 'youtube';
	    } 
	    elseif (strpos($url, 'vimeo') > 0) 
	    {
	        return 'vimeo';
	    }
	    else 
	    {
	        return 'unknown';
	    }
	}
}

if ( ! function_exists('load_view'))
{
	function load_view($view='',$data=array(),$buffer=FALSE,$theme='')
	{
		$CI 	= get_instance();
		if($theme=='')
		$theme 	= get_active_theme();
		if($buffer==FALSE)
		{
			if(@file_exists(APPPATH."modules/themes/views/".$theme."/".$view.".php"))
			$CI->load->view('themes/'.$theme.'/'.$view,$data);
			else
			$CI->load->view('themes/default/'.$view,$data);	
		}
		else
		{
			if(@file_exists(APPPATH."modules/themes/views/".$theme."/".$view.".php"))
			$view_data = $CI->load->view('themes/'.$theme.'/'.$view,$data,TRUE);
			else
			$view_data = $CI->load->view('themes/default/'.$view,$data,TRUE);	
			return $view_data;
		}
	}
}

if ( ! function_exists('load_template'))
{
	function load_template($data=array(),$theme='',$tmpl='template_view')
	{
		$row 	= get_option('site_settings');
		if(is_array($row) && isset($row['error']))
		{
			echo 'Site settings not found.error on : epbase_helper';
			die();
		}
		else
		{
			$values 		= json_decode($row->values);
			$data['title'] 	= $values->site_title;
		}

		load_view($tmpl,$data);
	}
}

if ( ! function_exists('get_active_theme'))
{
	function get_active_theme()
	{
		$row = get_option('active_theme');
		if(is_array($row) && isset($row['error']))
		{
			return 'default';
		}
		else
			return $row->values;
	}
}

if ( ! function_exists('get_option'))
{
	function get_option($key='')
	{
		$CI = get_instance();
		$query = $CI->db->get_where('options',array('key'=>$key,'status'=>1));		
		if($query->num_rows()>0)
			return $query->row();
		else
			return array('error'=>'Key not found');
	}
}

if ( ! function_exists('update_option'))
{
	function update_option($key='',$values=array())
	{
		$CI = get_instance();
		$data['values'] = json_encode($values);
		echo $key;
		print_r($data);
		$query = $CI->db->update('options',$data,array('key'=>$key));		
	}
}


if ( ! function_exists('get_plugins'))
{
	function get_plugins()
	{
		$CI = get_instance();
		$query = $CI->db->get_where('plugins',array('status'=>1));		
		return $query;
	}
}

if ( ! function_exists('get_widgets_by_position'))
{
	function get_widgets_by_position($pos='')
	{
		$CI = get_instance();
		$positions = get_option('positions');
		$positions = json_decode($positions->values);
		$widgets = array();
		foreach($positions as $position)
		{
			if($position->name==$pos)
			{
				if(isset($position->widgets))
				$widgets = $position->widgets;
			}
		}
		return $widgets;
	}
}

if ( ! function_exists('configPagination'))
{
	function configPagination($url,$total_rows,$segment,$per_page=10)
	{
		$CI = get_instance();
		$CI->load->library('pagination');
		$config['base_url'] 		= site_url($url);
		$config['total_rows'] 		= $total_rows;
		$config['per_page'] 		= $per_page;
		$config['uri_segment'] 		= $segment;
		$config['full_tag_open'] 	= '<div class="pagination"><ul>';
		$config['full_tag_close'] 	= '</ul></div>';
		$config['num_tag_open'] 	= '<li>';
		$config['num_tag_close'] 	= '</li>';
		$config['cur_tag_open'] 	= '<li class="active"><a href="#">';
		$config['cur_tag_close']	= '</a></li>';
		$config['num_links'] 		= 5;
		$config['next_tag_open'] 	= "<li>";
		$config['next_tag_close'] 	= "</li>";
		$config['prev_tag_open'] 	= "<li>";
		$config['prev_tag_close'] 	= "</li>";
		
		$config['first_link'] 	= FALSE;
		$config['last_link'] 	= FALSE;
		$CI->pagination->initialize($config);
		
		return $CI->pagination->create_links();
	}
}

if ( ! function_exists('get_category_title_by_id'))
{
	function get_category_title_by_id($id='')
	{
		if($id==0)
			return 'No parent';
		$CI = get_instance();
		$CI->load->database();
		$query = $CI->db->get_where('categories',array('id'=>$id));
		if($query->num_rows()>0)
		{
			$row = $query->row();
			return lang_key($row->title);
		}
		else
			return '';
	}
}

if ( ! function_exists('get_profile_photo_by_id'))
{
	function get_profile_photo_by_id($id='',$type='')
	{
		if($id==0)
			return 'No found';

		$CI = get_instance();
		$CI->load->database();
		$query = $CI->db->get_where('users',array('id'=>$id));
		if($query->num_rows()>0)
		{
			$row = $query->row();
			if($row->profile_photo=='')
				return base_url().'uploads/profile_photos/nophoto-'.strtolower($row->gender).'.jpg';
			
			if($type=='thumb')
			return base_url().'uploads/profile_photos/thumb/'.$row->profile_photo;
			else
			return base_url().'uploads/profile_photos/'.$row->profile_photo;
		}
		else
		{

			return base_url().'uploads/profile_photos/nophoto-female.jpg';
		}
	}
}

if ( ! function_exists('get_profile_photo_name_by_username'))
{
	function get_profile_photo_name_by_username($username='',$type='thumb')
	{
		if($username=='')
			return 'Not found';

		$CI = get_instance();
		$CI->load->database();
		$query = $CI->db->get_where('users',array('user_name'=>$username));
		if($query->num_rows()>0)
		{
			$row = $query->row();
			if($row->profile_photo!='')
			return $row->profile_photo;
			else
			return 'nophoto-'.strtolower($row->gender).'.jpg';
		}
		else
			return 'nophoto-.jpg';
	}
}

if ( ! function_exists('get_profile_photo_by_username'))
{
	function get_profile_photo_by_username($username='',$type='thumb')
	{
		if($username=='')
			return 'Not found';

		$CI = get_instance();
		$CI->load->database();
		$query = $CI->db->get_where('users',array('user_name'=>$username));
		if($query->num_rows()>0)
		{
			$row = $query->row();
			if($row->profile_photo!='')
			return base_url().'uploads/profile_photos/'.$type.'/'.$row->profile_photo;
			else
			return base_url().'uploads/profile_photos/nophoto-'.strtolower($row->gender).'.jpg';
		}
		else
			return base_url().'uploads/profile_photos/nophoto-female.jpg';
	}
}




if ( ! function_exists('get_view_count'))
{
	function get_view_count($post_id,$from='all')
	{
		if (isset($_COOKIE['viewcookie'.$post_id])==FALSE && $from=='detail')
		{
			$CI = get_instance();
			$CI->load->database();

			$query = $CI->db->get_where('posts',array('id'=>$post_id));
			if($query->num_rows()>0)
			{
				$row = $query->row();
				$total_view = $row->total_view;
				$total_view++;
			}		
			else
				$total_view = 0;	
			$CI->db->update('posts',array('total_view'=>$total_view),array('id'=>$post_id));
			setcookie("viewcookie".$post_id, 1, time()+1800);
			return $total_view;
		}
		else
		{
			$CI = get_instance();
			$CI->load->database();

			$query = $CI->db->get_where('posts',array('id'=>$post_id));
			if($query->num_rows()>0)
			{
				$row = $query->row();
				return $row->total_view;
			}		
			else
				$total_view = 0;				
		}
	}
}

if ( ! function_exists('get_all_business_map_data'))
{
    function get_all_business_map_data()
    {
        $CI = get_instance();
        $CI->load->database();
        $query 	= $CI->db->get_where('posts',array('status'=> 1));
        $data 	= array();
        $posts 	= array();

		$i=0;
        foreach ($query->result() as $row)
        {
			$i++;
            $post = array();
			$post['post_id'] 			= $row->id;
			$post['post_title'] 		= str_replace("'",'',get_post_data_by_lang($row,'title'));
			$post['post_purpose'] 		= '';
			$post['featured_image_url'] = get_featured_photo_by_id($row->featured_img);
			$post['latitude'] 			= $row->latitude;
			$post['longitude'] 			= $row->longitude;
			$post['price'] 				= '';
			$post['post_short_address'] = str_replace("'",'',lang_key(get_location_name_by_id($row->city)));
			$post['detail_link'] 		= post_detail_url($row);
			$post['parent_category'] 	= get_category_title_by_id($row->category);
			$post['fa_icon'] 			= get_category_fa_icon($row->category);

			if($i%3 == 1)
				$color = "#ed5441";
			else if($i%3 == 2)
				$color = "#51d466";
			else
				$color = "#609cec";
			$post['fa_color'] = $color;
            array_push($posts,$post);
        }

        $data['posts'] = $posts;
        return $data;
    }
}

if ( ! function_exists('get_business_map_data_single'))
{
	function get_business_map_data_single($row)
	{
		$data 	= array();
		$posts 	= array();

			$post = array();
			$post['post_id'] 			= $row->id;
			$post['post_title'] 		= get_post_data_by_lang($row,'title');;
			$post['post_purpose'] 		= 'hi';
			$post['featured_image_url'] = get_featured_photo_by_id($row->featured_img);
			$post['latitude'] 			= $row->latitude;
			$post['longitude'] 			= $row->longitude;
			$post['price'] 				= '';
			$post['post_short_address'] = get_location_name_by_id($row->city);
			$post['detail_link'] 		= post_detail_url($row);
			$post['parent_category'] 	= get_category_title_by_id($row->category);
			array_push($posts,$post);

		$data['posts'] = $posts;
		return json_encode($data);
	}
}

if ( ! function_exists('get_formatted_address'))
{
	function get_formatted_address($address, $city, $state, $country, $zip_code)
	{

		return $address.', '.get_location_name_by_id($city).', '.get_location_name_by_id($state).' '.$zip_code.', '.get_location_name_by_id($country);
	}
}

if ( ! function_exists('prepare_map_json_from_query'))
{
    function prepare_map_json_from_query($query)
    {
        $CI 	= get_instance();
        $data 	= array();
        $posts 	= array();

		$i = 0;
        foreach ($query->result() as $row)
        {
			$i++;
            $post = array();
            $post['post_id'] 			= $row->id;
			$post['post_title'] 		= get_post_data_by_lang($row,'title');
			$post['post_purpose'] 		= '';
			$post['featured_image_url'] = get_featured_photo_by_id($row->featured_img);
			$post['latitude'] 			= $row->latitude;
			$post['longitude'] 			= $row->longitude;
			$post['price'] 				= '';
			$post['rating'] 			= $row->rating;
			$post['post_short_address'] = get_location_name_by_id($row->city);
			$post['detail_link'] 		= post_detail_url($row);
			$post['parent_category'] 	= get_category_title_by_id($row->category);
			$post['fa_icon'] 			= get_category_fa_icon($row->category);
			if($i%3 == 1)
				$color = "#ed5441";
			else if($i%3 == 2)
				$color = "#51d466";
			else
				$color = "#609cec";
			$post['fa_color'] = $color;
			array_push($posts,$post);
        }

        $data['posts'] = $posts;
        return $data;
    }
}


if ( ! function_exists('create_rectangle_thumb'))
{
	function create_rectangle_thumb($img,$dest)
	{
		$seg = explode('.',$img);
		$thumbType    	= 'jpg';
        $thumbSize    	= 300;
        $thumbWidth 	= 300;
        $thumbHeight 	= 226;
        $thumbPath    	= $dest;
        $thumbQuality 	= 100;

		$last_index = count($seg);
		$last_index--;

		if($seg[$last_index]=='jpg' || $seg[$last_index]=='JPG' || $seg[$last_index]=='jpeg')
		{
			if (!$full = imagecreatefromjpeg($img)) {
			    return 'error';
			}			
		}
		else if($seg[$last_index]=='png')
		{
			if (!$full = imagecreatefrompng($img)) {
			    return 'error';
			}			
		}
		else if($seg[$last_index]=='gif')
		{
			if (!$full = imagecreatefromgif($img)) {
			    return 'error';
			}			
		}
		 
	    $width  = imagesx($full);
	    $height = imagesy($full);
		 

	    # work out the smaller version, setting the shortest side to the size of the thumb, constraining height/wight 
        if ($height > $width) {
            $divisor = $width / $thumbHeight;
        } else {
            $divisor = $height / $thumbWidth;
        }

		 
        $resizedWidth   = ceil($width / $divisor);
        $resizedHeight  = ceil($height / $divisor);
		 
        # work out center point 
        $thumbx = floor(($resizedWidth  - $thumbWidth) / 2);
        $thumby = floor(($resizedHeight - $thumbHeight) / 2);
		 
	    /* create the small smaller version, then crop it centrally to create the thumbnail */
        $resized  = imagecreatetruecolor($resizedWidth, $resizedHeight);
        $thumb    = imagecreatetruecolor($thumbWidth, $thumbHeight);

	    imagealphablending( $resized, false );
		imagesavealpha( $resized, true );

		imagealphablending( $thumb, false );
		imagesavealpha( $thumb, true );

	    imagecopyresized($resized, $full, 0, 0, 0, 0, $resizedWidth, $resizedHeight, $width, $height);
	    imagecopyresized($thumb, $resized, 0, 0, $thumbx, $thumby, $thumbSize, $thumbSize, $thumbSize, $thumbSize);
		 
		 $name = name_from_url($img);

	    imagejpeg($thumb, $thumbPath.str_replace('_large.jpg', '_thumb.jpg', $name), $thumbQuality);
	}
	
}

if ( ! function_exists('get_category_parent_by_id'))
{
    function get_category_parent_by_id($id)
    {

    	$CI = get_instance();
    	$CI->load->database();
    	$query = $CI->db->get_where('categories',array('id'=>$id,'status'=>1));
        if($query->num_rows()>0)
        {
        	$row = $query->row();
        	if($row->parent==0)
        		return $id;
        	else
        		return $row->parent;
        }
        else
        {
        	return $id;
        }
    }
}


if ( ! function_exists('get_category_fa_icon'))
{
	function get_category_fa_icon($id)
	{

		$CI = get_instance();
		$CI->load->database();
		$query = $CI->db->get_where('categories',array('id'=>$id,'status'=>1));
		if($query->num_rows()>0)
		{
			$row = $query->row();

			if($row->fa_icon != '')
				return $row->fa_icon;
			else
				return 'fa-picture-o';
		}
		else
		{
			return 'fa-picture-o';
		}
	}
}

if ( ! function_exists('remove_feature_expiration'))
{
	function remove_feature_expiration ($today)
	{
		$CI = get_instance();
		$CI->load->database();
		
		$CI->db->where('featured',1);
		$CI->db->where('status',1);
		$CI->db->where('featured_expiration_date <',$today);
		$CI->db->update('posts',array('featured'=>0));
	}
}

if ( ! function_exists('remove_post_expiration'))
{
	function remove_post_expiration ($today)
	{
		$CI = get_instance();
		$CI->load->database();

		$CI->db->where('status',1);
		$CI->db->where('expirtion_date <',$today);
		$CI->db->update('posts',array('status'=>4));
	}
}

if ( ! function_exists('expiration_cron'))
{
	function expiration_cron ()
	{
		$CI = get_instance();

		$CI->load->helper('date');
		$datestring = "%Y-%m-%d";
		$time  = time();
		$today = mdate($datestring, $time);

		$option = get_option('last_expiration_cron_date');

		if((is_array($option) && isset($option['error'])) || strtotime($option->values)<strtotime($today))
		{
			ini_set('max_execution_time', 3600);
			if(get_settings('business_settings','hide_posts_if_expired','Yes')=='Yes')
			remove_post_expiration($today);
		
			remove_feature_expiration($today);

			if(get_settings('business_settings','hide_posts_if_expired','Yes')=='Yes')
			{
				$CI->load->config('business_directory');
				$days = ($CI->config->item('send_notification_before')!='')?$CI->config->item('send_notification_before'):2;
				$expirtion_date = date('Y-m-d', strtotime($today. ' + '.$days.' days'));
				send_expiration_notification($expirtion_date);				
			}
			
			add_option('last_expiration_cron_date',$today);
		}

	}
}


if ( ! function_exists('send_expiration_notification'))
{
	function send_expiration_notification($expirtion_date='')
	{
		$CI = get_instance();
		$CI->load->config('business_directory');

		if($CI->config->item('send_notification_before_post_expiration')=='yes')
		{

			$CI->load->database();

			$CI->db->where('status',1);
			$CI->db->where('expirtion_date',$expirtion_date);
			$query = $CI->db->get_where('posts');
			
			$ids = array();
			$emails = array();

			foreach ($query->result() as $row) {
				if(!in_array($row->created_by, $ids))
				{
					array_push($emails,get_user_email_by_id($row->created_by));
					array_push($ids,$row->created_by);
				}
			}

			$val = get_admin_email_and_name();
			$admin_email = $val['admin_email'];
			$admin_name  = $val['admin_name'];
			$link = site_url('admin/business/allposts');

			
			$CI->load->model('admin/system_model');
			$CI->load->config('business_directory');

			$tmpl = $CI->system_model->get_email_tmpl_by_email_name('post_expiration_notification');
			$subject = $tmpl->subject;
			$subject = str_replace("#username",$data['user_name'],$subject);
			$subject = str_replace("#all_posts_link",$link,$subject);
			$subject = str_replace("#no_of_days",$CI->config->item('send_notification_before'),$subject);
			$subject = str_replace("#webadmin",$admin_name,$subject);

			$body = $tmpl->body;
			$body = str_replace("#username",$data['user_name'],$body);
			$body = str_replace("#all_posts_link",$link,$body);
			$body = str_replace("#no_of_days",$CI->config->item('send_notification_before'),$body);
			$body = str_replace("#webadmin",$admin_name,$body);

			$CI->load->library('email');
			$CI->email->from($admin_email, $subject);
			$CI->email->to($emails);
			$CI->email->subject($subject);		
			$CI->email->message($body);		
			$CI->email->send();
		}

	}
}



if ( ! function_exists('get_all_post_count'))
{
	function get_all_post_count()
	{

		$CI = get_instance();
		$CI->load->database();

		$CI->db->where('status',1);
		$query = $CI->db->get_where('posts');
		return $query->num_rows();

	}
}


if ( ! function_exists('get_all_post_reviews'))
{
	function get_all_post_reviews($post_id)
	{

		$CI = get_instance();
		$CI->load->database();

		$CI->db->order_by('id', 'desc');
		$CI->db->where('post_id',$post_id);
		$CI->db->where('status',1);
		$query = $CI->db->get_where('review');
		return $query;

	}
}

if ( ! function_exists('get_post_average_rating'))
{
	function get_post_average_rating($post_id)
	{

		$CI = get_instance();
		$CI->load->database();


		$CI->db->where('post_id',$post_id);
		$CI->db->where('status',1);
		$CI->db->where('rating !=',0);
		$CI->db->select_avg('rating', 'total_rating');
		$query = $CI->db->get('review');
		$average_rating = $query->row()->total_rating;

		return ceiling_rating($average_rating, 0.5);

	}
}


if ( ! function_exists('check_half_star_position'))
{
	function check_half_star_position($rating = 0)
	{
		$position = 0;

		if($rating == 1.5){
			$position = 2;
		}
		else if($rating == 2.5)
		{
			$position = 3;
		}
		else if($rating == 3.5)
		{
			$position = 4;
		}
		else if($rating == 4.5)
		{
			$position = 5;
		}
		else
		{
			$position = 0;
		}

		return $position;

	}
}

if( !function_exists('ceiling_rating') )
{
	function ceiling_rating($number, $significance = 1)
	{
		return ( is_numeric($number) && is_numeric($significance) ) ? (ceil($number/$significance)*$significance) : false;
	}
}

if ( ! function_exists('get_review_stars'))
{
	function get_review_stars($rating)
	{

		$html = '<span class="stars">';
		for($i=1; $i <=5; $i++)
		{
	        $active_class = ($i <= $rating) ? 'active' : '';
	        $html .= '<i class="fa fa-star '.$active_class.'"></i>';
	    }
	    $html .= '</span>';
	    return $html;
	}
}

if ( ! function_exists('get_review_with_half_stars'))
{
	function get_review_with_half_stars($average_rating,$half_star_position,$additional_class='')
	{

		$html = '<span class="stars '.$additional_class.'">';
         for($i=1; $i <=5; $i++)
         {
            $active_class = ($i <= $average_rating) ? 'active' : '';
            if($half_star_position != 0 && $i == $half_star_position)
            { 
                $html .= '<i class="fa fa-star-half-o active"></i>';
            }
            else
            { 
                $html .= '<i class="fa fa-star '.$active_class.'"></i>';
            }
        } 
        
        $html .= '</span>';
	    return $html;
	}
}
/* End of file array_helper.php */
/* Location: ./system/helpers/array_helper.php */