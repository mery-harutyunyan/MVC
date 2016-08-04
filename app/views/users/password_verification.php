<?php
$this->render('elements/header');
$this->render('elements/menu');
?>


<div class="container">

    <div class="row">
        <div class="col-md-4 col-md-offset-4 well">
            <form role="form" action="/users/passwordVerification" method="post" name="passwordverificationform" id="passwordverificationform">
                <fieldset>
                    <legend>Reset password</legend>

                    <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
                    <div class="form-group">
                        <label for="name">New password</label>
                        <input id="password" type="password" name="password" placeholder="Password"
                               class="password required form-control"/>
                        <span class="text-danger"><?php if (isset($password_error)) echo $password_error; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="name">Confirm New Password</label>
                        <input type="password" name="cpassword" placeholder="Confirm Password"
                               class="cpassword required form-control"/>
                        <span class="text-danger"><?php if (isset($cpassword_error)) echo $cpassword_error; ?></span>
                    </div>



                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Reset password</button>
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

        $("#passwordverificationform").submit(function (e) {
            if ($(e.target).data('valid') === undefined || $(e.target).data('valid') == false) {
                e.preventDefault();

                var message = '';
                $('input').each(function () {
                    message = validate($(this));
                    $(this).next(".text-danger").html(message).slideDown();


                })

                if(message == ''){
                    $(e.target).data('valid', true);
                    $("#passwordverificationform").submit();
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











