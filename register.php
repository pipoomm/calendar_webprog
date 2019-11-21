<?php
/**
 * Copyright (C) 2013 peredur.net
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
include_once 'includes/register.inc.php';
include_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Calendar Project | Registration Form</title>
        <script type="text/JavaScript" src="js/sha512.js"></script> 
        <script type="text/JavaScript" src="js/forms.js"></script>
        <link rel="stylesheet" href="css/css.css" type="text/css">
    </head>
    <body style=" margin: 0; padding: 0; font-family: sans-serif; background: url(css/90977.jpg);
  background-size: cover;">
        <!-- Registration form to be output if the POST variables are not
        set or if the registration script caused an error. -->
        
        <?php
        if (!empty($error_msg)) {
            echo $error_msg;
        }
        ?>
        <form class="boxreg" method="post" name="registration_form" action="<?php echo esc_url($_SERVER['PHP_SELF']); ?>">
            <h1>Register</h1>
            <input type='text' name='username' id='username' placeholder="Username"/><br>
            <input type="text" name="email" id="email" placeholder="Email"/><br>
            <input type="password" name="password" id="password"placeholder="Password"/><br>
            <input type="password" name="confirmpwd" id="confirmpwd" placeholder="Confirm Password"/><br>
            <button class="btn btnregis" onclick="return regformhash(this.form,
                                   this.form.username,
                                   this.form.email,
                                   this.form.password,
                                   this.form.confirmpwd);" /> Register</button>                       
         <div style="color: white; font-size: 13px;">
            <p>Usernames may contain only digits, upper and lower case letters and underscores<br>
            <br>Emails must have a valid email format<br>
            <br>Passwords must be at least 6 characters long<br></p>
            <p>Passwords must contain
                <ul>
                    <li>At least one upper case letter (A..Z)</li>
                    <li>At least one lower case letter (a..z)</li>
                    <li>At least one number (0..9)</li>
                </ul>
            </p>
            <p>Your password and confirmation must match exactly</p>
        <p>Return to the <a href="index.php">login page</a>.</p>
         </div> 
        </form>
            
    </body>
</html>
