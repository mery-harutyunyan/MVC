<?php
$this->render('elements/header');
$this->render('elements/menu');
?>


<div class="container">

    <div class="row">
        <div class="col-md-4 col-md-offset-4 well">
            <form role="form" action="/users/register" enctype="multipart/form-data" method="post" name="signupform" id="signupform">
                <fieldset>
                    <legend>Sign Up</legend>

                    <div class="form-group">
                        <label for="name">First name</label>
                        <input type="text" name="first_name" placeholder="Enter first name"
                               class="required form-control"/>
                        <span class="text-danger"><?php if (isset($fname_error)) echo $fname_error; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="name">Last name</label>
                        <input type="text" name="last_name" placeholder="Enter last name"
                               class="required form-control"/>
                        <span class="text-danger"><?php if (isset($lname_error)) echo $lname_error; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="name">Email</label>
                        <input type="text" name="email" placeholder="Email" class="required email form-control"/>
                        <span class="text-danger"><?php if (isset($email_error)) echo $email_error; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="name">Password</label>
                        <input id="password" type="password" name="password" placeholder="Password"
                               class="password required form-control"/>
                        <span class="text-danger"><?php if (isset($password_error)) echo $password_error; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="name">Confirm Password</label>
                        <input type="password" name="cpassword" placeholder="Confirm Password"
                               class="cpassword required form-control"/>
                        <span class="text-danger"><?php if (isset($cpassword_error)) echo $cpassword_error; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputFile">Profile image</label>
                        <input name="image" type="file" class="image required" >
                        <span class="text-danger"><?php if (isset($image_error)) echo $image_error; ?></span>

                    </div>

                    <div class="form-group">
                        <button type="submit"   class="btn btn-primary">Sign Up</button>
                    </div>
                </fieldset>
            </form>

        </div>
    </div>

</div> <!-- /container -->

<script>
    $(document).ready(function () {

        $("input").blur(function () {
            message = validate($(this));
            $(this).next(".text-danger").html(message).slideDown();
        });

        $("#signupform").submit(function (e) {
            if ($(e.target).data('valid') === undefined || $(e.target).data('valid') == false) {
                e.preventDefault();

                var message = '';
                $('input').each(function () {
                    message = validate($(this));
                    $(this).next(".text-danger").html(message).slideDown();


                })

                if(message == ''){
                    $(e.target).data('valid', true);
                   $("#signupform").submit();
                }else{
                    $(e.target).data('valid', false);
                }
            }
        });
    })

    function validate(input) {
        var error_message = '';
        if (input.hasClass('required') && input.val() == '') {
            error_message = "<p>This field is required</p>";
        } else if (input.hasClass('email') && !isValidEmailAddress(input.val())) {
            error_message = "<p>Please enter valid email</p>";

        } else if (input.hasClass('password') && !isValidPassword(input.val())) {
            error_message = "<p>Password must contain digit, uppercase and lowercase letters</p>";
        }
        else if (input.hasClass('password') && !isValidPasswordLength(input.val())) {
            error_message = "<p>Password must contain 3-8 letters</p>"
        }
        else if (input.hasClass('cpassword') && input.val() != $('.password').val()) {
            error_message = "<p>Passwords does not match</p>";
        }
        else {
            error_message = "";
        }

        return error_message;

    }

    function isValidEmailAddress(email) {
        var pattern = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return pattern.test(email);
    }


    function isValidPassword(password) {

        return /^[A-Za-z0-9\d=!\-@._*]*$/.test(password) // consists of only these
            && /[A-Z]/.test(password) // has a uppercase letter
            && /[a-z]/.test(password) // has a lowercase letter
            && /\d/.test(password) // has a digit
    }

    function isValidPasswordLength(password) {
        return password.length >= 3 && password.length <= 8
    }

</script>
<?php
$this->render('elements/footer');
?>











