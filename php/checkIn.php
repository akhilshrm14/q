<!DOCTYPE html>
<html>
<head>
    
    <style>
@import url('https://fonts.googleapis.com/css?family=Fjalla+One&display=swap');

*
{
  margin: 0;
  padding: 0;
}
body
{
  background: url("https://s3-us-west-2.amazonaws.com/s.cdpn.io/38816/image-from-rawpixel-id-2210775-jpeg.jpg") center center no-repeat;
  background-size: cover;
  width: 100vw;
  height: 100vh;
  display: grid;
  align-items: center;
  justify-items: center;
}
.contact-us
{
  background: #f8f4e5;
  padding: 50px 100px;
  border: 2px solid rgba(0,0,0,1);
  box-shadow: 15px 15px 1px #ffa580, 15px 15px 1px 2px rgba(0,0,0,1);
}
input
{
  display: block;
  width: 100%;
  font-size: 14pt;
  line-height: 14pt * 2;
  font-family: 'Fjalla One';
  margin-bottom: 14pt * 2;
  border: none;
  border-bottom: 5px solid rgba(0,0,0,1);
  background: #f8f4e5;
  min-width: 250px;
  padding-left: 5px;
  outline: none;
  color: rgba(0,0,0,1)
}
select
{
    display: block;
  width: 100%;
  font-size: 14pt;
  line-height: 14pt * 2;
  font-family: 'Fjalla One';
  margin-bottom: 14pt * 2;
  border: none;
  border-bottom: 5px solid rgba(0,0,0,1);
  background: #f8f4e5;
  min-width: 250px;
  padding-left: 5px;
  outline: none;
  color: rgba(0,0,0,1)
}
input:focus
{
  border-bottom: 5px solid #ffa580;
}
button
{
  display: block;
  margin: 0 auto;
  line-height: 14pt * 2;
  padding: 0 20px;
  background: #ffa580;
  letter-spacing: 2px;
  transition: .2s all ease-in-out;
  outline: none;
  border: 1px solid rgba(0,0,0,1);
  box-shadow: 3px 3px 1px #95a4ff, 3px 3px 1px 1px rgba(0,0,0,1);
}
  button:hover
  {
    background: rgba(0,0,0,1);
    color: white;
    border: 1px solid rgba(0,0,0,1);
  }
::selection 
{
  background: #ffc8ff;
}
input:-webkit-autofill,
input:-webkit-autofill:hover, 
input:-webkit-autofill:focus
{
  border-bottom: 5px solid #95a4ff;
  -webkit-text-fill-color: #2A293E;
  -webkit-box-shadow: 0 0 0px 1000px #f8f4e5 inset;
            box-shadow:0 0 0px 1000px #f8f4e5 inset;
  transition: background-color 5000s ease-in-out 0s;
}
    </style>
        
        <script type="text/javascript">
        // This function will take input of search string and location. It will then call a php page to call MYSQL DB to fetch the records
        function showSearchResults() {

            var location = document.getElementById('location').value;
            var businessType = document.getElementById('businessType').value;

            if (location == null || businessType == null || businessType.length == 0 || location.length == 0) {
                document.getElementById("overlay").innerHTML = "Ooops !! Please select a valid location or Business";
                document.getElementById("overlay").style.display = "block";
                return;
            } else {
                document.getElementById('loadingmessage').style.zIndex = '3';
                document.getElementById("loadingmessage").style.display = "block";
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("overlay").innerHTML = this.responseText;
                        document.getElementById("overlay").style.display = "block";
                        document.getElementById("loadingmessage").style.display = "none";
                    }
                };
                xmlhttp.open("GET", "php/searchresults.php?businessType=" + businessType + "&location=" + location, true);
                xmlhttp.send();
            }
        }
        </script>

</head>
<body>

<form class="contact-us" method="post" action="">
        <label><h1>CheckIn Form</h1></label>
        <hr>
        <br>
        <input type="text" id="fname" name="fname" placeholder="First Name"><br><br>
        <input type="text" id="lname" name="lname" placeholder="Last Name"><br><br>
        <input type="tel" id="contact" name="contact" placeholder="Contact Number"><br><br>

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
$location = mysqli_real_escape_string($conn, $_REQUEST['location']);
$date = new DateTime();

$date = date("Y-m-d H:i:s");
// paint dropdown in html
if ($business_Type == '1' || $business_Type == '2') { ?>
<select name="services" id="services">
<option disabled selected value>Services</option>
<option value="1">New Order</option>
<option value="2" >Pick UP</option>
</select>
<?php 
} 
else if ($business_Type == '3') { ?>
    <select name="services" id="services">
        <option disabled selected value>Services</option>
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
if($fname!=NULL && $lname!=NULL && $phone!=NULL)
{
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
            echo('<script type="text/javascript">showSearchResults();</script>');
    }

    }
    else
    {
        echo ('<script>alert("\nFill The Form")</script>');
    }
}



// call a javascipt execute a php code to insert into customer_service_rlt

 
// Close connection
mysqli_close($conn);
?>
    <br>
    <button input type="submit" name="button" value="Submit">SUBMIT
     <br>
    </button>
    </form>
  
</body>
</html>