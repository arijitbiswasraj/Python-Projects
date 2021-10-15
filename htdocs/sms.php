<?php
include('dbcon.php');
session_start(); 
if(!isset($_SESSION['user_hfcgym'])){
  header('location:index.php');
}
include('smsapi.php');
?>
<!DOCTYPE html>
<html>
    <head>

    </head>
    <body>
        <?php
        
        // function closed($phno, $name, $date, $reason){
        //     $phno = '91'.$phno;
        //     sendsms($phno, 'Hi '.$name.', this is to notify you that HALDER FITNESS CENTER will be closed on '.$date.' due to '.$reason.' - HALDER ENTERPRISES');
        // }
        
        // function wish($phno, $name, $festival){
        //     $phno = '91'.$phno;
        //     sendsms($phno,'Hi '.$name.', we at HALDER FITNESS CENTER wish you a very HAPPY '.$festival.'. May your life fill with happiness and health - HALDER ENTERPRISES');
        // }
        // function bdaywish($phno, $name){
        //     $phno = '91'.$phno;
        //     sendsms($phno,'Dear '.$name.', we at HALDER FITNESS CENTER wish you a very happy birthday and a lifetime of good health and an amazing physique. May all your dreams and wishes come true. - HALDER ENTERPRISES');
        // }
        
        // function annwish($phno, $name1, $name2){
        //     $phno = '91'.$phno;
        //     sendsms($phno, 'Dear '.$name1.' and '.$name2.', we at HALDER FITNESS CENTER wish you a very happy and amazing anniversary. Wishing you a long and wonderful healthy life together on this grand occasion - HALDER ENTERPRISES');
        // }

        
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
                    $query 	= mysqli_query($conn, "select * from user u where day(u.bday) = $day && month(u.bday)= $month && ( u.id not in (select id from smsdata) or (select count(bdayreminder) from smsdata where id=u.id and year(bdayreminder) <> $year) <> 0 );");
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
                        <input type="submit" value="Send Birthday Wish SMS" name="bdaywish"> ';
                        echo '</td>';
                        echo '</tr>';

                    }    
                    $query = mysqli_query($conn, "select * from user u where day(u.bday) = 13 && month(u.bday)= 11 && (select count(bdayreminder) from smsdata where id=u.id and year(bdayreminder) = 2021) <> 0;");
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
                      <input type="submit" value="Send Anniverdary Wish SMS" name="annwish">';
                      echo '</td>';
                      echo '</tr>';

                  }    
                  //not eligible for sms
                  $query = mysqli_query($conn, "select * from user u where day(u.bday) = 13 && month(u.bday)= 11 && (select count(bdayreminder) from smsdata where id=u.id and year(bdayreminder) = 2021) <> 0;");
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
    </body>
</html>