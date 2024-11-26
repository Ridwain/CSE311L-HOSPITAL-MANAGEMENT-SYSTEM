<?php
include 'dbcon.php';
session_start();
if (isset($_POST['submit'])) {
    $Full_name = $_POST['full_name'];
    $Email = $_POST['email'];
    $Phone_Number = $_POST['phone_number'];
    $Birth_Date = $_POST['b_date'];
    $Gender = $_POST['gender'];
    $Address = $_POST['address'];
    $Specialities = $_POST['Specialities'];
    $UserType = $_POST['user_type'];
    $Password = $_POST['password'];
    $enc_Password = password_hash($Password, PASSWORD_DEFAULT);


    $checkNumber = "SELECT * FROM Users WHERE Phone_Number='$Phone_Number' ";

    $result = $connection->query($checkNumber);

    if ($result->num_rows > 0) {
        $_SESSION['status'] = "Mobile Number Already Exists";
        $_SESSION['status_code'] = "error";
        header("location:adminPortal.php");
    } else {
        $connection->begin_transaction(); // Start a transaction
        $insertQuery1 = "INSERT INTO `Users` (`ID`, `Phone_Number`, `Password`, `UserType`) 
                 VALUES (NULL, '$Phone_Number', '$enc_Password', '$UserType')";


        if ($connection->query($insertQuery1) === TRUE) {
            $user_id = $connection->insert_id;

            $insertQuery2 = "INSERT INTO `Doctor` (`ID`, `Full_name`, `Email`, `Birth_date`, `Gender`, `Address`, `Specialities`, `User_id`) 
                                VALUES (NULL, '$Full_name', '$Email', '$Birth_Date', '$Gender', '$Address', '$Specialities', '$user_id')";


            if ($connection->query($insertQuery2) === TRUE) {
                $connection->commit();
                $_SESSION['status'] = "Registered Successfully";
                $_SESSION['status_code'] = "success";
                header("location: adminPortal.php");
            } else {
                $connection->rollback();
                $_SESSION['status'] = "Data not inserted!";
                $_SESSION['status_code'] = "error";
            }
        } else {
            $connection->rollback();
            echo "Error: " . $connection->error;
        }
    }
}
