<?php
$servername = "localhost";
$username = "root";
$password = "mysql";
$dbname = "project_manager_php";

$connection = mysqli_connect($servername, $username, $password, $dbname);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET["action"]) and $_GET["action"] == "delete") {
    $sql = "DELETE FROM employees WHERE employee_id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $_GET["id"]);
    $res = $stmt->execute();

    $stmt->close();
    mysqli_close($connection);

    header("Location: " . strtok($_SERVER["REQUEST_URI"], "?"));
    die();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/scss/components/reset.css">
    <link rel="stylesheet" href="assets/dist/css/main.min.css">
    <title>ProjectManagerPHP</title>
</head>

<body>
    <?php
    include "header.php"
    ?>

    <main class="main">
        <table class="data">
            <thead class="data__head">
                <tr class="data__row">
                    <th class="data__column data__column--head">Id</th>
                    <th class="data__column data__column--head">First Name</th>
                    <th class="data__column data__column--head">Last Name</th>
                    <th class="data__column data__column--head">Role</th>
                    <th class="data__column data__column--head">Project</th>
                    <th class="data__column data__column--head">Action</th>
                </tr>
            </thead>
            <tbody class="data__body">
                <?php
                $sql = "SELECT employees.employee_id, employees.first_name, employees.last_name, employees.role, employees.project_id, projects.project_title FROM employees
                LEFT JOIN projects ON employees.project_id = projects.project_id;";
                $result = mysqli_query($connection, $sql);
                $idNo = 1;
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        print("<tr class='data__body-row'>");
                        print("<td class='data__column'>" . $idNo++ . "</td>");
                        print("<td class='data__column'>" . $row["first_name"] . "</td>");
                        print("<td class='data__column'>" . $row["last_name"] . "</td>");
                        print("<td class='data__column'>" . $row["role"] . "</td>");
                        print("<td class='data__column'>" . $row["project_title"] . "</td>");
                        print("<td class='data__column'><a href='?action=delete&id=" . $row["employee_id"] . "'><button class='btn'>DELETE</button></a></td>");
                        print("</tr>");
                    }
                } else {
                    print("<tr class='data_body-row'>
                            <td class='data__column'></td>
                            <td class='data__column'></td>
                            <td class='data__column'></td>
                            <td class='data__column'></td>
                            <td class='data__column'></td>
                            <td class='data__column'></td>
                        </tr>");
                }
                ?>
            <tbody>
        </table>
    </main>

    <?php
    include "footer.php";

    mysqli_close($connection);
    ?>
</body>

</html>