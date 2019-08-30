<?php echo (isset($msg) && $msg!='')?$msg:'';?>
<div class="input-group">
    <input class="form-control" type="text" placeholder="<?php echo lang_key('your_email'); ?>" value="" name="email">
    <span class="input-group-btn">
        <button type="submit" class="btn btn-color"><?php echo lang_key('Save');?></button>
    </span>
</div>
<?php echo form_error('email');?>

