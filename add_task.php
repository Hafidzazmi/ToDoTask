<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php'; /*database connection*/
include 'includes/head.php'; /*css and jquery*/

  //get brand from SQLiteDatabase
  $sql = "SELECT * FROM tasks ORDER BY user";
  $result = $db->query($sql);

  // var for error
  $errors = array();

  //edit brands
  if(isset($_GET['edit']) && !empty($_GET['edit'])){
      $edit_id = (int)$_GET['edit'];
      $edit_id = sanitize($edit_id);
      $sql2 = "SELECT * FROM tasks WHERE id ='$edit_id'";
      $edit_result = $db->query($sql2);
      $eBrand = mysqli_fetch_assoc($edit_result);
  }


  //Delete brands
  if (isset($_GET['delete'])&& !empty($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    $delete_id = sanitize($delete_id);
    $sql = "DELETE FROM tasks WHERE id = '$delete_id'";
    $db->query($sql);
    header('Location: add_task.php');
    }


  //if add form is submitted

  if(isset($_POST['add_submit'])){
      $task_title = sanitize($_POST['task_title']);
      $task_desc = sanitize($_POST['task_desc']);
      $user = sanitize($_POST['username']);
      $email = sanitize($_POST['email']);
      //check brand is blank
      if($_POST['task_title'] == ''){
        $errors[] .= 'Must enter task title';
      }

      // check if brand already_exists
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

        //add brand to database
        $sql = "INSERT INTO tasks (task_title, task_desc,user,email) VALUES ('$task_title', '$task_desc', '$user', '$email')";

        if(isset($_GET['edit'])){
          $sql = "UPDATE task SET task_title = '$task_title' WHERE id = '$edit_id'";
        }

        $db->query($sql);
        header('Location: add_task.php');

      }

  }

?>

<h2 class="text-center">Brand homepage</h2><hr>

<!-- Brand form-->
<div class="text-center">
  <form class="form-inline" action="add_task.php<?= ((isset($_GET['edit']))?'?edit='.$edit_id:''); ?>" method="post">
    <div class="form-group">

      <?php
      $task_title = '';
        if(isset($_GET['edit'])){
            $task_title = $eBrand['user'];
        }
        else {
          if(isset($_POST['brand'])){
            $brand_value = sanitize($_POST['user']);
          }
        }
      ?>

      <label for="brand"><?=((isset($_GET['edit']))?'Edit':'Add A'); ?> Task title: </label>

      <input type="text" name="task_title" id="task_title" class="form-control" value="<?=$brand_value;?>">

      <label for="brand"> Task Description: </label>

      <input type="text" name="task_desc" id="task_desc" class="form-control" value="<?=$brand_value;?>">

      <label for="brand">Username: </label>

      <input type="text" name="username" id="username" class="form-control" value="<?=$brand_value;?>">

      <label for="brand">Email: </label>

      <input type="email" name="email" id="email" class="form-control" value="<?=$brand_value;?>">

      <!--add cancel button for edit-->
      <?php if(isset($_GET['edit'])): ?>
        <a href="add_task.php" class="btn btn-default">Cancel</a>
      <?php endif; ?>

      <input type="submit" name="add_submit" value="<?=((isset($_GET['edit']))?'Edit':'Add');?> Brand" class="btn btn-success">
    </div>
  </form>
</div><hr>
<!-- Brand form-->

<!-- create table to edit brand-->
<table class="table table-bordered table-striped table-auto table-condensed">

  <!--table head-->
  <thead>
    <th></th>
    <th>body</th>
    <th></th>
  </thead>
  <!--table head-->

  <!--table content-->
  <tbody>

    <!-- row one-->
    <?php while($brand = mysqli_fetch_assoc($result)): ?>
      <tr>
        <!--edit button-->
        <td><a href="add_task.php?edit=<?=$brand['id'];?>" class="btn btn-xs btn-default">Edit</a></td>

        <td><?= $brand['task_desc'];?></td>

        <!--delete button-->
        <td><a href="add_task.php?delete=<?=$brand['id'];?>" class="btn btn-xs btn-default">Remove</a></td>

      </tr>
      <!-- row one-->
    <?php endwhile;?>

  </tbody>
  <!--table content-->

</table>
