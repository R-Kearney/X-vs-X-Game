<html>
<head>
<style>
table {
	 min-width: 200px;
	 min-height: 200px;
	 padding: 5px;
	 border: 2px;
	 border-radius: 3px;
	 border-style: solid;
 }
 th, td{
	 min-width: 20px;
	 min-height: 20px;
	 padding: 20px;
	 border: 2px;
	 border-radius: 3px;
	 border-style: solid;
 }
 body {
	 padding: 30px;
 }
 .button {
	color: white;
	border-radius: 4px;
	text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
	background: rgb(28, 184, 65); /* this is green */
	border-color: rgb(28, 184, 65);
    border-style: none;
    height: 40px;
    width: 150px;
    font-size: 16px;
    font-weight: 500;
}

</style>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "xGame";
$row0 = array(0,0,0);
$row1 = array(0,0,0);
$row2 = array(0,0,0);
$gameOver = 0; 
$boxes = 9; // default
$player = 1;


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 


// Update Database
if(isset($_GET["row"])) {
	if(isset($_GET["col"])) {
		$row = $_GET["row"];
		$col = $_GET["col"];
		$sql = "UPDATE `moves` SET Clicked='X' WHERE Row=$row AND Col=$col";
		$conn->query($sql);
		// Control Players
		if(isset($_GET["player"])) {
			$player = $_GET["player"];
			$lastPlayer = $player;
			if ($player == 1){
				$sql = "UPDATE `moves` SET Player='2' WHERE Row=3 AND Col=3";
			} else {
				$sql = "UPDATE `moves` SET Player='1' WHERE Row=3 AND Col=3";
			}
			$conn->query($sql);
		}
	}
}
$conn->close();
?>
</head>
<body>
<table>
<?php
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// Check for a win
for ($k = 0; $k < 3; $k++){
	echo("<tr>");
	for ($i = 0; $i < 3; $i++){
		$sql = "SELECT * FROM `moves` WHERE Row=$k AND Col=$i";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			while ($tableData = $result->fetch_assoc()){
				if ($tableData["Clicked"] == "X"){
					${"row" . $k}[$i] = 1;
				}
			}
		}
	}
}
// Check for 3 Across
for ($k = 0; $k < 3; $k++){
	if(${"row" . $k}[0] == 1 && ${"row" . $k}[1] == 1 && ${"row" . $k}[2] == 1){
		$gameOver = 1;
	}
}
// Check for 3 Down
for ($i = 0; $i < 3; $i++){ 
	if($row0[$i] == 1 && $row1[$i] == 1 && $row2[$i] == 1){
		$gameOver = 1;
	}
}
// Check for Diagonal Right -> Left
if($row0[2] == 1 && $row2[0] == 1 && $row1[1] == 1){
	$gameOver = 1;
}
// Check for Diagonal Left -> Right
if($row0[0] == 1 && $row2[2] == 1 && $row1[1] == 1){
	$gameOver = 1;
}
// Gets next player
$sql = "SELECT * FROM `moves` WHERE Row=3 AND Col=3";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	while ($tableData = $result->fetch_assoc()){
		$player = $tableData["Player"];
	}
}

// Print the Table	
for ($k = 0; $k < 3; $k++){
	echo("<tr>");
	for ($i = 0; $i < 3; $i++){
		echo("<td> " );
		if($gameOver != 1){
			echo("<a href=\"/server/game.php?row=" . $k . "&col=" . $i . "&player=" . $player . "\"  > ");
		}
		$sql = "SELECT * FROM `moves` WHERE Row=$k AND Col=$i";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			while ($tableData = $result->fetch_assoc()){
				echo($tableData["Clicked"]);
			}
		}
		if($gameOver != 1){
			echo("</a>");
		}
		echo(" </td>" );
	}
	echo("</tr> ");
}
echo("</table>");

$conn->close();

// Check if the Game is Over
if ($gameOver == 1 ){
	if ($player == 1 ){
		echo("<h1>Player 2 Wins </h1>");
	} else {
		echo("<h1>Player 1 Wins </h1>");
	}
}
header('Refresh: 2;url=/server/game.php'); // Reload the page every 2 Seconds
?>
<br></br>
<br></br>
<a href="/server/newGame.php" ><input class="button" type="submit" value="New Game"> </a>
</body>
</html>
