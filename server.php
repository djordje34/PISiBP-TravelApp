<?php
require_once 'core/init.php';
function checkIfUsernameExists($uname,$optId){
  $db = mysqli_connect('localhost', 'root','', 'mydb');
  $db1=mysqli_stmt_init($db);
  if (!$optId){
  mysqli_stmt_prepare($db1, "SELECT kupac_id FROM kupac WHERE username=?");
  mysqli_stmt_bind_param($db1, "s", $uname);
  mysqli_stmt_execute($db1);
  mysqli_stmt_bind_result($db1, $res);
  mysqli_stmt_fetch($db1);

  }
  else{

    mysqli_stmt_prepare($db1, "SELECT kupac_id FROM kupac WHERE username=? and kupac_id!=?");
    mysqli_stmt_bind_param($db1, "si", $uname,$_SESSION['id']);
    mysqli_stmt_execute($db1);
    mysqli_stmt_bind_result($db1, $res);
    mysqli_stmt_fetch($db1);
  }

  if (!$res){
    return false;
  }
  return true;
}
  #UNUTRASNJE IF IZDVOJITI KAO ZASEBNU FUNKCIJU SA OPCIJOM DA SE MENJA IZMEDJU USERNAME I EMAIL
if (isset($_POST["username"])) {
  $response='';
  $db = mysqli_connect('localhost', 'root','', 'mydb');
  $uname = mysqli_real_escape_string($db,$_POST["username"]);

  if (!Session::exists(Config::get('session/session_name'))){
    if (checkIfUsernameExists($uname,0)){
      $response = "<span style='color: red;text-align:center'>Zauzeto</span><script>$(':submit').attr('disabled', true);</script>";
    }
    else{
      $response = "<span style='color: green;text-align:center'>Slobodno</span><script>$(':submit').attr('disabled', false);</script>";
    }

  }
  else{ #UKOLIKO kupac HOCE DA PROMENI USERNAME


    
    if (checkIfUsernameExists($uname,1)){
        $response = "<span style='color: red;'>Zauzeto</span><script>$(':submit').attr('disabled', true);</script>";
    }
  }

  echo $response;
}