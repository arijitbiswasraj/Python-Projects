<?php
include('dbcon.php');
session_start(); 
if(!isset($_SESSION['user_hfcgym'])){
  header('location:index.php');
}
include('smsapi.php');
?>
<?php

//processing sms sending requests
if ( isset($_POST['sendmessage'])){
    $functionname = $_POST['function'];
    $name = $_POST['name'];
    $phno = $_POST['phno'];
    $id = $_POST['id'];
    $today = $_POST['today'];
    $query 	= mysqli_query($conn, "UPDATE `user` SET `reminderdate` = '$today' WHERE `user`.`id` = $id;");
    if (!$query) {
        die ('SQL Error: ' . mysqli_error($conn));
    }
    try{
        if($functionname == 'expired'){
            $date = $_POST['date'];
            expired($phno, $name, $date);
        }
        else if($functionname == 'tobeexpired'){
            $days = $_POST['days'];
            tobeexpired($phno, $name, $days);
        }
        else{
            tobeexpiredtoday($phno, $name);
        }
    }
    catch(Exception $e){
        echo '<h1>Error in sending SMS. Please try again later.'.$e->getMessage().'</h1>';
    }
}
if(isset($_POST['recharge'])){
    $idno = $_POST['idno'];    
    $startdate = $_POST['today'];
    $name = $_POST['name'];
    $phno = $_POST['phno'];

    $query1 	= mysqli_query($conn, "SELECT duration, name, monthly  FROM `package` WHERE id=7");
    $resultrow = mysqli_fetch_array($query1);

    $duration = $resultrow['duration'];
    

    $enddate = date('Y-m-d', strtotime($startdate. ' + '.$duration.' days'));
    //UPDATE `user` SET `packstartdate` = '2021-10-23', `packenddate` = '2021-11-24' WHERE `user`.`id` = 343;
    $query 	= mysqli_query($conn, "UPDATE `user` SET `packstartdate` = '$startdate', `packenddate` = '$enddate' WHERE `user`.`id` = $idno;");
    $year = $startdate.substr(0,4);
    $month = $startdate.substr(5,2);
    $amount = $resultrow['monthly'];
    $packname = $resultrow['name'];
    $query2 = mysqli_query($conn, "INSERT INTO `transaction` (`amount`, `date`, `duration`, `name`, `id`, `slno`, `year`, `month`, `packid`, `packname`) VALUES ('$amount', '$startdate', '$duration', '$name', '$idno', NULL, '$year', '$month', '7', '$packname');");

    if (!$query ||!$query2 || $query1) {
        die ('SQL Error: ' . mysqli_error($conn));
    }
    else{
        echo '<h4>Recharge Sucessful. Student will receive an sms shortly.</h4>';
        try{
            renewed($phno,$name,$startdate,$duration);
            echo '<h4>SMS Sending Successful<h4>';
        }
        catch(Exception $e){
            echo '<h4>Error in sending SMS. Please try again later.'.$e->getMessage().'</h4>';
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
            <?php
                $query 	= mysqli_query($conn, "SELECT name,id,packstartdate,packenddate,packid,mobile,reminderdate FROM `user`;"); 
                echo '<table>';                
                echo 
                '<tr>
                <th>ID</th>
                <th>Name</th>
                <th>Package</th>
                <th>Pack Startdate</th>
                <th>Pack EndDate</th>
                <th>Days Remaining</th>
                <th>Send SMS</th>
                <th>Recharge</th>
                </tr>';
                while($row = mysqli_fetch_array($query)){

                    $startdate = $row['packstartdate'];
                    $enddate = $row['packenddate'];                    
                    $reminderdate = $row['reminderdate'];                    

                    $today = date('Y-m-d');                    
                    

                    $diff = date_diff(date_create(date("M j, Y")), date_create(date('M j, Y', strtotime($enddate))));                        
                    $days =$diff->format('%d');
                                        
                 
                    $functionname = 'func';
                    $isexpired = false;
                    if (strtotime($enddate) < time()) {
                        
                        // past date                        
                        //send expired sms
                        $isexpired = true;
                        $functionname = 'expired';
                    }
                    else if($days == 0){
                        //send tobeexpiredtoday     
                        $functionname = 'tobeexpiredtoday';
                    }
                    else if($days  <= 3){
                        //send tobexpired
                        $functionname = 'tobeexpired';
                        
                    }
                    else{
                        //dont show on list
                        continue;
                    }                    
                    
                    
                    echo '<tr>';
                    
                    echo '<td>'.$row['id'].'</td>';
                    
                    echo '<td>'.$row['name'].'</td>';
                    
                    
                    $packid = $row['packid'];
                    $pquery = mysqli_query($conn, "SELECT name FROM `package` WHERE id=$packid;");                     
                    $packnamerow = mysqli_fetch_array($pquery);
                    echo '<td>'.$packnamerow['name'].'</td>';
                    
                    
                    $newStartDate = date("d-m-Y", strtotime($startdate));
                    $newEndDate = date("d-m-Y", strtotime($enddate));
                    
                    echo '<td>'.$newStartDate.'</td>';
                    echo '<td>'.$newEndDate.'</td>';
                    
                    if(!$isexpired){
                        echo '<td>'.$diff->format('%d').'</td>';
                    }
                    else{
                        echo '<td style="color:red;">Expired</td>';
                    }
                    if($reminderdate == ''){
                        //reminder not sent till date
                        

                        echo '<td>';                        
                        echo '<form method="post" action="dues.php">';
                        echo '<input type="hidden" value="'.$functionname.'" name="function">';                        
                        echo '<input type="hidden" value="'.$row['name'].'" name="name">';
                        echo '<input type="hidden" value="'.$row['mobile'].'" name="phno">';
                        echo '<input type="hidden" value="'.$row['id'].'" name="id">';    
                        echo '<input type="hidden" value="'.$today.'" name="today">';                

                        if($functionname == 'expired'){
                            echo '<input type="hidden" value="'.$newEndDate.'" name="date">';
                        }
                        else if($functionname == 'tobeexpired'){
                            echo '<input type="hidden" value="'.$days.'" name="days">';
                        }
                        echo '<input type="submit" name="sendmessage" value="SEND REMINDER">';
                        echo '</form>';
                        
                        echo '</td>';
                    }
                    else{
                        
                        $reminderdiff = date_diff(date_create(date("M j, Y")), date_create(date('M j, Y', strtotime($reminderdate))));
                        $reminderdays = $reminderdiff->format('%d');

                        if($reminderdays == 0){
                            //then dont show thw form
                            echo '<td style="color:green">Reminder Already Sent</td>';
                        }
                        else{
                            //show the form.
                        echo '<td>';
                        
                        echo '<form method="post" action="dues.php">';
                        echo '<input type="hidden" value="'.$functionname.'" name="function" >';
                        echo '<input type="hidden" value="'.$row['name'].'" name="name">';
                        echo '<input type="hidden" value="'.$row['mobile'].'" name="phno">';
                        echo '<input type="hidden" value="'.$row['id'].'" name="id">';
                        echo '<input type="hidden" value="'.$today.'" name="today">';                    

                        if($functionname == 'expired'){
                            echo '<input type="hidden" value="'.$newEndDate.'" name="date">';
                        }
                        else if($functionname == 'tobeexpired'){
                            echo '<input type="hidden" value="'.$days.'" name="days">';
                        }
                        echo '<input type="submit" name="sendmessage" value="SEND REMINDER">';
                        echo '</form>';
                        
                        echo '</td>';
                        }

                    }
                    echo "<td>";
                    echo '<form method="post" action="dues.php">';
                    echo '<input name="idno" value="'.$row['id'].'" type="hidden">';
                    echo '<input type="hidden" value="'.$row['name'].'" name="name">';
                    echo '<input type="hidden" value="'.$row['mobile'].'" name="phno">';
                    echo '<input type="date" name="today" required>';
                    echo '<input type="submit" name="recharge" value="RECHARGE">';
                    echo '</form>';
                    echo "</td>";

                    

                    // function tobeexpired($phno, $name, $days){
                    
                    
                        //     $phno = '91'.$phno;
                    //     sendsms($phno, 'Dear '.$name.', your gym membership is going to expire in '.$days.' day(s). For renewal please visit HALDER FITNESS CENTER, Birnagar - HALDER ENTERPRISES');
                    // }
                    
                    // function expired($phno, $name, $date){
                    //     $phno = '91'.$phno;
                    //     sendsms($phno, 'Dear '.$name.', your gym membership expired on '.$date.'. For renewal please visit HALDER FITNESS CENTER, Birnagar - HALDER ENTERPRISES');
                    // }
                    
                    // function tobeexpiredtoday($phno, $name){
                    //     $phno = '91'.$phno;
                    //     sendsms($phno, 'Dear '.$name.', your gym membership ends today. For renewal please visit HALDER FITNESS CENTER, Birnagar. - HALDER ENTERPRISES');
                    // }
                    
                    

                  
                    
                    
                    echo '</tr>';
                }
                echo '</table>';
                
            ?>
            
    </body>
</html>