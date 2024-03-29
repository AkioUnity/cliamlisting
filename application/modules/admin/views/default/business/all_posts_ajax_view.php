<!-- file updated on version 1.6 -->
<?php 
$curr_page = $this->uri->segment(5);
if($curr_page=='')
  $curr_page = 0;
$dl = default_lang();
?>
        <span class="ajax-msg"></span>
        <table id="all-posts" class="table table-hover table-advance">

           <thead>

               <tr>

                  <th class="numeric serial" style="width:50px;">
                    <input class="check-all-ids" type="checkbox" name="" value="">
                    #
                  </th>

                  <th class="numeric photo"><?php echo lang_key('image');?></th>

                  <th class="numeric title"><?php echo lang_key('title');?></th>

                  <th class="numeric category"><?php echo lang_key('category');?></th>

                  <th class="numeric email"><?php echo lang_key('email');?></th>
                  
                  <th class="numeric city"><?php echo lang_key('city');?></th>

                  <th class="numeric status"><?php echo lang_key('status');?></th>

                  <th class="numeric days-left"><?php echo lang_key('days_left');?></th>

                  <th class="numeric featured-status"><?php echo lang_key('featured');?></th>

                  <th class="numeric actions"><?php echo lang_key('actions');?></th>

               </tr>

           </thead>

           <tbody>

          <?php $i=1;foreach($posts->result() as $row):  ?>

               <tr>

                  <td data-title="#" class="numeric">
                    <input class="ids" type="checkbox" name="ids[]" value="<?php echo $row->id;?>">
                    <?php echo $i;?>
                  </td>

                  <td data-title="<?php echo lang_key('image');?>" class="numeric"><img class="thumbnail" style="width:50px;margin-bottom:0px;" src="<?php echo get_featured_photo_by_id($row->featured_img);?>" /></td>

                  <td data-title="<?php echo lang_key('title');?>" class="numeric"><?php echo get_post_data_by_lang($row,'title');?></td>

                  <td data-title="<?php echo lang_key('category');?>" class="numeric"><?php echo get_category_title_by_id($row->category);?></td>

                  <td data-title="<?php echo lang_key('email');?>" class="numeric">
                      <?php echo $row->email;?>
                  </td>
                  
                  <td data-title="<?php echo lang_key('city');?>" class="numeric"><?php echo get_location_name_by_id($row->city);?></td>
                  
                  <td data-title="<?php echo lang_key('status');?>" class="numeric"><?php echo get_status_title_by_value($row->status);?></td>

                  <td data-title="<?php echo lang_key('days_left');?>" class="numeric">
                    <?php 
                    

                    $future = strtotime($row->expirtion_date); //Future date.
                    $timefromdb = time();
                    $timeleft = $future-$timefromdb;
                    $daysleft = ceil((($timeleft/24)/60)/60); 
                    if($daysleft<0)
                      echo '--';
                    else
                      echo $daysleft.' '.lang_key('days');

                  ?>
                  </td>
                  
                  <td data-title="<?php echo lang_key('featured');?>" class="numeric"><?php echo ($row->featured==1)?'<span class="label label-success">Yes</span>':'<span class="label label-info">No</span>';?></td>

                  <td data-title="<?php echo lang_key('actions');?>" class="numeric">

                    <div class="btn-group">

                      <a class="btn btn-info dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-cog"></i> <?php echo lang_key('action');?> <span class="caret"></span></a>

                      <ul class="dropdown-menu dropdown-info">

                          <li><a href="<?php echo post_detail_url($row);?>" target="_blank"><?php echo lang_key('view_on_front');?></a></li>
<!--                          <li><a href="--><?php //echo site_url('edit-business/'.$curr_page.'/'.$row->id);?><!--">--><?php //echo lang_key('edit');?><!--</a></li>-->
                          <li><a href="<?php echo site_url('admin/business/deletepost/'.$curr_page.'/'.$row->id);?>"><?php echo lang_key('delete');?></a></li>
                          <li><a href="<?php echo site_url('admin/business/view_all_reviews/'.$curr_page.'/'.$row->id);?>"><?php echo lang_key('view_all_reviews');?></a></li>
