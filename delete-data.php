
<?php
    $con = mysqli_connect("localhost", "root", "", "brijesh") or die("Connection Failed");

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $sql = "DELETE FROM `insert-data` WHERE `id`=".$id;

        if ($res = mysqli_query($con, $sql)) {
            header("location:view-data.php");
        }
    }
 ?>