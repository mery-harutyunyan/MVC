<?php
session_start();

if (isset($_SESSION['usr_id'])) {
    header("Location: index.php");
}

include_once 'dbconnect.php';

//set validation error flag as false
$error = false;

//check if form is submitted
if (isset($_POST['signup'])) {
    $first_name = mysqli_real_escape_string($con, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($con, $_POST['last_name']);

    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);

    //name can contain only alpha characters and space
    if (!preg_match("/^[a-zA-Z ]+$/", $first_name)) {
        $error = true;
        $fname_error = "Name must contain only alphabets and space";
    }

    if (!preg_match("/^[a-zA-Z ]+$/", $last_name)) {
        $error = true;
        $lname_error = "Name must contain only alphabets and space";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $email_error = "Please Enter Valid Email";
    }
    if (strlen($password) < 6) {
        $error = true;
        $password_error = "Password must be minimum of 6 characters";
    }
    if ($password != $cpassword) {
        $error = true;
        $cpassword_error = "Password and Confirm Password doesn't match";
    }
    if (!$error) {
        if (mysqli_query($con, "INSERT INTO users(first_name, last_name,email,password) VALUES('" . $first_name . "', '" . $last_name . "', '" . $email . "', '" . md5($password) . "')")) {
            $successmsg = "Successfully Registered! <a href='login.php'>Click here to Login</a>";
        } else {
            $errormsg = "Error in registering...Please try again later!";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Start page</title>

    <!-- Bootstrap core CSS -->
    <link href="public/css/bootstrap.min.css" rel="stylesheet">


</head>

<body>

<!-- Static navbar -->
<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">Mery's project</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="register.php">Register</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>

        </div>
    </div>
</nav>


<div class="container">

    <div class="row">
        <div class="col-md-4 col-md-offset-4 well">
            <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="signupform"
                  id="signupform">
                <fieldset>
                    <legend>Sign Up</legend>

                    <div class="form-group">
                        <label for="name">First name</label>
                        <input type="text" name="first_name" placeholder="Enter first name"
                               value="<?php if ($error) echo $first_name; ?>" class="form-control"/>
                        <span class="text-danger"><?php if (isset($fname_error)) echo $fname_error; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="name">Last name</label>
                        <input type="text" name="last_name" placeholder="Enter last name"
                               value="<?php if ($error) echo $last_name; ?>" class="form-control"/>
                        <span class="text-danger"><?php if (isset($lname_error)) echo $lname_error; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="name">Email</label>
                        <input type="text" name="email" placeholder="Email" value="<?php if ($error) echo $email; ?>"
                               class="form-control"/>
                        <span class="text-danger"><?php if (isset($email_error)) echo $email_error; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="name">Password</label>
                        <input id="password" type="password" name="password" placeholder="Password"
                               class="form-control"/>
                        <span class="text-danger"><?php if (isset($password_error)) echo $password_error; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="name">Confirm Password</label>
                        <input type="password" name="cpassword" placeholder="Confirm Password" class="form-control"/>
                        <span class="text-danger"><?php if (isset($cpassword_error)) echo $cpassword_error; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputFile">Profile image</label>
                        <input name="image" type="file" id="exampleInputFile">
                        <span class="text-danger"><?php if (isset($cpassword_error)) echo $cpassword_error; ?></span>

                    </div>

                    <div class="form-group">
                        <input type="submit" name="signup" value="Sign Up" class="btn btn-primary"/>
                    </div>
                </fieldset>
            </form>
            <span class="text-success"><?php if (isset($successmsg)) {
                    echo $successmsg;
                } ?></span>
            <span class="text-danger"><?php if (isset($errormsg)) {
                    echo $errormsg;
                } ?></span>
        </div>
    </div>

</div> <!-- /container -->

<script src="public/js/jquery.min.js"></script>
<script src="public/js/bootstrap.min.js"></script>
<script src="public/js/jquery.validate.min.js"></script>
<script>
    $().ready(function () {

        $.validator.addMethod("pwcheck", function(value) {
            return /^[A-Za-z0-9\d=!\-@._*]*$/.test(value) // consists of only these
                && /[a-z]/.test(value) // has a lowercase letter
                && /\d/.test(value) // has a digit
        });
        // validate signup form on keyup and submit
        $("#signupform").validate({
            rules: {
                first_name: "required",
                last_name: "required",

                password: {
                    required: true,
                    minlength: 3,
                    maxlength: 8,
                    pwcheck: true,
                },
                cpassword: {
                    required: true,
                    minlength: 3,
                    equalTo: "#password"
                },
                email: {
                    required: true,
                    email: true
                },

            },
            messages: {
                first_name: "Please enter your firstname",
                last_name: "Please enter your lastname",
                email: "Please enter a valid email address",
                password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 3 characters long",
                    maxlength: "Your password must be maximum 8 characters long",
                    pwcheck : "Your password must contain digit, uppercase, lowercase charecters"
                },
                cpassword: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 5 characters long",
                    equalTo: "Please enter the same password as above"
                },

            }
        });


    });
</script>
</body>
</html>











