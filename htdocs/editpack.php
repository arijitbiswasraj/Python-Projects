<?php
include('dbcon.php');
session_start(); 
if(!isset($_SESSION['user_hfcgym'])){
  header('location:index.php');
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
        <?php 
        if(isset($_POST['editpack'])){
            $id = $_POST['id'];
            //use this id to run the sql query to update the pack
            echo '<form method="post" action="editpack.php">
            <label>Pack Name:</label>
            <input type="text" required name="packname">
            <label>Registration Fees</label>
            <input type="number"required name="regfees">
            <label>Monthly Fees</label>
            <input type="number" required name="monthly">
            <label>Discount Percentage(Put 0 if entering discount amount)</label>
            <input type="number" required name="discount">
            <label>Discount Amount(Put 0 if entering discount percentage)</label>
            <input type="number" required name="disamount">
            <label>Duration in Days</label>
            <input type="number" required name="duration">
            <input type="submit" name="updatepack" value="Create Pack">            
            <input name="id" type="hidden" value="'.$id.'">
        </form>';
            
        }
        if(isset($_POST['updatepack'])){
            $packname = $_POST['packname'];
            $regfees = $_POST['regfees'];
            $monthly = $_POST['monthly'];
            $discount = $_POST['discount'];
            $disamount = $_POST['disamount'];
            $duration = $_POST['duration'];
            $id = $_POST['id'];
            //UPDATE `package` SET `name` = 'fdghjfhjfgj', `regfees` = '4545', `monthly` = '87', `discount` = '90', `disamount` = '5656', `duration` = '68988' WHERE `package`.`id` = 8;            
            $query 	= mysqli_query($conn, "UPDATE `package` SET `name` = '$packname', `regfees` = '$regfees', `monthly` = '$monthly', `discount` = '$discount', `disamount` = '$disamount', `duration` = '$duration' WHERE `package`.`id` = $id;");
            if (!$query) {
                die ('SQL Error: ' . mysqli_error($conn));
            }
            else{
                echo '<h4>Pack edited successfully.</h4>';               
            }
        }
        ?>

    </body>
</html>