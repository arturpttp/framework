<?php
?>

<form action="/user/register/submit" method="post" class="">
    <input type="text" name="name" placeholder="Name">
    <input type="text" name="user" placeholder="User">
    <input type="text" name="email" placeholder="E-mail">
    <input type="password" name="password" id="pass" placeholder="Password">
    <input type="password" name="confirm_password" id="pass2" placeholder="Confirm password">
    <input type="submit" name="submit" placeholder="Submit">
    <input type="checkbox" name="seepassword" placeholder="Show password" onclick="showPassword()">
</form>

<script>
    function showPassword(){
        var x = document.getElementById("pass");
        var x2 = document.getElementById("pass2");
        if (x.type === "password") {
            x.type = "text";
            x2.type = "text";
        } else {
            x.type = "password";
            x2.type = "password";
        }
    }
</script>