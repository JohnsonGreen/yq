<?php
header('content-type:application/json;charset=utf8');  
$result = $_POST;
if($_POST['username'] == "guorenjie")
  $response['is_err'] = 0;
else {
  $response['is_err'] = 1;
}
$response['result'] = "is_ok";
echo json_encode($response);
?>
