<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
date_default_timezone_set("Australia/Sydney");




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
// echo "Connected successfully";

$MainTableData = GetTableData($conn,"Main");

// print_r($MainTableData);

$Contact = [];

foreach ($MainTableData as $ContactData){
    
    $temp=[];
    
    $Name_ID = $ContactData['Name_ID'];
    $Priority_ID = $ContactData['Priority_ID'];
    
    $Name = GetTableDataFromPK($conn, "Names", "Name_ID", $Name_ID, ["Names"])[0];
    
    $Priority = GetTableDataFromPK($conn, "Priority", "Priority_ID", $Priority_ID, ["Days","Readable Days"]);
    
    $ContactData["Last contact date"];
    
    $LastContactDate = new DateTime($ContactData["Last contact date"]);
    $dateDiff = ($LastContactDate->diff(new DateTime('today')));
    
    $daysDiff = $dateDiff->format('%r%a');
    $temp['Name'] = $Name;
    $temp['Contact in'] = $Priority[1];
    $temp['Contact in(days)'] = $Priority[0];
    $temp['Last Contact Date'] = $ContactData["Last contact date"];
    $temp['Name_ID'] = $Name_ID;
    $temp['Priority_ID'] = $Priority_ID;
    
    
    if(($daysDiff - $Priority[0]) >= 0){
        $temp['Contact due for(days)'] = $daysDiff - $Priority[0];
    }else{
        $temp['Contact due in(days)'] = ($daysDiff - $Priority[0])*-1; 
    }
    
    
    if($daysDiff>=$Priority[0]){
        $Contact['to_contact'][]= $temp;
    }else{
        $Contact['contact_later'][]= $temp;
    }
    
}

echo json_encode($Contact,JSON_PRETTY_PRINT);


function GetTableData($connectionObject, $tableName){
    $sql = "select * from `".$tableName."`";
    $result = mysqli_query($connectionObject, $sql);
    
    $rows = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    
    return $rows;
}

function GetTableDataFromPK($connectionObject, $tableName, $PK, $PK_Val, $Column_Vals){
    $sql = "SELECT * FROM `".$tableName."` WHERE ".$PK." = ".$PK_Val;
    $result = mysqli_query($connectionObject, $sql);
    $val =[];
    while ($row = mysqli_fetch_assoc($result)) {
        foreach ($Column_Vals as $Column_Val){
            
            $val[] = $row[$Column_Val];
        }
    }

    return $val;
}
?>