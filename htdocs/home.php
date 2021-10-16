<?php 
session_start(); 
if(!isset($_SESSION['user_hfcgym'])){
  header('location:index.php');
}
include('dbcon.php');
?>

<html>
<head>
<?php include('header.php');?>
</head>
<body>
  <?php 
  include('menu.php');
  ?>
  <!-- <h1>Gym Management SOFTWARE</h1>
  <a href="admission.php">Admission</a>
  <a href="stumanage.php">Student Management</a>
  <a href="dues.php">Fees and Dues</a>
  <a href="packs.php">Packs</a>
  <a href="stats.php">Stats</a>
  <a href="sms.php">SMS</a>
  <a href="changepass.php">Change Password</a>
  <a href="logout.php">Logout</a> -->
</body>
</html>