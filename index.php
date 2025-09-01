<?php
session_start();
include("./DB/conn_db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $User_Email = trim($_POST["User_email"] ?? '');
    $User_Password = $_POST["User_password"] ?? '';

    if (!filter_var($User_Email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    }
    elseif (strlen($User_Password) < 8 || strlen($User_Password) > 64) {
        $error = "Password must be between 8 and 64 characters.";
    } else {
        try {
            $get_user = "SELECT * FROM users WHERE User_Email = :email LIMIT 1";
            $stmt = $db_connect->prepare($get_user);
            $stmt->bindParam(":email", $User_Email, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (password_verify($User_Password, $user["User_Password"])) {
                    session_regenerate_id(true);

                    $_SESSION["user_id"] = (int)$user["User_Id"];
                    $_SESSION["user_name"] = htmlspecialchars($user["User_Name"], ENT_QUOTES, 'UTF-8');
                    $_SESSION["user_email"] = htmlspecialchars($user["User_Email"], ENT_QUOTES, 'UTF-8');

                    header("Location: ./pages/dashboard/dashboard.php");
                    exit;
                } else {
                    $error = "Wrong password.";
                }
            } else {
                $error = "No account found with that email.";
            }
        } catch (\PDOException $e) {
            // Don’t reveal DB details to user
            error_log("DB Error: " . $e->getMessage());
            $error = "Something went wrong. Please try again later.";
        }
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
    <form method="post" novalidate>
        <label for="email">Email</label><br>
        <input type="email" name="User_email" required><br>

        <label for="password">Password</label><br>
        <input type="password" name="User_password" required minlength="8" maxlength="64"><br>

        <button style="margin-top: 30px;" type="submit">Submit</button><br>

        <p>Don't have an account? <a href="./pages/auth/signup.php">Sign up</a></p>
        
        <?php if (!empty($error)): ?>
            <div style="color: red;">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>
    </form>
</body>
</html>
