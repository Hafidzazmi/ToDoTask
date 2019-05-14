<?php
/*-- connect with database-
input1= host
input2= username for database
input3= password for database
input4= database name*/
$db = mysqli_connect('127.0.0.1','hafidzazmi07','Pc657845','hafidzazmi07');
$db->set_charset("utf8");

/*-- connect with database-*/

//start session
session_start();

/*Print out connection error */
if(mysqli_connect_errno()){
    echo 'Database connection failed with following errors: '. mysqli_connect_error();
    die();
}
/*Print out connection error */

//for admin and additional function//
require_once $_SERVER['DOCUMENT_ROOT'].'/config.php';
require_once BASEURL.'/helpers/helpers.php';



//for login admin checking system
if(isset($_SESSION['SBUser'])){

  $user_id = $_SESSION['SBUser'];
  $query = $db->query("SELECT * FROM users WHERE id='$user_id'");
  $user_data = mysqli_fetch_assoc($query);

}




if(isset($_SESSION['success_flash'])){
  #echo '<div class="bg-success"><p class="alert-success text-center">'.$_SESSION['success_flash'].'</p></div>';
    unset($_SESSION['success_flash']);
}

if(isset($_SESSION['error_flash'])){
  echo '<div class="bg-danger"><p class="alert-danger text-center">'.$_SESSION['error_flash'].'</p></div>';
  unset($_SESSION['error_flash']);
}
