<?php  echo '<?xml version="1.0" encoding="' . $encoding . '"?>' . "\n"; ?>
<rss version="2.0"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
    xmlns:admin="http://webns.net/mvcb/"
    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
    xmlns:content="http://purl.org/rss/1.0/modules/content/"
    xmlns:atom="http://www.w3.org/2005/Atom">
 
    <channel>
     
    <title><?php echo $feed_name; ?></title>
 
    <link><?php echo $feed_url; ?></link>
    <description><?php echo $page_description; ?></description>
    <dc:language><?php echo $page_language; ?></dc:language>
 
    <dc:rights>Copyright <?php echo gmdate("Y", time()); ?></dc:rights>
    <admin:generatorAgent rdf:resource="http://www.codeigniter.com/" />
 
    <atom:link href="<?php echo site_url('show/feed');?>" rel="self" type="application/rss+xml" />

    <?php foreach($posts->result() as $post): ?>
     	<?php 
      
       	$car_title =  get_post_data_by_lang($post,'title');
       	$description = get_post_data_by_lang($post,'description');
       	$detail_link = post_detail_url($post);
        $media = get_meta_photo_by_id($post->featured_img);
        $seg = explode('.', $media);
       	$type = $seg[count($seg)-1];

        $valid_type = array('jpg','jpeg','png','gif');
        if(in_array(strtolower($type),$valid_type))
        {
        ?>
          <item>
   
            <title><?php echo xml_convert($car_title); ?></title>
            <link><?php echo $detail_link; ?></link>
            <guid><?php echo $detail_link; ?></guid>
   
              <description><![CDATA[ <?php echo character_limiter($description, 200); ?> ]]></description>
              <enclosure length="250" url="<?php echo $media;?>" type="image/<?php echo $type;?>" />
              <pubDate><?php echo standard_date("DATE_RSS", $post->publish_time);?></pubDate>
          </item>
 
         
    <?php 
    }
    endforeach; ?>
     
    </channel>
</rss>