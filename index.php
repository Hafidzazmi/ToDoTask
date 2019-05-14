<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php'; /*database connection*/
include 'includes/head.php'; /*css and jquery*/

$sqlTask = "SELECT * FROM tasks";
$featuredTask = $db->query($sqlTask);

//get all tasks from MYSQLI
$sql = "SELECT * FROM tasks ORDER BY user";
$result = $db->query($sql);

// var for error
$errors = array();

//edit task for admin//
if(isset($_GET['edit']) && !empty($_GET['edit'])){
    if(!is_logged_in()){
       header('Location: login.php');
    }
    $edit_id = (int)$_GET['edit'];
    $edit_id = sanitize($edit_id);
    $sql2 = "SELECT * FROM tasks WHERE id ='$edit_id'";
    $edit_result = $db->query($sql2);
    $e_task = mysqli_fetch_assoc($edit_result);
    echo'<style>
    .form_container{
      display: block;
    }
        </style>';
}


//Mark as done//
if (isset($_GET['mark'])&& !empty($_GET['mark'])) {
  $delete_id = (int)$_GET['mark'];
  $delete_id = sanitize($delete_id);
  $sql = "UPDATE tasks SET status = 1 WHERE id = '$delete_id'";
  $db->query($sql);
  header('Location: index.php');
  }

  if (isset($_GET['undo'])&& !empty($_GET['undo'])) {
    $delete_id = (int)$_GET['undo'];
    $delete_id = sanitize($delete_id);
    $sql = "UPDATE tasks SET status = 0 WHERE id = '$delete_id'";
    $db->query($sql);
    header('Location: index.php');
    }


//if add form is submitted add new task function //

if(isset($_POST['add_submit'])){
    $task_title = sanitize($_POST['task_title']);
    $task_desc = sanitize($_POST['task_desc']);
    $user = sanitize($_POST['username']);
    $email = sanitize($_POST['email']);
    //check brand is blank
    if($_POST['task_title'] == ''){
      $errors[] .= 'Must enter task title';
    }

    // check if task title already_exists//
    $sql = "SELECT * FROM tasks WHERE task_title = '$task_title'";

    if (isset($_GET['edit'])) {
      $sql = "SELECT * FROM tasks WHERE task_title = '$task_title' AND id != '$edit_id'";
    }

    $results = $db->query($sql);
    $count = mysqli_num_rows($results);
    if($count>0){
      $errors[] .= $task_title. ' Brand already exist';
    }

    //display $errors
    if(!empty($errors)){
      echo display_errors($errors);
    }

    else {

      //add task to database
      $sql = "INSERT INTO tasks (task_title, task_desc,user,email) VALUES ('$task_title', '$task_desc', '$user', '$email')";

      if(isset($_GET['edit'])){
        $sql = "UPDATE tasks SET task_title = '$task_title', task_desc = '$task_desc', user = '$user' , email = '$email' WHERE id = '$edit_id'";
      }

      $db->query($sql);
      header('Location: index.php');

    }

}

?>

<h2 class="text-center head_line">Task homepage</h2>

<div class="login_center">
  <?php if(is_logged_in()):;?>
    <a class="btn btn-primary" href="logout.php">Logout</a>
  <?php else:?>
    <a class="btn btn-primary" href="login.php">Login</a>
  <?php endif;?>
  <a class="btn btn-success" id="show"  onclick="show()">Add task +</a>
  <a class="btn btn-warning" id="hide"  onclick="hide()">Hide -</a>
</div>



<!-- Task form-->

