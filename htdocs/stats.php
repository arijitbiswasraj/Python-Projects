<!--//INSERT INTO `transaction` (`amount`, `date`, `duration`, `name`, `id`, `slno`, `year`, `month`, `packid`, `packname`) VALUES ('700', '2021-10-01', '30', 'arijit', '195', NULL, '2021', '10', '7', 'REGULAR');-->

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

    </head>
    <body>
        <h1>Analytics</h1>
        <table>
            <?php
                
                //SELECT count(id) FROM `user`;
                $query 	= mysqli_query($conn, "SELECT count(id) as users FROM `user`;");
                if (!$query) {
                    die ('SQL Error: ' . mysqli_error($conn));
                }
                $row = mysqli_fetch_array($query);               
                $users = $row['users'];
                echo '<tr>';
                echo '<td>Total number of active users</td>';
                echo '<td>'.$users.'</td>';
                echo '</tr>';

                //SELECT sum(amount) as total FROM `transaction` WHERE month=10 && year=2021;
                $month = date('m');
                $year='20'.date('y');
                $query 	= mysqli_query($conn, "SELECT sum(amount) as total FROM `transaction` WHERE month=$month && year=$year;");
                if (!$query) {
                    die ('SQL Error: ' . mysqli_error($conn));
                }
                $row = mysqli_fetch_array($query);         
                $amount = $row['total'];
                echo '<tr>';
                echo '<td>Total earning in month '.$month.': </td>';
                echo '<td>'.$amount.'</td>';
                echo '</tr>';

                //SELECT sum(amount) as total FROM `transaction` WHERE year=2021;
                $query 	= mysqli_query($conn, "SELECT sum(amount) as total FROM `transaction` WHERE year=$year;");
                if (!$query) {
                    die ('SQL Error: ' . mysqli_error($conn));
                }
                $row = mysqli_fetch_array($query);         
                $amount = $row['total'];
                echo '<tr>';
                echo '<td>Total earning in month '.$month.': </td>';
                echo '<td>'.$amount.'</td>';
                echo '</tr>';

                //SELECT count(slno) as total FROM `couple`;
                $query 	= mysqli_query($conn, "SELECT count(slno) as total FROM `couple`;");
                if (!$query) {
                    die ('SQL Error: ' . mysqli_error($conn));
                }
                $row = mysqli_fetch_array($query);         
                $users = $row['total'];
                echo '<tr>';
                echo '<td>Total number of couples: </td>';
                echo '<td>'.$users.'</td>';
                echo '</tr>';                
            ?>

        </table>
        <table>
            <?php
            //last 30 transactions
            //select * from transaction order by slno DESC LIMIT 30;
            $query 	= mysqli_query($conn, "select * from transaction order by slno DESC LIMIT 30;");
            if (!$query) {
                die ('SQL Error: ' . mysqli_error($conn));
            }
            
            echo '<tr>';
            echo '<th>Name: </th>';
            echo '<th>ID: </th>';
            echo '<th>Duration: </th>';
            echo '<th>Pack Name: </th>';
            echo '<th>Date: </th>';
            echo '<th>Amount: </th>';
            echo '</tr>';
            while($row = mysqli_fetch_array($query)){
                echo '<tr>';
                echo '<td>'.$row['name'].'</td>';
                echo '<td>'.$row['id'].'</td>';
                echo '<td>'.$row['duration'].'</td>';
                echo '<td>'.$row['packname'].'</td>';
                $newDate = date("d-m-Y", strtotime($row['date']));
                echo '<td>'.$newDate.'</td>';
                echo '<td>'.$row['amount'].'</td>';
                echo '</tr>';
            }            
            
            ?>
        </table>
    </body>
</html>