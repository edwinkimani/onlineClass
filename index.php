<?php
include("./DB/conn_db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $User_Email = $_POST["User_email"];
    $User_password = $_POST["User_password"];

    $get_user = "SELECT * FROM users where email=`$User_Email`;";
    $get_user_stmt = $db_connect->prepare($get_user);
    $get_user_stmt->execute();

    if ($get_user_stmt && $get_user_stmt->rowcount()) {
        $user = $get_user_stmt->fetchAll(PDO::FETCH_ASSOC);
         if ($User_password === $user["User_Password"]) {
            echo "Login successful. Welcome " . $user["User_Name"];
        } else {
            $error = "Wrong password.";
        }
    }else {
        $error = "No account found with that email.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Login Page</h1>
    <form method="post">
        <label for="email">Email</label><br>
        <input type="email" name="User_email"><br>
        <label for="password">Password</label><br>
        <input type="password" name="User_password"><br>
        <button style="margin-top: 30px;" type="submit">Submit</button><br>
        <p>Don't have an account? <a href="./pages/auth/signup.php">Sign up</a></p>
    </form>
</body>

</html>