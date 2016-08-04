<p> Hi <?php echo $data['first_name']; ?></p>
<br/>
<br/>
<p>
    For changing your password please visit following <a
        href="<?php echo PATH; ?>users/passwordVerification/<?php echo $data['password_token']; ?>">link</a>
</p>