<!--                          --><?php //if(is_admin()){?>
<!--                            --><?php //if($row->status==2){?>
<!--                            <li><a href="--><?php //echo site_url('admin/business/approvepost/'.$curr_page.'/'.$row->id);?><!--">--><?php //echo lang_key('approve');?><!--</a></li>-->
<!--                            --><?php //}?>
<!--                            --><?php //if($row->featured==0){?>
<!--                            <li><a class="make-featured" data-postid="--><?php //echo $row->id;?><!--" href="javascript:void(0)">--><?php //echo lang_key('make_featured');?><!--</a></li>-->
<!--                            --><?php //}else{?>
<!--                            <li><a href="--><?php //echo site_url('admin/business/removefeaturepost/'.$curr_page.'/'.$row->id);?><!--">--><?php //echo lang_key('remove_featured');?><!--</a></li>-->
<!--                            <li><a class = "renew-featured" data-postid="--><?php //echo $row->id;?><!--" href="javascript:void(0)">--><?php //echo lang_key('renew_featured');?><!--</a></li>-->
<!--                            --><?php //}?>
<!--                          --><?php //}else{?>
<!--                            --><?php //if(get_settings('package_settings','enable_featured_pricing','No')=='Yes' && $row->featured==0){?>
<!--                            <li><a href="--><?php //echo site_url('admin/business/choosefeaturepackage/'.$row->id);?><!--">--><?php //echo lang_key('make_featured');?><!--</a></li>-->
<!--                            --><?php //}else{?>
<!--                            <li><a href="--><?php //echo site_url('admin/business/choosefeaturepackagerenew/'.$row->id);?><!--">--><?php //echo lang_key('renew_featured');?><!--</a></li>-->
<!--                            --><?php //}?>
<!--                          --><?php //}?>
<!--                          --><?php //if($row->status==4){ // if post is expired give an option to renew?>
<!--                              <li><a href="--><?php //echo site_url('user/payment/chooserenewpackage/'.$row->id);?><!--">--><?php //echo lang_key('renew_package');?><!--</a></li>-->
<!--                          --><?php //}?><!-- -->

                      </ul>

                    </div>

                  </td>

               </tr>

            <?php $i++;endforeach;?>   

           </tbody>

        </table>

          <div class="pagination pull-right">
            <ul class="pagination pagination-colory"><?php echo $pages;?></ul>
          </div>
          <div class="pull-right">
            <img src="<?php echo base_url('assets/images/loading.gif');?>" style="width:20px;margin:5px;display:none" class="loading">
          </div>
          <div class="clearfix"></div>
          <a href="#" class="btn btn-danger delete-all" style="display:none"><?php echo lang_key('delete_selected');?></a>
          <div class="clearfix"></div>


<script type="text/javascript">
  jQuery(document).ready(function(){
    jQuery('.check-all-ids').click(function(e){
      var checked = jQuery(this).attr('checked');
      if(checked=='checked')
      {
        jQuery('.ids').prop('checked',true);
        jQuery('.delete-all').show();
      }
      else
      {
        jQuery('.ids').prop('checked',false);
        jQuery('.delete-all').hide();
      }
    });

    jQuery('.ids').click(function(e){
      var checked = jQuery(this).attr('checked');
      if(checked!='checked')
      {
        jQuery('.check-all-ids').prop('checked',false);
        var flag = 0;
        jQuery('.ids').each(function(){
          if(jQuery(this).attr('checked')=='checked')
            flag = 1;
        });
        if(flag==0)
        jQuery('.delete-all').hide();
      }
      else
      {
        jQuery('.delete-all').show();
      }
    });

    jQuery('.delete-all').click(function(e){
      e.preventDefault();
      var r = confirm("<?php echo lang_key('delete_warning');?>");
      if (r == true) {
        var load_url = '<?php echo site_url("admin/business/bluk_delete_ads/")?>';
        var id_array = Array();
        
        jQuery('.ids').each(function(){
          if(jQuery(this).attr('checked')=='checked')
            id_array.push(jQuery(this).val());
        });

        jQuery.post(
            load_url,
            {ids:id_array},
            function(responseText){        
                $('#table-search-from').submit();
            }
        ); 
      }

    });

  });
</script>