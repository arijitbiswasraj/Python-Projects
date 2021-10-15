<?php
include('dbcon.php');
session_start(); 
if(!isset($_SESSION['user_hfcgym'])){
  header('location:index.php');
}
?>
<?php


if(isset($_POST["createpack"])){
    //INSERT INTO `package` (`id`, `name`, `regfees`, `monthly`, `discount`, `disamount`, `duration`) VALUES (NULL, 'dsds', '121', '121', '121', '121', '121');
    $packname = $_POST['packname'];
    $regfees = $_POST['regfees'];
    $monthly= $_POST['monthly'];
    $discount = $_POST['discount'];
    $disamount = $_POST['disamount'];
    $duration = $_POST['duration'];
    $query 	= mysqli_query($conn, "INSERT INTO `package` (`id`, `name`, `regfees`, `monthly`, `discount`, `disamount`, `duration`) VALUES (NULL, '$packname', '$regfees', '$monthly', '$discount', '$disamount', '$duration');");

    if (!$query) {
        die ('SQL Error: ' . mysqli_error($conn));
    }
    else{
        echo '<h4>Successful Addition of new Package.</h4>';
    }
}
?>
<!DOCTYPE html>
<html>

    <head>

    </head>
    <body>
        <h3>List of Packages present</h3>
        <table>
            <tr>
                <th>Package Name</th>
                <th>Edit</th>
            </tr>
            <?php
                //SELECT * FROM `package`
                $query 	= mysqli_query($conn, "SELECT * FROM `package`;");
                while($row = mysqli_fetch_array($query)){
                    echo '<tr>';
                    echo '<td>'.$row['name'].'</td>';
                    

                    echo '<td>';
                    echo '<form method="post" action="editpack.php">';
                    echo '<input type="hidden" name="id" value="'.$row['id'].'">';
                    echo '<input type="submit" name="editpack" value="EDIT PACK">';
                    echo '</form>';
                    echo '</td>';
                    echo '</tr>';
                }
            
            ?>
        </table>
        <h3>Create a new package </h3>
        <form method="post" action="packs.php">
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
            <input type="submit" name="createpack" value="Create Pack">            
        </form>
                

    </body>
</html>