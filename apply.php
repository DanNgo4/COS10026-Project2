<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="description" content="Demonstrates some basic HTML form elements">
  <meta name="keywords" content="HTML, Form, tags">
  <meta name="author" content="A Lecturer">
  <title>JOB APPLICATION FORM</title>
  <link rel="stylesheet" href="styles/style.css">
  <link rel="icon" type="image/x-icon" href="images/soe_logo_transparent_small.png"><!-- Dan added this favicon 12/04-->
</head>
<body>
<?php 
	include 'Header.inc'; 
?>
    <div class="BodyContent">

 <main id="applybody">
<!--merge the page with header eliminating the gap -->
<!--h3{text-align: center ; color: aqua; background-color: blue; font-weight: bold; font-size: 2em;}-->
  <h3 class="heading-apply">Apply With Us!</h3>

    <form method="post" action="processEOI.php" novalidate="novalidate">
        <!--fieldset{background-color: lightgrey;}-->
        <fieldset>
            <!--Job reference with a text area max inputs 5 alphanumeric characters-->
            <p><label for="JRN">Job Reference Number</label>
                <input type="text" name="Job_Reference" id="Job_Reference" maxlength="5" pattern="^[a-zA-Z0-9]{1,5}" required="required"></p>
        </fieldset>
	<fieldset>
        <!--legend{font-family: serif ;color:black; font-size: 1.5em;}
-->
        <legend class="heading-apply">Personal details</legend>
	<p><label for="First_Name"> First Name</label>
        <input type="text" name="First_Name" id="First_Name" Maxlength="20" pattern="[a-zA-Z]{1,20}" required="required">
	</p>
    <!--ensure proper gap between labels-->
	
    <p><label for="Last_Name">Last Name</label>
        <input type="text" name="Last_Name" id="Last_Name" maxlength="20" pattern="[A-Za-z]{1,20}" required="required">
        </p>
        <p><label for="dob">DOB</label>
            <input type="text" name="dob" id="dob" placeholder="YYYY/MM/DD">
            <p><label for="street">Steet Address</label>
            <input type="text" name="Street_Address" id="Street_Address" maxlength="40" pattern="^[a-zA-Z0-9]{1,40}+$" required="required">
            <label for="suburb">Suburb/Town</label>
            <input type="text" name="Suburb_Town" id="Suburb_Town" maxlength="40" pattern="^[a-zA-Z0-9]{1,40}+$" required="required">
            <!--States in drop down-->
            <label for="State">State</label>
            <select name="State" id="State">
                <option selected="selected">Please Select</option>
                <option value="VIC">VIC</option>
                <option value="NSW">NSW</option>
                <option value="QLD">QLD</option>
                <option value="NT">NT</option>
                <option value="WA">WA</option>
                <option value="SA">SA</option>
                <option value="TAS">TAS</option>
                <option value="ACT">ACT</option>
            </select>
            <label for="post">Post Code</label>
            <input type="text" name="Postcode" id="Postcode" maxlength="4" pattern="\d{4}" required="required">
            </p></fieldset>
            <fieldset>
                <!--Gender with radio inputs-->
                <legend class="heading-apply">Gender</legend>
            <p>
                <label for="Female">Female</label>
                <input type="radio" value="Female" name="Gender" id="Female">
                <label for="Male">Male</label>
                <input type="radio" value="Male" name="Gender" id="Male">
                <label for="TransGender">TransGender</label>
                <input type="radio" value="TransGender" name="Gender" id="TransGender">
                <label for="Nonbinary">Non Binary</label>
                <input type="radio" value="Nonbinary" name="Gender" id="Nonbinary">
                <label for="Others">Others</label>
                <input type="radio" value="Others" name="Gender" id="Others">
	        </p>
            </fieldset>
            <fieldset>
                <legend class="heading-apply">Contact Details</legend>
                <p><label for="Phone">Phone Number</label>
                <input type="text" name="Phone_Number" id="Phone_Number" pattern="[\d\s]{8,12}" required="required">
                <label for="email">E-mail Address</label>
                <input type="email" name="Email_Address" id="Email_Address" required="required">
                </p>
            </fieldset>
            
                 <fieldset>
                    <legend class="heading-apply">Skills and Experience</legend>
                    <p>Skills</p>
                        <p><label for="OOP">Object Oriented programming</label>
                            <input type="checkbox" value="OOP" name="Skills" id="OOP" >
                        <label for="APIs">APIs</label>
                        <input type="checkbox" value="APIs" name="Skills" id="APIs">
                        <label for="AWS">AWS</label>
                        <input type="checkbox" value="AWS" name="Skills" id="AWS">
                        <label for="Java">Java</label>
                        <input type="checkbox" value="Java" name="Skills" id="Java">
                        <label for="Python">Python</label>
                        <input type="checkbox" value="Python" name="Skills" id="Python">
                        <label for="Otherskills">Other Skills</label>
                        <input type="checkbox" value="Otherskills" name="Skills" id="OS" >
                        </p>

                           <p><label for="OtherSkills">Other Skills</label>
                             <textarea id="OtherSkills" name="Description of Other Skills" rows="4" cols="40" placeholder="Mention your skills here"></textarea></p>

                    </fieldset>
                    
            <!-- Jugraj Adding buttons -->
            <button class="btn02"><input class="btnapy" type="submit" value="Apply"></button>
            <button class="btn02"><input class="btnapy" type="reset" value="Reset Form"></button>


        </form>
    </div>
  </main>

<?php 
	include 'Footer.inc'; 
?>
</body>
</html>

