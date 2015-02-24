<html>
    <head>
        <title>Attand Data</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
<?php
 if (isset($_POST["sending"])){
        $email=$_POST["email"];
        echo $email;
 }else{
        echo "Form not send";
}
?>
        <form action="test_teamplate.html" method="post">
            <tr><td>Input Email:</td><td><input type="text" name="email" size="8"/></td>
       <br>
       <br>
       <tr><td>Choose Date:</td><rd><input type="date" name="date" size="8"></td><tr>
       <br>
       <br>
       <input type="submit" name="sending" value="SEND">
        </form>
    </body>
</html>
