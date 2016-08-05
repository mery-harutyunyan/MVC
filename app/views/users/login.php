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

                    <p><a href="/users/forgotPassword">Forgot password</a> </p>

                    <span class="login-error text-danger"><?php if (isset($login_error)) echo $login_error; ?></span>


                    <div class="form-group">
                        <button type="submit" name="login" class="btn btn-primary">Login</button>
                    </div>
                </fieldset>
            </form>

        </div>
    </div>

</div>

<script src="../../../public/js/form_validation.js"></script>

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

</script>
<?php
$this->render('elements/footer');
?>

