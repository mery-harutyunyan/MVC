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
<script src="../../../public/js/form_validation.js"></script>
<script>
    $(document).ready(function () {

        $("input").blur(function () {

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

</script>
<?php
$this->render('elements/footer');
?>

