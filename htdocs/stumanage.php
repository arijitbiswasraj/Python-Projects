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
        <form action="stumanage.php" method="POST">
            <label>Search By ID: </label>
            <input type="number" name="idno">
            <input type="submit" value="Search" name="search">
        </form>
        <?php
        if ( isset($_POST['search'])){
            $idno = $_POST['idno'];    
            $query 	= mysqli_query($conn, "SELECT * FROM `user` where id = $idno"); 
            $number = mysqli_num_rows($query);
            if (!$query) {
                die ('Search Error please enter correct id no and : ' . mysqli_error($conn));
            }
            else if($number == 0){
                echo '<h4>ID number not found! Please enter a correct ID number.</h4>';
            }
            else{
                echo '<h4>Search Sucessfull!</h4>';                                      
                echo '<h4>Student Details:</h4>';                                      
                $row=mysqli_fetch_array($query);
                echo '<table>';
                echo'<tr><td>ID: </td><td>'.$row['id'].'</td></tr>';
                echo'<tr><td>Name: </td><td>'.$row['name'].'</td></tr>';
                echo'<tr><td>Address: </td><td>'.$row['address'].'</td></tr>';
                echo'<tr><td>Profile PIC: </td><td>'.$row['profilepicfilename'].'</td></tr>';
                echo'<tr><td>Mobile No.: </td><td>'.$row['mobile'].'</td></tr>';
                echo'<tr><td>Email: </td><td>'.$row['mail'].'</td></tr>';
                echo'<tr><td>Gender: </td><td>'.$row['gender'].'</td></tr>';
                echo'<tr><td>Emergency Name: </td><td>'.$row['emergencyname'].'</td></tr>';
                echo'<tr><td>Emergency No.: </td><td>'.$row['emergencyno'].'</td></tr>';
                echo'<tr><td>Father\'s Name: </td><td>'.$row['fathername'].'</td></tr>';
                echo'<tr><td>Birthday: </td><td>'.$row['bday'].'</td></tr>';
                echo'<tr><td>KYC Document Type: </td><td>'.$row['authdoctype'].'</td></tr>';
                echo'<tr><td>KYC Doc Number: </td><td>'.$row['authdocno'].'</td></tr>';
                echo'<tr><td>Health Problem: </td><td>'.$row['healthproblem'].'</td></tr>';
                echo'<tr><td>Packname: </td><td>'.$row['packid'].'</td></tr>';
                echo'<tr><td>Pack Start Date: </td><td>'.$row['packstartdate'].'</td></tr>';
                echo'<tr><td>Pack End Date: </td><td>'.$row['packenddate'].'</td></tr>';
                echo '</table>';
                echo '<form action="stumanage.php" method="post">';
                echo '<input type="hidden" value="'.$row['id'].'" name="idno">';
                echo '<input type="submit" name="remove" value="Remove User">';
                echo '</form>';
            }

        }
        if(isset($_POST['remove'])){
            $idno = mysqli_real_escape_string($conn, $_POST['idno']);
            $query 	= mysqli_query($conn, "DELETE FROM `user` WHERE id = $idno");
            $query1 	= mysqli_query($conn, "DELETE FROM `couple` WHERE id1 = $idno || id2 = $idno;");
            if (!$query || !$query1) {
                die ('SQL Error: ' . mysqli_error($conn));
            }
            else{
                echo '<h4>User deleted Sucessfully.</h4>';
            }
           
        }
        ?>

    </body>
</html>