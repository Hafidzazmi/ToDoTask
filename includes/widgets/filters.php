<?php

//fetch_data.php

require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php'; /*database connection*/

/*jquery pagination
set record per page */
$record_per_page = 4;
$page = '';
$output = '';
if(isset($_POST["page"]))
{
   $page = $_POST["page"];
}
else
{
   $page = 1;
}
$start_from = ($page - 1)*$record_per_page;
/*jquery pagination*/

/*filter function
// sort by Status 1.all | 2. pending | 3. Completed
*/
if(isset($_POST["action"]))
{
 $query = "SELECT * FROM tasks ";

 if(isset($_POST["sort"])){
   if($_POST["sort"]==1){
     $query .='';
   }
   if($_POST["sort"]==2){
     $query .= "WHERE status=1";
   }
   if($_POST["sort"]==3){
     $query .= "WHERE status=0";
   }

 }
 /* filter by username or email*/
 
 if(isset($_POST["search"]) && ($_POST["search"] != '') ){
   $keyword = '%'.sanitize($_POST["search"]).'%';
   if($_POST["sort"]==1){
     $query .="WHERE (user LIKE '$keyword' OR email  LIKE '$keyword')";
   }else{
   $query .= " AND (user LIKE '$keyword' OR email  LIKE '$keyword') ";
 }
 }

$query .= " ORDER BY created_at desc";

 $page_result = $db->query($query);

 $total_records = mysqli_num_rows($page_result);

 $total_pages = ceil($total_records/$record_per_page);

 $query .= " LIMIT {$start_from}, {$record_per_page}";

 $statement = $db->query($query);

 $total_row = mysqli_num_rows($statement);
 $output = '';
 if($total_row > 0)
 {
   $output .= '<table class="table ">
     <thead>
       <tr>
         <th>Task</th>
         <th>User</th>
         <th>Email</th>
         <th>Status</th>
         <th></th>
       </tr>
     </thead>
     <tbody>';
  while($result = mysqli_fetch_assoc($statement))
  {
    if ($result['status']==1){
      $status = 'done';
    }else{
      $status = 'pending';
    }
    $output .= ' <tr>
       <td>'.$result['task_title'].'</td>
       <td>'.$result['user'].'</td>
       <td>'.$result['email'].'</td>
       <td>'.$status.'</td>
       <td><a  onclick="details_display('.$result['id'].')">More details</a></td>
     </tr>';
  }
  $output .= '</tbody>
</table>';

  for($i=1; $i<=$total_pages; $i++)
  {
     $output .= "<span class='pagination_link' style='cursor:pointer; padding:6px; border:1px solid #ccc;' id='".$i."'>".$i."</span>";
  }

 }
 else
 {
  $output = '<h3 style="text-align:center; padding:20px;">No data found :)</h3>';
 }
 echo $output;
}

?>
