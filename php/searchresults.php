<!DOCTYPE html>
<html>
<head>
    <style>
        .table {
        border: 1;
        width: 100%;
        color: #588c7e;
        font-family: monospace;
        font-size: 18px;
        text-align: left;
        max-width:100%;
        }
        th {
        background-color: #588c7e;
        color: white;
        }
          tr:nth-child(even) {background-color: #f2f2f2}
    </style>
    </head>
<body>

<?php

include 'config.php';

/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
$conn = mysqli_connect($servername, $username, $password ,$dbname);
 
// Check connection
if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
 
// Escape user inputs for security
$businessType = mysqli_real_escape_string($conn, $_REQUEST['businessType']);
$location = mysqli_real_escape_string($conn, $_REQUEST['location']);

// Retrieve Data for businesses
$sql = "SELECT business_id, business_name, address_line1, address_line2, city_id, state_name, phone_no, zipcode, business_type_id FROM BUSINESS 
        LEFT JOIN STATE ON STATE.state_id = BUSINESS.state_id
        WHERE city_id = '$location' 
        AND business_type_id ='$businessType'";

//Execute the query
$result = $conn->query($sql);


if (mysqli_num_rows($result) > 0) {

// Generate the header of the table
echo " <div align=\"right\"><input type=\"button\" name=\"close\" onclick=\"closeSearchResults()\" value=\"Close\"></div>
        <table><thead> 
              <tr>
                <th>Business Name</th>
                <th>Business Address</th>
                <th>Phone Number</th>
                <th>Services Offered</th>
                <th>Estimated Wait Time (HH:MM:SS)</th>
                <th>Check-In</th>
              </tr></thead>";

   // Loop through the result set
    while($row = mysqli_fetch_assoc($result)) {
        
        // Retrieve Data for businesses
        $businessId = $row["business_id"];
        $sql_services = "SELECT service_id, service_name FROM SERVICES WHERE service_id IN (SELECT service_id FROM BUSINESS_SERVICE_RLT where business_id = '$businessId')";
        
        // Find all the services for each business
        $result_services = $conn->query($sql_services);
        $services_list= "<ul>";
        // Iterate the list of services and create a comma seperated list of services
        while($row_services = mysqli_fetch_assoc($result_services)) {
            $services_list .= "<li>";
            $services_list .= $row_services["service_name"]. "</li>";
        }
        $services_list .= "</ul>";
        
        //Find wait time for all the businesses
        $sql_customer_wait_time = "SELECT SEC_TO_TIME( SUM( TIME_TO_SEC( SERVICES.service_time) ) ) AS CUSTOMER_WAIT_TIME
                FROM BUSINESS_SERVICE_RLT, SERVICES , CUSTOMER_SERVICE_RLT 
                WHERE
                BUSINESS_SERVICE_RLT.business_id  ='$businessId'
                AND BUSINESS_SERVICE_RLT.service_id = SERVICES .service_id
                AND CUSTOMER_SERVICE_RLT.status_id  =  1
                AND BUSINESS_SERVICE_RLT.business_service_rlt_id = CUSTOMER_SERVICE_RLT.business_service_rlt_id ;";
        
        $result_customer_wait_time = $conn->query($sql_customer_wait_time);
        $customer_wait_time;
        while($row_customer_wait_time = mysqli_fetch_assoc($result_customer_wait_time)) {
            $customer_wait_time = $row_customer_wait_time["CUSTOMER_WAIT_TIME"];
        }
        if ($customer_wait_time == null){$customer_wait_time ="No Wait Time";}
        
        //Paint HTML
        echo "<tr> <td> " . $row["business_name"]. 
            "</td> <td> " . $row["address_line1"]. " <BR> " . $row["address_line2"]. " <BR>" . $row["zipcode"]. " <BR>" . $row["state_name"]. 
            "</td> <td>"  . $row["phone_no"].
            "</td> <td>"  . $services_list.
            "</td> <td>"  . $customer_wait_time.
            "</td> <td align =\"center\"><button id=\"checkIn\" onclick=\"showCheckIn('" . $row["business_id"]. "','" . $row["business_name"]. "','" . $row["business_type_id"]."')\" >Check-In</button></td>";
        }
      } else {
         //echo "<tr> Sorry, No Results found for your Search !! </tr>" 
        echo "no results found";
      }
 
// Close connection
mysqli_close($conn);
?>

   
</body>
</html>