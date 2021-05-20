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
$business_name = mysqli_real_escape_string($link, $_REQUEST['name']);
$phone_number = mysqli_real_escape_string($link, $_REQUEST['contact']);
$address_line1 = mysqli_real_escape_string($link, $_REQUEST['line1']);
$address_line2 = mysqli_real_escape_string($link, $_REQUEST['line2']);
$business_type= mysqli_real_escape_string($link, $_REQUEST['service']);
$city_id= mysqli_real_escape_string($link, $_REQUEST['city']);
$state_id= mysqli_real_escape_string($link, $_REQUEST['state']);
$zip= mysqli_real_escape_string($link, $_REQUEST['zip']);
$country= mysqli_real_escape_string($link, $_REQUEST['country']);





$date = new DateTime();

$date = date("Y-m-d H:i:s");



// Attempt insert query execution
$sql_insert_business = "insert into BUSINESS(business_name,phone_no,address_line1,address_line2,business_type_id,city_id,state_id,zipcode,country_id,created_by,create_stp,update_stp)
values('$business_name','$phone_number','$address_line1','$address_line2','$business_type','$city_id','$state_id','$zip','$country','Harsh Garg','$date','$date')";

$checkbox=$_POST['new'];


// Extracting Business ID
if ($link->query($sql_insert_business) === TRUE) {
    $business_id = $link->insert_id;
    echo ('<script>alert("New record created successfully.")</script>');
    foreach($checkbox as $service_id)  
   {  
      
      $sql_business_rlt="insert into BUSINESS_SERVICE_RLT(service_id,business_id,created_by,create_stp,update_stp)
      values('$service_id','$business_id','Harsh Garg','$date','$date');"; 
      mysqli_query($link, $sql_business_rlt);
   } 
   echo ('<script>alert(Records added successfully.to Service table")</script>');
   echo('<script>window.location.replace("index.html")</script>');
}
else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}




 
// Close connection
mysqli_close($link);
?>