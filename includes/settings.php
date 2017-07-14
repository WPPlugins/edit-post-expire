<?php
/*
 * Edit Post Expire WordPress plugin options page
 *
 * @Author: Vladimir Garagulya
 * @URL: http://shinephp.com
 * @package EditPostExpire
 *
 */

?>
<div class="wrap">
  <div class="icon32" id="icon-options-general"><br/></div>
  <h2><?php _e('Edit Post Expire - Options', 'edit_post_expire'); ?></h2>
  <hr/>
  
  <form method="post" action="options-general.php?page=edit-post-expire.php" >

    
    <table>
      <tr>
        <td><label for="minutes_after"><?php _e('Block post edit after:', 'edit_post_expire'); ?></label></td>
        <td><input type="text" name="minutes_after" id="minutes_after" value="<?php echo $minutes_after; ?>" size="4"/> <?php _e( 'minutes', 'edit_post_expire' ); ?></td>
      </tr>
    </table>
    <?php wp_nonce_field('edit_post_expire'); ?>   
    <p class="submit">
      <input type="submit" class="button-primary" name="edit_post_expire_update" value="<?php _e('Update') ?>" />
    </p>  

  </form>  
</div>
