<div class="row">

    <div class="col-md-2">
        <a href="<?php echo site_url('profile/'.$review->created_by.'/'.get_user_fullname_by_id($review->created_by));?>">
            <img alt="user-image" src="<?php echo get_profile_photo_by_id($review->created_by); ?>" class="img-responsive user-img">
        </a>
    </div>
    <div class="col-md-10">
        <h4><?php echo get_user_fullname_by_id($review->created_by); ?></h4>
        <p class="contact-types">
        <?php echo get_review_stars($review->rating);?>

        <div class="clearfix"></div>
        <strong><?php echo lang_key('posted_on');?>:</strong> <?php echo translatedDate($review->create_time);?><!-- updated on version 1.6 -->
        </p>
        <p><?php echo $review->comment; ?></p>
    </div>

</div>
<hr/>