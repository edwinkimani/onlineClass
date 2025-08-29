<?php
include("../../DB/conn_db.php");

try {
    // Get search parameter - XSS vulnerable
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    if ($search) {
       $sql_query ="SELECT * FROM users WHERE User_Name LIKE '%$search%' OR User_Email LIKE '%$search%'";
    }
    else {
        $sql_query = "SELECT * FROM users";
    }
    
    $stmt = $db_connect->prepare($sql_query);
    $stmt->execute();
    $students = $stmt -> fetchAll(PDO::FETCH_ASSOC);

} catch (\PDOException $e) {
    die("Database Error:".$e->getMessage());
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
      <h1>Welcome</h1>
      <form method="GET">
            <input type="text" name="search" placeholder="Search users..." 
                   value="<?php echo $search; ?>" class="search-box">
            <button type="submit" class="btn" style="width: auto; margin-left: 10px;">Search</button>
        </form>
      <table >
            <thead>
                <th>Users_id</th>
                <th>Users_name</th>
                <th>Users_email</th>
            </thead>
            <tbody>
                <?php foreach ($students as $student):?>
                    <tr>
                        <td><?php echo $student['User_Id']?></td>
                        <td><?php echo $student['User_Name']?></td>
                        <td><?php echo $student['User_Email']?></td>
                    </tr>
                <?php endforeach;?>

            </tbody>
      </table>
</body>
</html>