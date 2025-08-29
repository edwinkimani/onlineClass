<?php
session_start();
include("./DB/conn_db.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $User_Email = $_POST["User_email"];
    $User_password = $_POST["User_password"];

    $get_user = "SELECT * FROM users WHERE User_Email = '$User_Email' LIMIT 1";
    $get_user_stmt = $db_connect->query($get_user);

    if ($get_user_stmt && $get_user_stmt->rowCount() > 0) {
        $user = $get_user_stmt->fetch(PDO::FETCH_ASSOC);

        if ($User_password === $user["User_Password"]) {
            $_SESSION["user_id"] = $user["User_Id"];
            $_SESSION["user_name"] = $user["User_Name"];
            $_SESSION["user_email"] = $user["User_Email"];
            header("Location: ./pages/dashboard/dashboard.php");
            exit;
        } else {
            $error = "Wrong password.";
        }
    } else {
        $error = "No account found with that email.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
</head>

<body>
    <h1>Login Page</h1>
    <?php if (!empty($error)) : ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="post">
        <label for="email">Email</label><br>
        <input type="email" name="User_email" required><br>
        <label for="password">Password</label><br>
        <input type="password" name="User_password" required><br>
        <button style="margin-top: 30px;" type="submit">Submit</button><br>
        <p>Don't have an account? <a href="./pages/auth/signup.php">Sign up</a></p>
    </form>
</body>

</html>
