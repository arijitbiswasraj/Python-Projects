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
        <script>
            function hidesecond(){
                var a = document.getElementById("second");
                var check = document.getElementById("couple");
                if(check.checked == true){
                    a.style.display = "block";
                }
                else{
                    a.style.display = "none";
                }
            }
        </script>
    </head>
    <body>
        <?php
        if ( isset($_POST['admit'])){
            if(!isset($_POST['couple'])){
                $studentname1 = mysqli_real_escape_string($conn, $_POST['studentname1']);
                $idno1 = mysqli_real_escape_string($conn, $_POST['idno1']);
                $address1 = mysqli_real_escape_string($conn, $_POST['address1']);
                $phno1 = mysqli_real_escape_string($conn, $_POST['phno1']);
                $mail1 = mysqli_real_escape_string($conn, $_POST['mail1']);
                $age1 = mysqli_real_escape_string($conn, $_POST['age1']);
                $gender1 = (mysqli_real_escape_string($conn, $_POST['gender1']) == "m")? 1:0;
                $emername1 = mysqli_real_escape_string($conn, $_POST['emername1']);
                $emerphno1 = mysqli_real_escape_string($conn, $_POST['emerphno1']);
                $father1 = mysqli_real_escape_string($conn, $_POST['father1']);
                $bday1 = mysqli_real_escape_string($conn, $_POST['bday1']);
                
                
                $authdoc1 = mysqli_real_escape_string($conn, $_POST['authdoc1']);
                $authdocno1 = mysqli_real_escape_string($conn, $_POST['authdocno1']);
                $healthproblem1 = mysqli_real_escape_string($conn, $_POST['healthproblem1']);
                $packid = mysqli_real_escape_string($conn, $_POST['package']);
                $startdate = $_POST['startdate'];
                
                $query1 	= mysqli_query($conn, "SELECT name, regfees, monthly, discount, disamount, duration FROM `package` WHERE id=$packid");
                $resultrow=mysqli_fetch_array($query1);

                $packname = $resultrow['name'];
                $regfees = $resultrow['regfees'];
                $monthly = $resultrow['monthly'];
                $discount = $resultrow['discount'];
                $disamount = $resultrow['disamount'];
                $duration = $resultrow['duration'];

                $enddate = date('Y-m-d', strtotime($startdate. ' + '.$duration.' days'));               
                $query 	= mysqli_query($conn, "INSERT INTO `user` (`id`, `name`, `address`, `profilepicfilename`, `mobile`, `mail`, `gender`, `emergencyname`, `emergencyno`, `fathername`, `authdoctype`, `authdocno`, `healthproblem`, `packid`, `packstartdate`, `packenddate`, `reminderdate`, `bday`) VALUES ('$idno1', '$studentname1', '$address1', 'photo', '$phno1', '$mail1', '$gender1', '$emername1', '$emerphno1', '$father1', '$authdoc1', '$authdocno1', '$healthproblem1', '$packid', '$startdate', '$enddate', 'NULL', '$bday1');");


                $amount = ($regfees + $monthly);
                $amount = $amount - (($discount  == 0)?$disamount:($discount*0.01*$amount));

                $year = $startdate.substr(0,4);
                $month = $startdate.substr(5,2);

                $query2 = mysqli_query($conn, "INSERT INTO `transaction` (`amount`, `date`, `duration`, `name`, `id`, `slno`, `year`, `month`, `packid`, `packname`) VALUES ('$amount', '$startdate', '$duration', '$studentname1', '$idno1', NULL, '$year', '$month', '$packid', '$packname');");

                if (!$query || !$query2) {
                    die ('SQL Error: ' . mysqli_error($conn));
                }
                else{
                    echo '<h4>Admission Sucessful. Student will receive an sms shortly.</h4>';
                    try{
                        welcome($phno1, $studentname1, $duration);
                        echo '<h4>SMS Sending Successful<h4>';
                    }
                    catch(Exception $e){
                        echo '<h4>Error in sending SMS. Please try again later.'.$e->getMessage().'</h4>';
                    }
                }
            }
            else{
                //couple admission

                $anniversary = mysqli_real_escape_string($conn, $_POST['anniversary']);
                
                //admitting first student
                $studentname1 = mysqli_real_escape_string($conn, $_POST['studentname1']);
                $idno1 = mysqli_real_escape_string($conn, $_POST['idno1']);
                $address1 = mysqli_real_escape_string($conn, $_POST['address1']);
                $phno1 = mysqli_real_escape_string($conn, $_POST['phno1']);
                $mail1 = mysqli_real_escape_string($conn, $_POST['mail1']);
                $age1 = mysqli_real_escape_string($conn, $_POST['age1']);
                $gender1 = (mysqli_real_escape_string($conn, $_POST['gender1']) == "m")? 1:0;
                $emername1 = mysqli_real_escape_string($conn, $_POST['emername1']);
                $emerphno1 = mysqli_real_escape_string($conn, $_POST['emerphno1']);
                $father1 = mysqli_real_escape_string($conn, $_POST['father1']);
                $bday1 = mysqli_real_escape_string($conn, $_POST['bday1']);
                
                
                $authdoc1 = mysqli_real_escape_string($conn, $_POST['authdoc1']);
                $authdocno1 = mysqli_real_escape_string($conn, $_POST['authdocno1']);
                $healthproblem1 = mysqli_real_escape_string($conn, $_POST['healthproblem1']);
                $packid = mysqli_real_escape_string($conn, $_POST['package']);
                $startdate = $_POST['startdate'];
                
                $query1 	= mysqli_query($conn, "SELECT duration FROM `package` WHERE id=$packid");
                $resultrow=mysqli_fetch_array($query1);

                $duration = $resultrow['duration'];

                $enddate = date('Y-m-d', strtotime($startdate. ' + '.$duration.' days'));               
                $query 	= mysqli_query($conn, "INSERT INTO `user` (`id`, `name`, `address`, `profilepicfilename`, `mobile`, `mail`, `gender`, `emergencyname`, `emergencyno`, `fathername`, `authdoctype`, `authdocno`, `healthproblem`, `packid`, `packstartdate`, `packenddate`, `reminderdate`, `bday`) VALUES ('$idno1', '$studentname1', '$address1', 'photo', '$phno1', '$mail1', '$gender1', '$emername1', '$emerphno1', '$father1', '$authdoc1', '$authdocno1', '$healthproblem1', '$packid', '$startdate', '$enddate', 'NULL', '$bday1');");
                if (!$query) {
                    die ('SQL Error: ' . mysqli_error($conn));
                }
                else{
                    echo '<h4>Admission Sucessful for '.$studentname1.'. Student will receive an sms shortly.</h4>';
                    try{
                        welcome($phno1, $studentname1, $duration);
                        echo '<h4>SMS Sending Successful<h4>';
                    }
                    catch(Exception $e){
                        echo '<h4>Error in sending SMS. Please try again later.'.$e->getMessage().'</h4>';
                    }
                }
                //admitting 2nd student
                $studentname2 = mysqli_real_escape_string($conn, $_POST['studentname2']);
                $idno2 = mysqli_real_escape_string($conn, $_POST['idno2']);
                $address2 = mysqli_real_escape_string($conn, $_POST['address2']);
                $phno2 = mysqli_real_escape_string($conn, $_POST['phno2']);
                $mail2 = mysqli_real_escape_string($conn, $_POST['mail2']);
                $age2 = mysqli_real_escape_string($conn, $_POST['age2']);
                $gender2 = (mysqli_real_escape_string($conn, $_POST['gender2']) == "m")? 1:0;
                $emername2 = mysqli_real_escape_string($conn, $_POST['emername2']);
                $emerphno2 = mysqli_real_escape_string($conn, $_POST['emerphno2']);
                $father2 = mysqli_real_escape_string($conn, $_POST['father2']);
                $bday2 = mysqli_real_escape_string($conn, $_POST['bday2']);
                
                $authdoc2 = mysqli_real_escape_string($conn, $_POST['authdoc2']);
                $authdocno2 = mysqli_real_escape_string($conn, $_POST['authdocno2']);
                $healthproblem2 = mysqli_real_escape_string($conn, $_POST['healthproblem2']);
                $packid = mysqli_real_escape_string($conn, $_POST['package']);
                $startdate = $_POST['startdate'];
                
                $query1 	= mysqli_query($conn, "SELECT name, regfees, monthly, discount, disamount, duration FROM `package` WHERE id=$packid");
                $resultrow=mysqli_fetch_array($query1);

                $packname = $resultrow['name'];
                $regfees = $resultrow['regfees'];
                $monthly = $resultrow['monthly'];
                $discount = $resultrow['discount'];
                $disamount = $resultrow['disamount'];
                $duration = $resultrow['duration'];

                $enddate = date('Y-m-d', strtotime($startdate. ' + '.$duration.' days'));               
                $query 	= mysqli_query($conn, "INSERT INTO `user` (`id`, `name`, `address`, `profilepicfilename`, `mobile`, `mail`, `gender`, `emergencyname`, `emergencyno`, `fathername`, `authdoctype`, `authdocno`, `healthproblem`, `packid`, `packstartdate`, `packenddate`, `reminderdate`, `bday`) VALUES ('$idno2', '$studentname2', '$address2', 'photo', '$phno2', '$mail2', '$gender2', '$emername2', '$emerphno2', '$father2', '$authdoc2', '$authdocno2', '$healthproblem2', '$packid', '$startdate', '$enddate', 'NULL', '$bday2');");

                $amount = ($regfees + $monthly);
                $amount = $amount - (($discount  == 0)?$disamount:($discount*0.01*$amount));

                $year = $startdate.substr(0,4);
                $month = $startdate.substr(5,2);

                $query2 = mysqli_query($conn, "INSERT INTO `transaction` (`amount`, `date`, `duration`, `name`, `id`, `slno`, `year`, `month`, `packid`, `packname`) VALUES ('$amount', '$startdate', '$duration', '$studentname1', '$idno1', NULL, '$year', '$month', '$packid', '$packname');");

                if (!$query || !$query2) {
                    die ('SQL Error: ' . mysqli_error($conn));
                }
                else{
                    try{
                        welcome($phno2, $studentname2, $duration);
                        echo '<h4>SMS Sending Successful<h4>';
                    }
                    catch(Exception $e){
                        echo '<h4>Error in sending SMS. Please try again later.'.$e->getMessage().'</h4>';
                    }
                    echo '<h4>Admission Sucessful for'.$studentname2.'. Student will receive an sms shortly.</h4>';
                }
                //INSERT INTO `couple` (`slno`, `id1`, `id2`) VALUES (NULL, '195', '0');
                $query 	= mysqli_query($conn, "INSERT INTO `couple` (`slno`, `id1`, `id2`, `anniversary`, `name1`, `name2`) VALUES (NULL, '$idno1', '$idno2', '$anniversary', '$studentname1', '$studentname2');");
                if (!$query) {
                    die ('SQL Error: ' . mysqli_error($conn));
                }
            }
        }
        ?>

        <h1>New Admission</h1>
        <form action="admission.php" method="post">
            <input type="checkbox" id="couple" name="couple" value="couple" onchange="hidesecond()"> 
            <label >Couple Admission</label><br>

            <!--1st student-->
            <div id="first">
                <label >Name: </label>
                <input type="text" name="studentname1" placeholder="Name" required><br>

                <label >Id Number: </label>
                <input type="number" min="1" name="idno1" required><br>

                <label >Address</label>
                <textarea  name="address1" required></textarea><br>

                <label >Phone Number(10 digits): </label>
                <input type="number" name="phno1" required><br>

                <label >Email ID:</label>
                <input type="text" name="mail1" placeholder="someone@google.com" required><br>

                <label >Age:</label>
                <input type="number" name="age1" required><br>

                <label >Gender</label><br>
                
                <input type="radio" name="gender1" value="m" required>
                <label >Male</label><br>

                <input type="radio" name="gender1" value="f" required>
                <label >Female</label><br>

                <label>Emergency contact name:</label>
                <input id="text" name="emername1" required>

                <label>Emergency Contact No.(10 digits)</label>
                <input type="number" name="emerphno1" required>

                <label>Father's name:</label>
                <input type="text" name="father1" required>

                <label>Birthday:</label>
                <input type="date" name="bday1" required>

                <label>KYC Document Type:</label>
                <select name="authdoc1" required>
                    <option selected value="1">Aadhar Card</option>
                    <option value="2">Voter Card</option>
                    <option value="3">Driving License</option>
                </select>
                <label>KYC Document Number</label>
                <input type="text" name="authdocno1" required>

                <label>Health Problem:(Write "NO" if no problem)</label>
                <input type="text" name="healthproblem1" required>


                
            </div>
            <!--2nd student-->
            <div id="second" style="display:none;">
                <label >Name: </label>
                <input type="text" name="studentname2" placeholder="Name" required><br>

                <label >Id Number: </label>
                <input type="number" min="1" name="idno2" required><br>

                <label >Address</label>
                <textarea  name="address2" required></textarea><br>

                <label >Phone Number(10 digits): </label>
                <input type="number" name="phno2" required><br>

                <label >Email ID:</label>
                <input type="text" name="mail2" placeholder="someone@google.com" required><br>

                <label >Age:</label>
                <input type="number" name="age2" required><br>

                <label >Gender</label><br>
                
                <input id="genderm" type="radio" name="gender2" value="m" required>
                <label >Male</label><br>

                <input id="genderf" type="radio" name="gender2" value="f" required>
                <label >Female</label><br>

                <label>Emergency contact name:</label>
                <input id="text" name="emername2" required>

                <label>Emergency Contact No.(10 digits)</label>
                <input type="number" name="emerphno2" required>

                <label>Father's name:</label>
                <input type="text" name="father2" required>

                <label>Birthday:</label>
                <input type="date" name="bday2" required>

                <label>Anniversary</label>
                <input type="date" name="anniversary" required>

                <label>KYC Document Type:</label>
                <select name="authdoc2" required>
                    <option selected value="1">Aadhar Card</option>
                    <option value="2">Voter Card</option>
                    <option value="3">Driving License</option>
                </select>
                <label>KYC Document Number</label>
                <input type="text" name="authdocno2" required>

                <label>Health Problem:(Write "NO" if no problem)</label>
                <input type="text" name="healthproblem2" required>

                
            </div>
            <label>Package:</label>
            <?php               
                    $query 	= mysqli_query($conn, "SELECT name, id FROM `package`");                                       
                    echo '<select name="package"  required>';
                    while($row_res=mysqli_fetch_array($query)){
                         echo '<option value="'.$row_res['id'].'">'.$row_res['name'].'</option>';
                    }
                    echo '</select>';
            ?>
            <label> Starting Date</label>
            <input type="date" name="startdate"  required>
            <input type="submit" name="admit" required>    
        </form>
    </body>
</html>