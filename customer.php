<?php

$servername = "queue-mgmt-db.czx8mpocslrz.us-east-2.rds.amazonaws.com";
$username = "admin";
$password = "color2021$";
$dbname = "queue_mgmt_db";

/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
$link = mysqli_connect($servername, $username, $password ,$dbname);
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
 

    // Escape user inputs for security
    $fname = mysqli_real_escape_string($link, $_REQUEST['fname']);
    $phone = mysqli_real_escape_string($link, $_REQUEST['contact']);
    $lname = mysqli_real_escape_string($link, $_REQUEST['lname']);
    


// Extracting Business ID
if($fname!=NULL && $lname!=NULL && $phone!=NULL)
{






$date = new DateTime();

$date = date("Y-m-d H:i:s");



// Attempt insert query execution
$sql_insert_customer="insert into CUSTOMER(first_name,last_name,phone_no,created_by,updated_by,create_stp,update_stp) 
        values('$fname','$lname','$phone','Utkarsh','Harsh Garg','$date','$date')";
        
if (mysqli_query($link, $sql_insert_customer) === TRUE) 
{   
    echo ('<script>alert("Customer Registered")</script>');
    echo('<script>window.location.replace("index.html")</script>');  
}
}
else
{
    echo ('<script>alert("Customer not Registered successfully.Please try again")</script>');
    echo('<script>window.location.replace("index.html")</script>');
    
}




 
// Close connection
mysqli_close($link);
?>