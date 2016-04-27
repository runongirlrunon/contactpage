<?php
// ignore first 2 lines--used for testing data input
// print_r($_POST);
// echo "<br>";

$servername = "setapproject.org";
$username = "csc412";
$password = "csc412";
$dbname = "csc412";

// blank variables for form validation use
$emailErr = $zipErr = $notesErr = "";

$custtype = $_POST["custtype"];

$name = ucwords(strtolower(trim($_POST["name"])));

if (empty($_POST["email"])) {
  $emailErr = "Email is required.";
} else {
  // remove any illegal characters
  $email = filter_var(strtolower($_POST["email"]), FILTER_SANITIZE_EMAIL);
  // validate email address
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $emailErr = "This is not a valid email address.";
  }
}

$tel = preg_replace('/[^0-9]/', '', $_POST["tel"]);

if (empty($_POST["zip"])){
  $zipErr = "Zip code is required.";
} else {
  $zip = $_POST["zip"];
}

$address = trim(ucwords(strtolower($_POST["street"]))).", ".trim(ucwords(strtolower($_POST["city"]))).", ".substr($_POST["state"], 0, 2).", ".$zip;

if (empty($_POST["notes"])){
  $notesErr = "A short note will help us help you.";
} else {
  $notes = str_replace("'", '', (trim($_POST["notes"])));
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO thisisatable (custtype, name, email, tel, zip, address, notes)
VALUES ('$custtype', '$name', '$email', '$tel', '$zip', '$address', '$notes')";

if ($conn->query($sql) === TRUE) {
  echo "New record created successfully<br>";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$sql = "SELECT custtype, name, email, tel, zip, address, notes FROM thisisatable";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    echo "Current database contains: <br>";
    while($row = $result->fetch_assoc()) {
        echo "(".$row["custtype"].") ";
        echo "Name: " . $row["name"] . "<br>";
        echo "&nbsp;&nbsp;Email: " . $row["email"]. "<br>";
        echo "&nbsp;&nbsp;Phone number: " . $row["tel"] . "<br>";
        echo "&nbsp;&nbsp;Address: " . $row["address"] . "<br>";
        echo "&nbsp;&nbsp;Notes: " . $row["notes"] . "<br><br>";
    }
} else {
    echo "0 results";
}

$conn->close();
?>
