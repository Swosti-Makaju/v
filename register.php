<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="css/regs.css" type="text/css">
</head>
<body>

<?php
require_once('connection.php');
if(isset($_POST['regs'])) {
    $fname = mysqli_real_escape_string($con, $_POST['fname']);
    $lname = mysqli_real_escape_string($con, $_POST['lname']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $lic = mysqli_real_escape_string($con, $_POST['lic']);
    $ph = mysqli_real_escape_string($con, $_POST['ph']);
    $pass = mysqli_real_escape_string($con, $_POST['pass']);
    $cpass = mysqli_real_escape_string($con, $_POST['cpass']);
    $gender = mysqli_real_escape_string($con, $_POST['gender']);
    $Pass = md5($pass);

    if(empty($fname) || empty($lname) || empty($email) || empty($lic) || empty($ph) || empty($pass) || empty($gender)) {
        echo '<script>alert("Please fill all fields")</script>';
    } else {
        if($pass == $cpass) {
            $sql2 = "SELECT * FROM users WHERE EMAIL='$email'";
            $res = mysqli_query($con, $sql2);
            if(mysqli_num_rows($res) > 0) {
                echo '<script>alert("Email already exists. Press OK to login!")</script>';
                echo '<script>window.location.href = "index.php";</script>';
            } else {
                $sql = "INSERT INTO users (FNAME, LNAME, EMAIL, LIC_NUM, PHONE_NUMBER, PASSWORD, GENDER) VALUES ('$fname', '$lname', '$email', '$lic', '$ph', '$Pass', '$gender')";
                $result = mysqli_query($con, $sql);
                if($result) {
                    echo '<script>alert("Registration successful! Press OK to login.")</script>';
                    echo '<script>window.location.href = "index.php";</script>';
                } else {
                    echo '<script>alert("Please check the connection")</script>';
                }
            }
        } else {
            echo '<script>alert("Passwords do not match")</script>';
            echo '<script>window.location.href = "register.php";</script>';
        }
    }
}
?>

<div class="main">
    <div class="register">
        <h2>Register Here</h2>
        <form id="register" action="register.php" method="POST">    
            <label>First Name:</label>
            <input type="text" name="fname" placeholder="Enter Your First Name" required>

            <label>Last Name:</label>
            <input type="text" name="lname" placeholder="Enter Your Last Name" required>

            <label>Email:</label>
            <input type="email" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="ex: example@ex.com" placeholder="Enter Valid Email" required>

            <label>Your License Number:</label>
            <input type="text" name="lic" placeholder="Enter Your License Number" required>

            <label>Phone Number:</label>
            <input type="tel" name="ph" maxlength="10" onkeypress="return onlyNumberKey(event)" placeholder="Enter Your Phone Number" required>

            <label>Password:</label>
            <input type="password" name="pass" maxlength="12" id="psw" placeholder="Enter Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required>

            <label>Confirm Password:</label>
            <input type="password" name="cpass" placeholder="Re-enter the password" required>

            <label>Gender:</label>
            <div class="gender-group">
                <input type="radio" id="male" name="gender" value="male">
                <label for="male">Male</label>
                <input type="radio" id="female" name="gender" value="female">
                <label for="female">Female</label>
            </div>

            <input type="submit" class="btnn" value="REGISTER" name="regs">
        </form>
    </div> 
</div>


</body>
</html>