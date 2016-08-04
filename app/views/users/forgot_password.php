<?php
$this->render('elements/header');
$this->render('elements/menu');
?>

<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 well">
            <form role="form" action="/users/sendPasswordEmail" method="post" name="forgotpasswordform" id="forgotpasswordform">
                <fieldset>
                    <legend>Forgot password</legend>

                    <div class="form-group">
                        <label for="name">Email</label>
                        <input type="text" name="email" placeholder="Your Email" class="required email form-control"/>
                        <span class="text-danger"><?php if (isset($email_error)) echo $email_error; ?></span>
                    </div>


                    <span class="login-error text-danger"><?php if (isset($forgotpassword_error)) echo $forgotpassword_error; ?></span>


                    <div class="form-group">
                        <button type="submit" name="submit" class="btn btn-primary">Go</button>
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

        $("#forgotpasswordform").submit(function (e) {
            if ($(e.target).data('valid') === undefined || $(e.target).data('valid') == false) {
                e.preventDefault();

                var message = '';
                $('input').each(function () {
                    message = validate($(this));
                    $(this).next(".text-danger").html(message).slideDown();


                })

                if (message == '') {
                    $(e.target).data('valid', true);
                    $("#forgotpasswordform").submit();
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

        } else {
            error_message = "";
        }

        return error_message;

    }

    function isValidEmailAddress(email) {
        var pattern = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return pattern.test(email);
    }


</script>
<?php
$this->render('elements/footer');
?>

