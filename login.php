<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';
include 'includes/head.php';

$login = ((isset($_POST['login']))?sanitize($_POST['login']):'');
$login = trim($login);
$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
$password = trim($password);

$errors = array();

?>

  <div class="container" id="login-form">

    <div>

      <?php

      if($_POST){

        if(empty($_POST['login']) || empty($_POST['password'])){

          $errors[] = 'You must provide email and password';

        }


        //check password length
        if(strlen($password)<3) {
          $errors[] = 'Keep trying u still not close';
        }

        //check if user exists in the data base
        //this will check if the username exist
        $query = $db->query("SELECT * FROM users WHERE login = '$login'");

        $user = mysqli_fetch_assoc($query);
        $userCount = mysqli_num_rows($query);


        if($userCount<1){

          $errors[]='The user died';

        }

        //check the password
        if(!password_verify($password, $user['password'])){
          $errors[] = 'You fail badly';
        }

        //check for errors
        if(!empty($errors)){

          echo display_errors($errors);

        }else{
          //log user in
          $user_id = $user['id'];
          login($user_id);
        }

      }

      ?>

    </div>

    <h2 class="text-center">Login</h2><hr>

    <form action="login.php" method="post">

      <div class="form-group">

        <label for="email">Email</label>
        <input type="text" name="login" id="login" class="form-control" value="<?=$login;?>">

      </div>

      <div class="form-group">

        <label for="password">Password</label>
        <input type="password" name="password" id="password" class="form-control" value="<?=$password;?>">

      </div>

      <div class="form-group">

        <input class="btn btn-primary" type="submit" value="Login">

      </div>

    </form>

    <p class="text-right">

      <a href="/index.php" alt="home">Visit Site</a>

    </p>

  </div>
