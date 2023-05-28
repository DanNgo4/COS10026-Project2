<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>processEOI</title>
</head>
<body>
    <?php
// Server-side validation and processing

// This function ensures all data should be sanitized to remove leading and trailing spaces, backslashes, and HTML control characters.
function sanitise_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

// Connect to the database
require_once("settings.php");

$conn = @mysqli_connect(
    $host,
    $user,
    $pwd,
    $sql_db
);
// Check connection; if failed, display an error message
if (!$conn) {
    echo "<p>Database connection failure</p>";
}


// Validate and sanitize form inputs

$errMsg = ""; // Added variable to store error messages

if (isset($_POST["Job_Reference"])) {
    $Job_Reference = sanitise_input($_POST["Job_Reference"]);
} else {
    header("location: apply.php");
    exit;
}

if (isset($_POST["First_Name"])) {
    $First_Name = sanitise_input($_POST["First_Name"]);
} else {
    header("location: apply.php");
    exit;
}

if (isset($_POST["Last_Name"])) {
    $Last_Name = sanitise_input($_POST["Last_Name"]);
} else {
    header("location: apply.php");
    exit;
}

if (isset($_POST["dob"])) {
    $dob = sanitise_input($_POST["dob"]);
} else {
    header("location: apply.php");
    exit;
}

if (isset($_POST["Gender"])) {
    $Gender = sanitise_input($_POST["Gender"]);
} else {
    header("location: apply.php");
    exit;
}

if (isset($_POST["Street_Address"])) {
    $Street_Address = sanitise_input($_POST["Street_Address"]);
} else {
    header("location: apply.php");
    exit;
}

if (isset($_POST["Suburb_Town"])) {
    $Suburb_Town = sanitise_input($_POST["Suburb_Town"]);
} else {
    header("location: apply.php");
    exit;
}

if (isset($_POST["State"])) {
    $State = sanitise_input($_POST["State"]);
} else {
    header("location: apply.php");
    exit;
}

if (isset($_POST["Postcode"])) {
    $Postcode = sanitise_input($_POST["Postcode"]);
} else {
    header("location: apply.php");
    exit;
}

if (isset($_POST["Email_Address"])) {
    $Email_Address = sanitise_input($_POST["Email_Address"]);
} else {
    header("location: apply.php");
    exit;
}

if (isset($_POST["Phone_Number"])) {
    $Phone_Number = sanitise_input($_POST["Phone_Number"]);
} else {
    header("location: apply.php");
    exit;
}

if (isset($_POST["OtherSkills"])) {
    $OtherSkills = sanitise_input($_POST["OtherSkills"]);
} else {
    header("location: apply.php");
    exit;
}

$Job_Reference = sanitise_input($Job_Reference);
$First_Name = sanitise_input($First_Name);
$Last_Name = sanitise_input($Last_Name);
$dob = sanitise_input($dob);
$Gender = sanitise_input($Gender);
$Street_Address = sanitise_input($Street_Address);
$Suburb_Town = sanitise_input($Suburb_Town);
$State = sanitise_input($State);
$Postcode = sanitise_input($Postcode);
$Email_Address = sanitise_input($Email_Address);
$Phone_Number = sanitise_input($Phone_Number);
$OtherSkills = sanitise_input($OtherSkills);

// Make sure all the required fields are filled; otherwise, display error message
if (empty($Job_Reference)) {
    $errMsg .= "<p>You must enter a job reference number.</p>";
} elseif (!preg_match("/^[a-zA-Z0-9]{5}$/", $Job_Reference)) {
    $errMsg .= "<p>Only five alphanumeric characters are allowed in the job reference number.</p>";
}

if (empty($First_Name)) {
    $errMsg .= "<p>You must enter your first name.</p>";
} elseif (!preg_match("/^[a-zA-Z]{1,20}$/", $First_Name)) {
    $errMsg .= "<p>Only 20 alphabetic letters are allowed in your first name.</p>";
}


if (empty($Last_Name)) {
    $errMsg .= "<p>You must enter your last name.</p>";
} elseif (!preg_match("/^[a-zA-Z]{1,20}$/", $Last_Name)) {
    $errMsg .= "<p>Only 20 alphabetic letters are allowed in your last name.</p>";
}


if (empty($dob)) {
    $errMsg .= "<p>You must enter your date of birth.</p>";
} else {
    $dobDateTime = DateTime::createFromFormat('Y-m-d', $dob);
    if (!$dobDateTime) {
        $errMsg .= "<p>You must enter the date of birth in the correct format: YYYY-MM-DD.</p>";
    } else {
        $now = new DateTime();
        $age = $now->diff($dobDateTime)->y;
        if ($age < 15 || $age > 80) {
            $errMsg .= "<p>Your age must be between 15 and 80 years.</p>";
        }
    }
}


