<?php
session_start();


// initializing variables
$username = "";
$email    = "";
$errors = array();
date_default_timezone_set('Europe/Belgrade');
// connect to the database
$db = mysqli_connect('localhost', 'root','', 'mydb');

function get_user_ID($korisnik)
{
  $id_user='';
  $db = mysqli_connect('localhost', 'root','', 'mydb');
  $db1=mysqli_stmt_init($db);
  mysqli_stmt_prepare($db1, "SELECT korisnik_id FROM korisnik WHERE username=?");
  mysqli_stmt_bind_param($db1, "s", $korisnik);
  mysqli_stmt_execute($db1);
  mysqli_stmt_bind_result($db1, $id_user);
  mysqli_stmt_fetch($db1);
  return $id_user;
}

if (isset($_POST['register']))
{
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($username)) { array_push($errors, "Username is required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($password)) { array_push($errors, "Password is required"); }

  $db1=mysqli_stmt_init($db);
  mysqli_stmt_prepare($db1, "SELECT korisnik_id FROM korisnik WHERE username=? OR email=? LIMIT 1");
  mysqli_stmt_bind_param($db1, "ss", $username, $email);
  mysqli_stmt_execute($db1);
  mysqli_stmt_bind_result($db1, $user);
  mysqli_stmt_fetch($db1);
  if ($user)
  {
    array_push($errors, "Username ili email vec postoji.");
  }

  if (count($errors) == 0)
  {
    $hashed_password = password_hash($password,PASSWORD_BCRYPT);//encrypt the password before saving in the database
    mysqli_stmt_prepare($db1, "INSERT INTO korisnik (email, password, username) VALUES(?,?,?)");
    mysqli_stmt_bind_param($db1, "sss", $email, $hashed_password, $username);
    mysqli_stmt_execute($db1);

    $kupac_id=get_user_ID($username);
    mysqli_stmt_prepare($db1, "INSERT INTO kupac (kupac_id) VALUES(?)");
    mysqli_stmt_bind_param($db1, "i", $kupac_id);
    mysqli_stmt_execute($db1);
  }
}

if (isset($_POST['login']))
{
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password = mysqli_real_escape_string($db, $_POST['password']);
  if (empty($username)) {
  	array_push($errors, "Username is required");
  }
  if (empty($password)) {
  	array_push($errors, "Password is required");
  }

  if (count($errors) == 0)
  {
    $passworddb;
    $db1=mysqli_stmt_init($db);
    mysqli_stmt_prepare($db1, "SELECT password FROM korisnik WHERE username=?");
    mysqli_stmt_bind_param($db1, "s", $username);
    mysqli_stmt_execute($db1);
    mysqli_stmt_bind_result($db1, $passworddb);
    mysqli_stmt_fetch($db1);
    if (password_verify($password, $passworddb))
    {
      echo "Password verified!";
  	  $_SESSION['username'] = $username;
  	  $_SESSION['success'] = "You are now logged in";
      $_SESSION['loggedin']= true;
      $id=get_user_ID($username);
      header('location: landing.php');
    }
    else
    {
      array_push($errors, "Wrong username/password combination");
    }
  }
}