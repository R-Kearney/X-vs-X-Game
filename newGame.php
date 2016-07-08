<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "xGame";

// Create connection
$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// Create database
$sql = "DROP DATABASE xGame";
$conn->query($sql);

$conn->close();

// Create connection
$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// Create database
$sql = "CREATE DATABASE xGame";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}

$conn->close();

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
} 

// sql to create table
$sql = "CREATE TABLE Moves (
Row INT(6) NOT NULL,
Col INT(6) NOT NULL,
Clicked VARCHAR(2) NOT NULL,
Player INT(6) NULL
)";
$conn->query($sql);

for ($k = 0; $k < 3; $k++){
		for ($i = 0; $i < 3; $i++){
			$sql = "INSERT INTO Moves (Row, Col, Clicked)
			VALUES ('$k', '$i', 'O' )";
			$conn->query($sql);
		}
}
$sql = "INSERT INTO Moves (Row, Col, Clicked, Player)
			VALUES ('3', '3', 'O', '1' )";
			$conn->query($sql);
			
header('Refresh: 0;url=/server/game.php');

$conn->close();
?>
