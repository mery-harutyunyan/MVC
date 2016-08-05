function validate(input) {
    var error_message = '';
    if (input.hasClass('required') && input.val() == '') {
        error_message = "<p>This field is required</p>";
    } else if (input.hasClass('email') && !isValidEmailAddress(input.val())) {
        error_message = "<p>Please enter valid email</p>";

    } else if (input.hasClass('password') && !isValidPassword(input.val())) {
        error_message = "<p>Password must contain digit, uppercase and lowercase letters</p>";
    }
    else if (input.hasClass('password') && !isValidPasswordLength(input.val(), 3, 8)) {
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

/*
 check if email is valid
 */
function isValidEmailAddress(email) {
    var pattern = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return pattern.test(email);
}

/*
 check if password have at least one digit, lowercase, uppercase letter
 */
function isValidPassword(password) {

    return /^[A-Za-z0-9\d=!\-@._*]*$/.test(password) // consists of only these
        && /[A-Z]/.test(password) // has a uppercase letter
        && /[a-z]/.test(password) // has a lowercase letter
        && /\d/.test(password) // has a digit
}

/*
 check if password length is between min an max
 */
function isValidPasswordLength(password, min, max) {
    return password.length >= min && password.length <= max
}