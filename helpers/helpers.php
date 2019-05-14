<?php

//show error
function display_errors($errors){
  $display = '<ul class="error_info">';
  foreach ($errors as $error) {
    $display .= '<li class="">'.$error.'</li>';
  }
  $display .= '</ul>';
  return $display;
}

//security avoid html input brand
function sanitize($dirty){
  return htmlentities($dirty,ENT_QUOTES,"UTF-8");
}


//admin login function n update time
function login($user_id){
  $_SESSION['SBUser'] = $user_id;
  global $db;
  $date = date("Y-m-d H:i:s");
  $db->query("UPDATE users SET updated_at = '$date' WHERE id = '$user_id'");

  $_SESSION['success_flash'] = 'You are now logged in';
  header('Location: index.php');
}



//check logged in
function is_logged_in(){

  if(isset($_SESSION['SBUser']) && $_SESSION['SBUser']>0){

    return true;
  }
  return false;
}



function login_error_redirect($url = 'login.php'){
    $_SESSION['error_flash'] = 'You are not log in';
    header('Location:'. $url);
}

function permission_error_redirect($url = 'login.php'){
    $_SESSION['error_flash'] = 'No acces';
    header('Location:'. $url);
}
