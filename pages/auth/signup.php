<?php
include('../../DB/conn_db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $User_Name = $_POST["User_Name"];
    $User_Email = $_POST["User_Email"];
    $User_Password = $_POST["User_Password"];

    try {
        $create_user_sql = "INSERT INTO users (User_Name,User_Email,User_Password) VALUES ('$User_Name','$User_Email','$User_Password');";
        $Create_User = $db_connect->prepare($create_user_sql);
        $Create_User->execute();
        header("Location: ../../index.php");
    } catch (\PDOException $e) {
        die("Database error:" . $e->getMessage());
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
    <form method="post">
        <h1>Signup Page</h1>
        <label for="Name">Name</label><br>
        <input type="text" name="User_Name"><br>
        <label for="Email">Email</label><br>
        <input type="email" name="User_Email"><br>
        <label for="Password">Password</label><br>
        <input type="password" name="User_Password"><br>
        <button style="margin-top: 30px;" type="submit">Sign up</button>
        <p>You have an account? <a href="../../index.php">Login</a></p>
    </form>
</body>

</html>