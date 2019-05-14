<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php'; /*database connection*/

//when theres action trigger ajax
// collect all information for the specific task and display
if(isset($_POST["action"]) && !empty($_POST['id']) )
{
  $id = sanitize($_POST['id']);
 $query = "SELECT * FROM tasks WHERE id= $id";
 $statement= $db->query($query);




 $output = '
 ';

   $output .= '<div class="container-fluid">';
  while($result = mysqli_fetch_assoc($statement))
  {
    if ($result['status']==1){
      $status = 'done';
    }else{
      $status = 'pending';
    }
    $output .= '  <h2 class="text-center">'.$result['task_title'].'</h2>
                  <h6 class="text-center">Created by : '.$result['user'].' | '.$result['email'].'</h6>
                <p class="text_desc"><strong>Description : </strong><br>'.nl2br($result['task_desc']).'</p>
                <div class="container-fluid">
                  <p class="text_right">'.$result['created_at'].'</p>
                </div>
                ';
  }
  if(is_logged_in()){


  $output .= '<div class="container-fluid btn_edit">
                <a href="index.php?edit='.$id.'" class="btn btn-success">Edit</a>';
  if ($status == 'pending'){
    $output .= '<a href="index.php?mark='.$id.'" class="btn btn-primary">Mark as done</a>';
  }else{
    $output .= '<a href="index.php?undo='.$id.'" class="btn btn-danger">Undo</a>';
  }
  $output .= '
              </div>
            </div>';
}else{
  $output .= '<div class="container-fluid"><a href="login.php" class="btn btn-dark">Edit</a>';
  if ($status == 'pending'){
    $output .= '<a href="login.php" class="btn btn-dark">Mark as done</a>';
  }else{
    $output .= '<a href="login.php" class="btn btn-dark">Undo</a>';
  }
  $output .= '
              </div>
            </div>';

}
 echo $output;
}

?>
