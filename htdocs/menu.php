<div class="topnav" id="myTopnav" >
  <a href="home.php">Home</a>
  <a href="admission.php">Admission</a>
  <a href="stumanage.php">Student Management</a>
  <a href="dues.php">Fees and Dues</a>
  <a href="packs.php">Packs</a>
  <a href="stats.php">Stats</a>
  <a href="sms.php">SMS</a>
  <a href="changepass.php">Change Password</a>
  <a href="logout.php">Logout</a>
  <a href="javascript:void(0);" class="icon" onclick="myFunction()">
    <i class="fa fa-bars"></i>
  </a>
</div>

<script>
function myFunction() {
  var x = document.getElementById("myTopnav");
  if (x.className === "topnav") {
    x.className += " responsive";
  } else {
    x.className = "topnav";
  }
}
</script>