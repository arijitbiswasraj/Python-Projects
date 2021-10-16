<?php
include('dbcon.php');
session_start(); 
if(!isset($_SESSION['user_hfcgym'])){
  header('location:index.php');
}

if(isset($_POST['changepass'])){
    $old = $_POST['oldpassword'];
    $new = $_POST['newpass'];
   
    
        $query 	= mysqli_query($conn, "SELECT `password` FROM `admin` WHERE `name` = 'hfcadmin';");
        $row = mysqli_fetch_array($query);

        if($row['password'] != $old){
            echo '<h4>Please enter the correct old password.</h4>';
        }
        else{
            $query 	= mysqli_query($conn, "UPDATE `admin` SET `password` = '$new' WHERE `name`='hfcadmin';");
            if (!$query) {
                die ('SQL Error: ' . mysqli_error($conn));
            }
            else{
                echo '<h4>Password change successful.</h4>';
            }
        }
    }
?>
<!DOCTYPE html>
<html>

<head>
<?php include('header.php');?>
</head>
<body>
<?php 
  include('menu.php');
  ?>
    <form method="post" action="changepass.php">
        <label>Current Password:</label>
        <input type="password" name="oldpassword">
        <label>New Password</label>
        <input type="password" name="newpass">     
        <input type="submit" name="changepass" value="CHANGE PASSWORD">
    </form>
</body>
</html>