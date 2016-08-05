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
</div>
<script src="../../../public/js/form_validation.js"></script>

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


</script>
<?php
$this->render('elements/footer');
?>











