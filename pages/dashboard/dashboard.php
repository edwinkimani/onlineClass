<?php
session_start();
include("../../DB/conn_db.php");

// Block access if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit;
}

try {
    if (!empty($_GET['search'])) {
        $search = "%" . $_GET['search'] . "%";
        $sql_query = "SELECT * FROM users WHERE User_Name LIKE :search";
        $stmt = $db_connect->prepare($sql_query);
        $stmt->bindParam(":search", $search, PDO::PARAM_STR);
    } else {
        $sql_query = "SELECT * FROM users";
        $stmt = $db_connect->prepare($sql_query);
    }

    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (\PDOException $e) {
    die("Database Error: " . htmlspecialchars($e->getMessage()));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>

<body>
    <h1>Welcome</h1>

    <p><b>User ID:</b> <?= htmlspecialchars($_SESSION['user_id']) ?></p>
    <p><b>User Email:</b> <?= htmlspecialchars($_SESSION['user_email']) ?></p>
    <p><b>User Name:</b> <?= htmlspecialchars($_SESSION['user_name']) ?></p>

    <a href="../../logout.php">Logout</a>

    <form method="GET">
        <input type="text" name="search" placeholder="Search users..."
            value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
        <button type="submit">Search</button>
    </form>

    <table style="margin-top:10px;" border="1">
        <thead>
            <tr>
                <th>User_Id</th>
                <th>User_Name</th>
                <th>User_Email</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $student): ?>
                <tr>
                    <td><?= htmlspecialchars($student['User_Id']) ?></td>
                    <td><?= htmlspecialchars($student['User_Name']) ?></td>
                    <td><?= htmlspecialchars($student['User_Email']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>