<div class="text-center container form_container" id="form_container">
  <?php
  $task_title = '';
  $task_desc = '';
  $username = '';
  $email = '';
    if(isset($_GET['edit'])){
        $task_title = $e_task['task_title'];
        $task_desc = $e_task['task_desc'];
        $username = $e_task['user'];
        $email = $e_task['email'];
    }
    else {
      if(isset($_POST['task'])){
        $task_title = sanitize($_POST['task_title']);
        $task_desc = ((sanitize($_POST['task_desc'])));
        echo($task_desc);
        $username = sanitize($_POST['username']);
        $email = sanitize($_POST['email']);
      }
    }
  ?>

  <form class="form-horizontal"id="edit_form" action="index.php<?= ((isset($_GET['edit']))?'?edit='.$edit_id:''); ?>" method="post">

    <div class="form-group">

      <div class="row ">

        <div class="col-2">
          <label for="task_title"><?=((isset($_GET['edit']))?'Edit':'Add A'); ?> Task title: </label>
        </div>

        <div class="col-sm-10">
          <input type="text" name="task_title" id="task_title" class="form-control" value="<?=$task_title;?>">
        </div>

      </div>

    </div>

    <div class=" form-group">

      <div class="row">
        <div class="col-6">

          <div class="row">
            <div class="col-2">
              <label for="username">Username: </label>
            </div>
            <div class="col-sm-10">
              <input type="text" name="username" id="username" class="form-control" value="<?=$username;?>">
            </div>
          </div>

        </div>

        <div class="col-6">
          <div class="row">
            <div class="col-2">
              <label for="email">Email: </label>
            </div>
            <div class="col-sm-10">
              <input type="email" name="email" id="email" class="form-control" value="<?=$email;?>">
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="form-group">
      <div class="row">
        <div class="col-2">
          <label for="task_desc"> Task Description: </label>
        </div>
        <div class="col-10">
          <textarea class="form-control" rows="5" id="task_desc" name="task_desc"><?=$task_desc;?></textarea>
        </div>

      </div>
    </div>


      <!--add cancel button for edit-->
      <?php if(isset($_GET['edit'])): ?>
        <a href="index.php" class="btn btn-default">Cancel</a>
      <?php endif; ?>

      <input type="submit" name="add_submit" value="<?=((isset($_GET['edit']))?'Edit':'Add');?> Task" class="btn btn-success">
    </div>
  </form>
</div><hr>



 <div class="container-fluid">

   <div class="row">
     <div class="col-8 all_task">
       <h2>To Do Table</h2>
       <p>The table shows the task flow among workers:</p>

       <div class="filter_container container">

     <div class="row">
        <h6 class="filter_head">Filter by : </h6>
       <div class="filter_dropdown">

        <select class="form-control common_selector"  name="sort" id="sort">
          <option value="1">All status</option>
         <option value="2">Pending</option>
         <option value="3">Done</option>

        </select>

       </div>
       <div class="search_bar">
           <input class="search form-control" id="search" type="search" autocomplete="off" placeholder="username or email">
       </div>

     </div>
   </div>

    <div class="filter_data"></div>

    </div>


    <div class="col-4"><div class=" detail_data"></div>

    </div>
  </div>
 </div>


<script>

// display side details and edit button for admin
function details_display(id)
{

      var action = 'fetch_data';
    $.ajax({
        url:"includes/widgets/show_details.php",
        method:"POST",
        data:{action:action,id:id},
        success:function(data){
            $('.detail_data').html(data);
        }
    });
}

// show and hide function for edit and add task
function show(){
  document.getElementById("form_container").style.display = "block";
  document.getElementById("show").style.display = "none";
  document.getElementById("hide").style.display = "inline-block";
}
function hide(){
  document.getElementById("form_container").style.display = "none";

  document.getElementById("show").style.display = "inline-block";
  document.getElementById("hide").style.display = "none";
}

// jquery for filter and edit
$(document).ready(function(){

    filter_data();



    function filter_data(page,search)
    {
        $('.filter_data').html('<div id="loading" style="" ></div>');
        var action = 'fetch_data';
        var sort = $('#sort').val();


        $.ajax({
            url:"includes/widgets/filters.php",
            method:"POST",
            data:{action:action,sort:sort,page:page,search:search},
            success:function(data){
                $('.filter_data').html(data);
            }
        });
    }


      $(document).on('click', '.pagination_link',function(){
        var page = $(this).attr("id");
        var search = document.getElementById("search").value;
        filter_data(page,search);

      });

      $(document).on('click', '.common_selector',function(){
        var page = 1;
        var search = document.getElementById("search").value;
        filter_data(page,search);

      });


    document.getElementById("search").addEventListener('input', function() {
      var page = 1;
      var search = document.getElementById("search").value;
      filter_data(page,search);


    });




})



</script>
