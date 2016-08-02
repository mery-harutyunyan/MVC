<?php
$this->render('elements/header');
$this->render('elements/menu');
?>

<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 well">
            <form role="form" action="/users/login" method="post" name="loginform" id="loginform">
                <fieldset>
                    <legend>Login</legend>

                    <div class="form-group">
                        <label for="name">Email</label>
                        <input type="text" name="email" placeholder="Your Email" class="required email form-control"/>
                        <span class="text-danger"><?php if (isset($email_error)) echo $email_error; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="name">Password</label>
                        <input type="password" name="password" placeholder="Your Password"
                               class="required password form-control"/>
                        <span class="text-danger"><?php if (isset($password_error)) echo $password_error; ?></span>
                    </div>

                    <span class="login-error text-danger"><?php if (isset($login_error)) echo $login_error; ?></span>


                    <div class="form-group">
                        <button type="submit" name="login" class="btn btn-primary">Login</button>
                    </div>
                </fieldset>
            </form>

        </div>
    </div>

</div>
<script>
    $(document).ready(function () {

        $("input").blur(function () {

            $('.login-error').html('');
            message = validate($(this));
            $(this).next(".text-danger").html(message).slideDown();
        });

        $("#loginform").submit(function (e) {
            if ($(e.target).data('valid') === undefined || $(e.target).data('valid') == false) {
                e.preventDefault();

                var message = '';
                $('input').each(function () {
                    message = validate($(this));
                    $(this).next(".text-danger").html(message).slideDown();


                })

                if (message == '') {
                    $(e.target).data('valid', true);
                    $("#loginform").submit();
                } else {
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

