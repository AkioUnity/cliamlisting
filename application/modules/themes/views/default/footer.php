<!-- Footer Starts -->
<div class="foot">
    <!-- Container -->
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-4">
                <!-- Foot Item -->
                <div class="foot-item">
                    <?php render_widgets('footer_first_column');?>
                </div>
            </div>  
            <div class="col-md-4 col-sm-4">
                <!-- Foot Item -->
                <div class="foot-item">
                    <!-- Heading -->
                    <?php render_widgets('footer_second_column');?>
                </div>
            </div>
            <div class="col-md-4 col-sm-4">
                <!-- Foot Item -->
                <div class="foot-item">
                    <?php render_widgets('footer_third_column');?>
                </div>
            </div>
        </div>
    </div>
</div>
<footer>
    <!-- Container -->
    <div class="container">
        <!-- Footer Content -->
            <!-- Paragraph -->
            <p class="pull-left"><?php echo translate(get_settings('site_settings','footer_text','copyright@'));?> | Powered by <a href="http://codecanyon.net/user/webhelios/portfolio?ref=webhelios">WhizBusinessDirectory</a></p>
            <?php render_widget('footer_links') ?>
            <!-- Clearfix -->
            <div class="clearfix"></div>
    </div>
</footer>