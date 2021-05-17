<!DOCTYPE html>
<html>
<head>
    <style>
        table {
        border: 1;
        width: 100%;
        color: #588c7e;
        font-family: monospace;
        font-size: 25px;
        text-align: left;
        }
        th {
        background-color: #588c7e;
        color: white;
        }
          tr:nth-child(even) {background-color: #f2f2f2}
    </style>
    </head>
<body>
<form method="post" action="">
        <label for="fname">First name:</label>
        <input type="text" id="fname" name="fname"><br><br>
        <label for="lname">Last name:</label>
        <input type="text" id="lname" name="lname"><br><br>
        <label for="contact">Contact Number:</label>
        <input type="tel" id="contact" name="contact"><br><br>

        <div class="wrap-input100 validate-input" id="size">
        </div>

<?php

include 'config.php';

/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
$conn = mysqli_connect($servername, $username, $password ,$dbname);
 
// Check connection
if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

$business_Type = mysqli_real_escape_string($conn, $_REQUEST['business_type_id']);
$business_id = mysqli_real_escape_string($conn, $_REQUEST['business_id']);
$business_name = mysqli_real_escape_string($conn, $_REQUEST['business_name']);

$date = new DateTime();

$date = date("Y-m-d H:i:s");
// paint dropdown in html
if ($business_Type == '1' || $business_Type == '2') { ?>
<select name="services" id="services">
<option disabled selected value>Service</option>
<option value="1">New Order</option>
<option value="2" >Pick UP</option>
</select>
<?php 
} 
else if ($business_Type == '3') { ?>
    <select name="services" id="services">
        <option disabled selected value>Service</option>
        <option value="3" >New Patient</option>
        <option value="4" >Re-Occuring Patient</option>
        <option value="6" >Lab Report Consultation</option>
        <option value="5" >Covid Shot</option>
        </select>
<?php 
}
 
// You are checkin in to business_name with a wait time of this

// select query to find all the business_service_rlt id for the business id above
if(isset($_POST['button']))
{  
    $sevice_id=$_POST['services'];
$sql_select_business_Service_rlt = "Select business_service_rlt_id from BUSINESS_SERVICE_RLT WHERE business_id='$business_id' and service_id='$sevice_id'";
$query = $conn->query($sql_select_business_Service_rlt);
while($result = mysqli_fetch_assoc($query)) {
    $business_service_rlt_id = $result["business_service_rlt_id"];
}


// Please select the services you want to checkin dropdown

// Name, phone number text box
// check in button
// on click of this button

    $fname=$_POST['fname'];
    $lname=$_POST['lname'];
    $phone=$_POST['contact'];
    
$sql_check_customer="Select customer_id from CUSTOMER where phone_no='$phone'";
$query1 = $conn->query($sql_check_customer);
while($result1 = mysqli_fetch_assoc($query1)) 
{
    $customer_id = $result1["customer_id"];
    if(mysqli_num_rows($query1)>0)
    {
        $sql_insert_business_servicerlt="insert into CUSTOMER_SERVICE_RLT(status_id,customer_id,business_service_rlt_id,created_by,updated_by,create_stp,update_stp)
        values(1,'$customer_id','$business_service_rlt_id','Utkarsh','Harsh Garg','$date','$date')";
        mysqli_query($conn, $sql_insert_business_servicerlt);
        echo ('<script>alert("Inserted Customer into Customer Service RLT") </script>');
    }
}
    if(mysqli_num_rows($query1)<=0)
    {
        $sql_insert_customer="insert into CUSTOMER(first_name,last_name,phone_no,created_by,updated_by,create_stp,update_stp) 
        values('$fname','$lname','$phone','Utkarsh','Harsh Garg','$date','$date')";
        mysqli_query($conn, $sql_insert_customer);
       
        
        //select customer_id from customer_id

        $sql_check_customerid="Select customer_id from CUSTOMER where phone_no='$phone'";
        $query2 = $conn->query($sql_check_customerid);
        while($result2 = mysqli_fetch_assoc($query2)) 
            {
                $new_customer_id = $result2["customer_id"];
            }

            // insert into Customer_service_rlt new value

            $sql_insert_business_servicerlt="insert into CUSTOMER_SERVICE_RLT(status_id,customer_id,business_service_rlt_id,created_by,updated_by,create_stp,update_stp)
            values(1,'$new_customer_id','$business_service_rlt_id','Utkarsh','Harsh Garg','$date','$date')";
            mysqli_query($conn, $sql_insert_business_servicerlt);
            echo ('<script>alert("New Customer Added into Customer Table \n Inserted new Customer into Customer Service RLT")</script>');
    }

}
 



// call a javascipt execute a php code to insert into customer_service_rlt

 
// Close connection
mysqli_close($conn);
?>
    <br>
    <input type="submit" name="button" value="Submit">
     <br>
    </form>
  
</body>
</html>