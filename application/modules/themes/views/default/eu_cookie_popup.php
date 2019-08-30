<div id="cookiescript-popup" style="display:none">
   <div id="cookiescript-wrapper" style="">
      <?php echo lang_key('cookie_policy_alert');?>
      <div style="clear:both"></div>
      <a href="<?php echo site_url(get_settings('business_settings','cookie_policy_page_url',''));?>" class="btn btn-blue"><?php echo lang_key('read_more');?></a>
      <a href="#" class="btn btn-green agree-cookie" ><?php echo lang_key('i_agree');?></a>
      <div id="cookiescript-pixel" style="width: 1px; height: 1px; float: left;"></div>
   </div>
</div>
<script src="<?php echo base_url();?>assets/admin/assets/jquery-cookie/jquery_cookie.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		if($.cookie('agree-terms')!=1)
		{
			$('#cookiescript-popup').show('slow');
		} 

		$('.agree-cookie').click(function(e){
			e.preventDefault();
			$.cookie('agree-terms', 1, { expires: 30 });
			$('#cookiescript-popup').hide('slow');
		});

	});
</script>