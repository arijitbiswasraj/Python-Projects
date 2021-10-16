<?php
include('dbcon.php');
session_start(); 
if(!isset($_SESSION['user_hfcgym'])){
  header('location:index.php');
}
include('smsapi.php');
?>
<?php
    if(isset($_POST['bdaywish'])){
        $phno = $_POST['phno'];
        $name = $_POST['stuname'];
        $today = $_POST['today'];
        $idno = $_POST['idno'];
        try{
            bdaywish($phno,$name);            
            echo '<h4>SMS Sending Successful<h4>';
        }
        catch(Exception $e){
            echo '<h4>Error in sending SMS. Please try again later.'.$e->getMessage().'</h4>';
        }

        $query 	= mysqli_query($conn, "UPDATE `user` SET `bdaysms` = '$today' WHERE `user`.`id` = $idno;");
        if (!$query) {
            die ('SQL Error: ' . mysqli_error($conn));
        }
    }
    if(isset($_POST['annwish'])){
        $name1= $_POST['stuname1'];
        $name2 = $_POST['stuname2'];
        $phno1 = $_POST['phno1'];
        $phno2 = $_POST['phno2'];
        $today = $_POST['today'];
        $slno = $_POST['slno'];
        try{
            annwish($phno1,$phno2, $name1, $name2);        
            echo '<h4>SMS Sending Successful<h4>';
        }
        catch(Exception $e){
            echo '<h4>Error in sending SMS. Please try again later.'.$e->getMessage().'</h4>';
        }


        $query 	= mysqli_query($conn, "UPDATE `couple` SET `anndate` = '$today' WHERE `couple`.`slno` = $slno;");
        if (!$query) {
            die ('SQL Error: ' . mysqli_error($conn));
        }
    }
    if(isset($_POST['sendclosedsms'])){
        //SELECT mobile, name FROM `user`;
        $query 	= mysqli_query($conn, "SELECT mobile, name FROM `user`;");
        $date = $_POST['closeddate'];
        $reason = $_POST['reason'];
        
        try{
            while($row=mysqli_fetch_array($query)){
                $phno = $row['mobile'];
                $name = $row['name'];
                closed($phno,$name, $date, $reason);
            }
        }
        catch(Exception $e){
            echo '<h4>Error in sending SMS. Please try again later.'.$e->getMessage().'</h4>';
        }
    }
    if(isset($_POST['sendwish'])){
        $query 	= mysqli_query($conn, "SELECT mobile, name FROM `user`;");        
        $reason = $_POST['reason'];
        
        try{
            while($row=mysqli_fetch_array($query)){
                $phno = $row['mobile'];
                $name = $row['name'];
                wish($phno,$name, $reason);
            }
        }
        catch(Exception $e){
            echo '<h4>Error in sending SMS. Please try again later.'.$e->getMessage().'</h4>';
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
        <h4>Birthdays Today</h4>
        <table>
            <tr>
                <th>Name</th>
                <th>ID</th>
                <th>Birthday</th>
                <th>Send SMS</th>
            </tr>
                <?php
                  
                    $month = date('m');
                    $day= date('d');
                    $year='20'.date('y');
                    $today = date('Y-m-d');
                    $query 	= mysqli_query($conn, "select * from user u where day(u.bday) = $day && month(u.bday)= $month && ( u.bdaysms IS NULL or year(u.bdaysms) <> $year);");
                    if(!$query){
                        die ('SQL Error: ' . mysqli_error($conn));
                    }
                    
                    while($row=mysqli_fetch_array($query)){
                        //list of bdays                        
                        echo '<tr>';
                        echo '<td>'.$row['name'].'</td>';
                        echo '<td>'.$row['id'].'</td>';
                        echo '<td>'.$row['bday'].'</td>';
                        echo '<td>';
                        //check if sms is already sent 
                        echo '<form method="post" action="sms.php">
                        <input type="hidden" name="stuname" value="'.$row['name'].'">
                        <input type="hidden" name="phno" value="'.$row['mobile'].'">
                        <input type="hidden" name="idno" value="'.$row['id'].'">
                        <input type="hidden" value="'.$today.'" name="today">
                        <input type="submit" value="Send Birthday Wish SMS" name="bdaywish"> ';
                        echo '</td>';
                        echo '</tr>';

                    }    
                    $query = mysqli_query($conn, "select * from user u where day(u.bday) = $day && month(u.bday)= $month && year(u.bdaysms) = $year;");
                    if(!$query){
                        die ('SQL Error: ' . mysqli_error($conn));
                    }     
                    while($row=mysqli_fetch_array($query)){
                        
                        echo '<tr>';
                        echo '<td>'.$row['name'].'</td>';
                        echo '<td>'.$row['id'].'</td>';
                        echo '<td>'.$row['bday'].'</td>';
                        echo '<td> SMS Already Sent</td>';
                        echo '</tr>';
                    }                   

                ?>
            
        </table>
        <h4>Anniversaries Today:</h4>
        <table>
        <tr>
            <th>Name 1</th>
            <th>ID 1</th>
            <th>Name 2</th>
            <th>ID 2</th>
            <th>Anniversary</th>
            <th>Send SMS</th>
        </tr>
        <?php
                  
                  $month = date('m');
                  $day= date('d');
                  $year='20'.date('y');
                  //eligible for sms
                  $query = mysqli_query($conn, "SELECT * FROM `couple` WHERE day(anniversary) = $day && month(anniversary) = $month && ( anndate IS NULL or year(anndate) <> $year);");
                  if(!$query){
                      die ('SQL Error: ' . mysqli_error($conn));
                  }
                  
                  while($row=mysqli_fetch_array($query)){
                      //list of bdays                        
                      echo '<tr>';
                      echo '<td>'.$row['name1'].'</td>';
                      echo '<td>'.$row['id1'].'</td>';
                      echo '<td>'.$row['name2'].'</td>';
                      echo '<td>'.$row['id2'].'</td>';
                      echo '<td>'.$row['anniversary'].'</td>';
                      echo '<td>';
                      //check if sms is already sent 
                      echo '<form method="post" action="sms.php">
                      <input type="hidden" name="stuname1" value="'.$row['name1'].'">
                      <input type="hidden" name="phno1" value="'.$row['mobile1'].'">
                      <input type="hidden" name="stuname2" value="'.$row['name2'].'">
                      <input type="hidden" name="phno2" value="'.$row['mobile2'].'">
                      <input type="hidden" value="'.$today.'" name="today">
                      <input type="hidden" value="'.$row['slno'].'" name="slno">
                      <input type="submit" value="Send Anniverdary Wish SMS" name="annwish">';
                      echo '</td>';
                      echo '</tr>';

                  }    
                  //not eligible for sms
                  $query = mysqli_query($conn, "SELECT * FROM `couple` WHERE day(anniversary) = $day && month(anniversary) = $month && year(anndate) = $year;");
                  if(!$query){
                      die ('SQL Error: ' . mysqli_error($conn));
                  }     
                  while($row=mysqli_fetch_array($query)){
                      
                      echo '<tr>';
                      echo '<td>'.$row['name1'].'</td>';
                      echo '<td>'.$row['id1'].'</td>';
                      echo '<td>'.$row['name2'].'</td>';
                      echo '<td>'.$row['id2'].'</td>';
                      echo '<td>'.$row['anniversary'].'</td>';
                      echo '<td> SMS Already Sent</td>';
                      echo '</tr>';
                  }                   

              ?>        
        </table>
        <h4>Send Closed SMS to everyone.</h4>
        <form method="post" action="sms.php">
            <label>Closed Date: </label>
            <input name="closeddate" type="date">
            <label>Closed Reason (10 characters)</label>
            <input name="reason" type="text" maxlength="10">
            <input type="submit" name="sendclosedsms" value="Send Closed SMS">
        </form>
        <h4>Send Festival Wish to Everyone</h4>
        <form method="post" action="sms.php">       
            <label>Festival Name (max 10 characters):</label>
            <input name="reason" type="text" maxlength="10">
            <input type="submit" name="sendwish" value="Send Festival Wish SMS">
        </form>
    </body>
</html>