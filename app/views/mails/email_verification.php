<p> Hi <?php echo $data['first_name']; ?></p>
<br/>
<br/>
<p>
    Please verify your account by clicking following <a
        href="<?php echo PATH; ?>users/accountVerification/<?php echo $data['verify_token']; ?>">link</a>
</p>