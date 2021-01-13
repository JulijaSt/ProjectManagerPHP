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

$firstName = "";
$lastName = "";
$role = "";
$firstName_error = "";
$lastName_error = "";
$role_error = "";


if (isset($_GET["action"]) && $_GET["action"] == "update" && !isset($_POST["update"])) {
    $updateModal = "SELECT * FROM employees WHERE employee_id = ?";
    $stmt = $connection->prepare($updateModal);
    $stmt->bind_param("i", $_GET["id"]);
    $stmt->execute();
    $result = $stmt->get_result();

    if (mysqli_num_rows($result) == 1) {
        $row = $result->fetch_assoc();
        $firstName = $row["first_name"];
        $lastName = $row["last_name"];
        $role = $row["role"];
    }
}
if (isset($_POST["update"])) {
    $firstName = $_POST["first_name"];
    $lastName = $_POST["last_name"];
    $role = $_POST["role"];
    $id = $_GET["id"];
    if (empty($_POST["first_name"])) {
        $firstName_error = "Enter the first name";
    }
    if (empty($_POST["last_name"])) {
        $lastName_error = "Enter the last name";
    }
    if (empty($_POST["role"])) {
        $role_error = "Enter the role";
    }
    if (!empty($_POST["first_name"]) && !empty($_POST["last_name"]) && !empty($_POST["role"]) && !empty($_GET["id"])) {

        $sql = "UPDATE employees SET first_name = ? , last_name = ?, role = ? WHERE employee_id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("sssi", $firstName, $lastName, $role, $id);
        $stmt->execute();

        $stmt->close();
        mysqli_close($connection);

        header("Location: " . strtok($_SERVER["REQUEST_URI"], "?"));
        die();
    }
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
    include "header.php";
    ?>

    <section class="modal">
        <div class="modal__content">
            <h2 class="modal__title">Update employee information</h2>
            <form action="" method="POST" name="updateEmployee" class="form form--employee">
                <div class="form__twoInput-block">
                    <div class="form__left">
                        <label for="firstName" class="label">First name</label>
                        <input type="text" class="input" name="first_name" value="<?php print($firstName) ?>" placeholder="Update first name">
                        <span class="form__error"><?php echo $firstName_error; ?></span>
                    </div>
                    <div class="form__right">
                        <label for="lastName" class="label">Last name</label>
                        <input type="text" class="input" name="last_name" value="<?php print($lastName) ?>" placeholder="Update last name">
                        <span class="form__error"><?php echo $lastName_error; ?></span>
                    </div>
                </div>
                <div class="form__input-block">
                    <label for="role" class="label">Role</label>
                    <input type="text" class="input" name="role" value="<?php print($role) ?>" placeholder="Update role">
                    <span class="form__error"><?php echo $role_error; ?></span>
                </div>
                <input class="btn btn--update" type="submit" name="update" value="UPDATE" />
                <input class="btn" type="button" name="Close" value="CLOSE" onclick="closeModal()" />
            </form>
        </div>
    </section>

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
                        print("<td class='data__column'>
                                <a href='?action=delete&id=" . $row["employee_id"] . "' class='data__link' tabindex='-1'>
                                    <button class='btn' onclick='return confirm(\"Are you sure you want to delete this employee?\")'>DELETE</button>
                                </a>
                                <a href='?action=update&id=" . $row["employee_id"] . "' class='data__link' tabindex='-1'>
                                    <button class='btn btn--update'>UPDATE</button>
                                </a>
                        </td>");
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

    if (isset($_GET["action"]) and $_GET["action"] == "update") {
    ?>
        <script type="text/javascript" src="../ProjectManagerPHP/assets/js/modal.js"></script>
    <?php
    }

    mysqli_close($connection);
    ?>
</body>

</html>