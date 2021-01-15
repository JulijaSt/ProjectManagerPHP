<?php
include "connection.php";
include "sqlStatmentFunction.php";

if (isset($_GET["action"]) and $_GET["action"] == "delete") {
    deleteEmployee($connection, $_GET["id"]);

    mysqli_close($connection);
    header("Location: " . strtok($_SERVER["REQUEST_URI"], "?"));
    die();
}

$update_arr = array(
    "firstName" => "",
    "lastName" => "",
    "role" => "",
    "firstName_error" => "",
    "lastName_error" => "",
    "role_error" => "",
    "selected" => ""
);

if (isset($_GET["action"]) && $_GET["action"] == "update" && !isset($_POST["update"])) {
    $result = selectEmployee($connection, $_GET["id"]);

    if (mysqli_num_rows($result) == 1) {
        $row = $result->fetch_assoc();
        $update_arr["firstName"] = $row["first_name"];
        $update_arr["lastName"] = $row["last_name"];
        $update_arr["role"] = $row["role"];
        $update_arr["selected"] = $row["project_title"];
    }
}
if (isset($_POST["update"])) {
    $update_arr["firstName"] = $_POST["first_name"];
    $update_arr["lastName"] = $_POST["last_name"];
    $update_arr["role"] = $_POST["role"];
    $id = $_GET["id"];

    $update_arr["selected"] = $_POST["projects"];

    if ($update_arr["selected"] < 0) {
        $update_arr["selected"] = null;
    }
    if (empty($update_arr["firstName"])) {
        $update_arr["firstName_error"] = "Enter the first name";
    }
    if (empty($update_arr["lastName"])) {
        $update_arr["lastName_error"] = "Enter the last name";
    }
    if (empty($update_arr["role"])) {
        $update_arr["role_error"] = "Enter the role";
    }

    if (!empty($update_arr["firstName"]) && !empty($update_arr["lastName"]) && !empty($update_arr["role"]) && !empty($id)) {
        updateEmployee($connection, $update_arr, $id);

        mysqli_close($connection);
        header("Location: " . strtok($_SERVER["REQUEST_URI"], "?"));
        die();
    }
}

$add_arr = array(
    "firstName" => "",
    "lastName" => "",
    "role" => "",
    "firstName_error" => "",
    "lastName_error" => "",
    "role_error" => ""
);

if (isset($_POST["add"])) {
    $add_arr["firstName"] = $_POST["first_name"];
    $add_arr["lastName"] = $_POST["last_name"];
    $add_arr["role"] = $_POST["role"];

    if (empty($add_arr["firstName"])) {
        $add_arr["firstName_error"] = "Enter the first name";
    }
    if (empty($add_arr["lastName"])) {
        $add_arr["lastName_error"] = "Enter the last name";
    }
    if (empty($add_arr["role"])) {
        $add_arr["role_error"] = "Enter the role";
    }

    if (!empty($add_arr["firstName"]) && !empty($add_arr["lastName"]) && !empty($add_arr["role"])) {
        insertEmployee($connection, $add_arr);

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
                        <input type="text" class="input" name="first_name" value="<?php print($update_arr["firstName"]) ?>" placeholder="Update first name">
                        <span class="form__error"><?php echo $update_arr["firstName_error"]; ?></span>
                    </div>
                    <div class="form__right">
                        <label for="lastName" class="label">Last name</label>
                        <input type="text" class="input" name="last_name" value="<?php print($update_arr["lastName"]) ?>" placeholder="Update last name">
                        <span class="form__error"><?php echo $update_arr["lastName_error"]; ?></span>
                    </div>
                </div>
                <div class="form__input-block">
                    <label for="role" class="label">Role</label>
                    <input type="text" class="input" name="role" value="<?php print($update_arr["role"]) ?>" placeholder="Update role">
                    <span class="form__error"><?php echo $update_arr["role_error"]; ?></span>
                </div>
                <div class="form__input-block">
                    <label for="projects" class="label">Select project</label>
                    <select name="projects" id="projects" class="input">
                        <option class="select__option" value=<?php print(-1) ?> <?php if ($update_arr["selected"] == "") print("selected") ?>>No project</option>
                        <?php
                        $sql = "SELECT project_id, project_title FROM projects;";
                        $result = mysqli_query($connection, $sql);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                                <option class="select__option" value="<?php print($row["project_id"]) ?>" <?php if ($update_arr["selected"] == $row["project_title"]) print("selected") ?>> <?php print($row["project_title"]) ?></option>
                        <?php
                            }
                        }
                        ?>
                    </select>
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

        <form action="" method="POST" name="add-employee" class="form form--add">
            <div class="form__container">
                <div class="form__block">
                    <input type="text" class="input" name="first_name" value="<?php if (isset($add_arr["firstName"])) print($add_arr["firstName"]) ?>" placeholder="First name">
                    <span class="form__error"><?php echo $add_arr["firstName_error"]; ?></span>
                </div>
                <div class="form__block">
                    <input type="text" class="input" name="last_name" value="<?php if (isset($add_arr["lastName"])) print($add_arr["lastName"]) ?>" placeholder="Last name">
                    <span class="form__error"><?php echo $add_arr["lastName_error"]; ?></span>
                </div>
                <div class="form__block">
                    <input type="text" class="input" name="role" value="<?php if (isset($add_arr["role"])) print($add_arr["role"]) ?>" placeholder="Role">
                    <span class="form__error"><?php echo $add_arr["role_error"]; ?></span>
                </div>
            </div>
            <input class="btn btn--add" type="submit" name="add" value="ADD EMPLOYEE" />
        </form>
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