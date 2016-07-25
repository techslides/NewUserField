<?php
/**
 * @package newUserField
 * @version 0.1
 */
/*
Plugin Name: New User Field
Plugin URI: http://techslides.com/
Description: Add, track, and display new data for a WordPress user
Version: 0.1
Author URI: http://techslides.com/
*/

function last_login( $user_login, $user ) {
  update_user_meta($user->ID,'last_login',date('Y/m/d h:i:sa'));
}
add_action('wp_login', 'last_login', 10, 2);

//limit edit to only admins
function update_extra_profile_fields($user_ID){
  if(current_user_can('administrator')){
    update_user_meta($user_ID,'credits',$_POST['credits']);
  }
}
add_action('personal_options_update','update_extra_profile_fields');
add_action('edit_user_profile_update','update_extra_profile_fields');

//show this only to administrators
function custom_profile($user){
    if(current_user_can('administrator')){
      echo '<table class="form-table">';
      echo '<tr><th>Credits</th><td><input type="text" name="credits" id="test" value="'.esc_attr(get_the_author_meta('credits',$user->ID)).'"></input></td></tr>';
      echo '<tr><th>Last Login</th><td><label>'.esc_attr(get_the_author_meta('last_login',$user->ID)).'</label></td></tr>';
      echo '</table>';
    }
}
add_action('show_user_profile','custom_profile');
add_action('edit_user_profile','custom_profile');




//show user id, credits, and last login columns
add_filter('manage_users_columns', 'techslides_add_user_id_column');
function techslides_add_user_id_column($columns) {
    $columns['user_id'] = 'User ID';
    $columns['credits'] = 'Credits';
    $columns['last_login'] = 'Last Login';
    return $columns;
}

//show user id, credits, and last login data
add_action('manage_users_custom_column',  'techslides_show_user_id_column_content', 10, 3);
function techslides_show_user_id_column_content($value, $column_name, $user_id) {
    $user = get_userdata( $user_id );
	  if ( 'user_id' == $column_name ){
      return $user_id;
    }
    if ( 'credits' == $column_name ){
      return $user->credits;
    }
    if ( 'last_login' == $column_name ){
      return $user->last_login;
    }        
    return $value;
}