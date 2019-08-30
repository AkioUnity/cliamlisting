<!-- Search Widget -->
<div class="s-widget">
    <!-- Heading -->
    <h5><i class="fa fa-envelope color"></i>&nbsp; <?php echo lang_key('newsletter_subscription'); ?></h5>
    <span class="newsletter-letter-form-holder">
    	<!-- Widgets Content -->
		<div class="widget-content search">
		    <form id="newsletter-form" role="form" action="<?php echo site_url('show/getnewsletter')?>" method="post">
		    	<?php load_view('newsletter_form');?>
		    </form>
		</div>    
    </span>    
</div>
<script type="text/javascript">
	$(document).ready(function(){

		jQuery('#newsletter-form').submit(function(e){
            e.preventDefault();
            var data = jQuery(this).serializeArray();
            var loadUrl = jQuery(this).attr('action');
            jQuery.post(
                loadUrl,
                data,
                function(responseText){
                    jQuery('#newsletter-form').html(responseText);
                 }
            );
        });

	});
</script>