if (empty($Gender)) {
    $errMsg .= "<p>Gender is required.</p>";
} else {
    // Radio input has a value, you can proceed with further processing
    $selectedGender = $_POST['Gender'];
}


if (empty($Street_Address)) {
    $errMsg .= "<p>You must enter your street address.</p>";
} elseif (!preg_match("/^[a-zA-Z]{1,40}$/", $Street_Address)) {
    $errMsg .= "<p>Only 40 alphabetic letters are allowed in your street address.</p>";
}


if (empty($Suburb_Town)) {
    $errMsg .= "<p>You must enter your suburb or town.</p>";
} elseif (!preg_match("/^[a-zA-Z]{1,40}$/", $Suburb_Town)) {
    $errMsg .= "<p>Only 40 alphabetic letters are allowed in your suburb or town.</p>";
}


if (empty($State)) {
    $errMsg .= "<p>You must select a state.</p>";
}


if (empty($Postcode)) {
    $errMsg .= "<p>You must enter your postcode.</p>";
} elseif (!preg_match("/^\d{4}$/", $Postcode)) {
    $errMsg .= "<p>Postcode must be exactly 4 digits long.</p>";
  
}
// Match postcode with state
$postcodeState = array(
  "3000" => "VIC",
  "2000" => "NSW",
  "4000" => "QLD",
  "0800" => "NT",
  "6000" => "WA",
  "5000" => "SA",
  "7000" => "TAS",
  "2600" => "ACT",
  
);
// Check if the entered postcode matches the selected state
if (isset($State) && isset($Postcode)) {
  $postcode = sanitise_input($Postcode);
  $postcode = str_pad($postcode, 4, "0", STR_PAD_LEFT); // Pad the postcode with leading zeros if necessary

  if (!isset($postcodeState[$postcode])) {
      $errMsg .= "<p>Invalid postcode.</p>";
  } elseif ($postcodeState[$postcode] !== $State) {
      $errMsg .= "<p>The selected state does not match the entered postcode.</p>";
  }
}


if (empty($Email_Address)) {
    $errMsg .= "<p>You must enter your email address.</p>";
} elseif (!filter_var($Email_Address, FILTER_VALIDATE_EMAIL)) {
    $errMsg .= "<p>You must enter a valid email address.</p>";
}


if (empty($Phone_Number)) {
    $errMsg .= "<p>You must enter your phone number.</p>";
} elseif (!preg_match("/^(\+?\d{1,4}[\s-]?)?(?!0+\s+,?$)\d{10}\s*,?$/", $Phone_Number)) {
    $errMsg .= "<p>You must enter a valid phone number (10 digits, no spaces or special characters).</p>";
}

 
// Create the EOI table if it doesn't exist
$create_table_query = "CREATE TABLE IF NOT EXISTS EOI (
  id INT AUTO_INCREMENT PRIMARY KEY,
  Job_Reference VARCHAR(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  First_Name VARCHAR(20) NOT NULL,
  Last_Name VARCHAR(20) NOT NULL,
  dob DATE NOT NULL,
  Gender VARCHAR(10) NOT NULL,
  Street_Address VARCHAR(40) NOT NULL,
  Suburb_Town VARCHAR(40) NOT NULL,
  State VARCHAR(3) NOT NULL,
  Postcode VARCHAR(4) NOT NULL,
  Email_Address VARCHAR(255) NOT NULL,
  Phone_Number VARCHAR(12),
  OtherSkills TEXT,
  UNIQUE KEY(Job_Reference)
)";
$conn->query($create_table_query);

// Insert the EOI record into the table
$insert_query = "INSERT INTO EOI (Job_Reference, first_name, last_name, dob, Gender, Street_Address, Suburb_Town, State , Postcode, Email_Address, Phone_Number, OtherSkills) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($insert_query);
$stmt->bind_param("ssssssssssss", $_POST["Job_Reference"], $_POST["first_name"], $_POST["last_name"], $_POST["dob"], $_POST["Gender"], $_POST["Street_Address"], $_POST["Suburb_Town"], $_POST["State"], $_POST["Postcode"], $_POST["Email_Address"], $_POST["Phone_Number"], $_POST["OtherSkills"]);

if ($stmt->execute()) {
  // Retrieve the auto-generated EOInumber
  $eoi_number = $stmt->insert_id;
}
?>
</body>
</html>
