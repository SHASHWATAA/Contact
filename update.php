<?php 

if (isset($_GET["Name_ID"])) {
    $Name_ID = $_GET["Name_ID"];
    $servername = "localhost";
    $username = "thetrian";
    $password = "-Xa%mcG_sE*!";
    $dbname = "thetrian_Contact";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password,$dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
    
    $DateToday = new DateTime('today');
    
    $DateToday = $DateToday->format('Y-m-d');

    
    $sql = "UPDATE `Main` SET `Last contact date` = '".$DateToday."' WHERE `Main`.`Name_ID` = ".$Name_ID;
    
    $result = mysqli_query($conn, $sql);
    
    header("Location: chart.html");
    exit();
    
    
    
    
} else {
    echo "Incorrect Parameters Supplied";
}

?>