<?php
$servername = "localhost";
$username = "root";
$password = "mysql";
$dbname = "project_manager_php";

$connection = mysqli_connect($servername, $username, $password, $dbname);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET["action"]) && $_GET["action"] == "delete") {
    $sql = "DELETE FROM projects WHERE project_id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $_GET["id"]);
    $res = $stmt->execute();

    $stmt->close();
    mysqli_close($connection);

    header("Location: " . strtok($_SERVER["REQUEST_URI"], "?"));
    die();
}

$project = "";
$project_error = "";

if (isset($_GET["action"]) and $_GET["action"] == "update") {
    $updateModal = "SELECT project_title FROM projects WHERE project_id = ?";
    $stmt = $connection->prepare($updateModal);
    $stmt->bind_param("i", $_GET["id"]);
    $stmt->execute();
    $result = $stmt->get_result();

    if (mysqli_num_rows($result) == 1) {
        $row = $result->fetch_assoc();
        $project = $row["project_title"];
    }
}

if (isset($_POST["update"])) {
    $project = $_POST["project_title"];
    $id = $_GET["id"];

    if (empty($_POST["project_title"])) {
        $project_error = "Enter the project title";
    }

    if (!empty($_POST["project_title"]) && !empty($_GET["id"])) {
        $sql = "UPDATE projects SET project_title = ? WHERE project_id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("si", $project, $id);
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
    include "header.php"
    ?>

    <section class="modal">
        <div class="modal__content">
            <h2 class="modal__title">Update project information</h2>
            <form action="" method="POST" name="updateEmployee" class="form form--project">
                <div class="form__input-block">
                    <label for="project_title" class="label">Project title</label>
                    <input type="text" class="input" name="project_title" value="<?php print($project) ?>" placeholder="Update project title">
                    <span class="form__error"><?php echo $project_error; ?></span>
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
                    <th class="data__column data__column--head">Project</th>
                    <th class="data__column data__column--head">Employees</th>
                    <th class="data__column data__column--head">Action</th>
                </tr>
            </thead>
            <tbody class="data__body">
                <?php
                $sql = "SELECT projects.project_id, projects.project_title, GROUP_CONCAT(CONCAT_WS('', employees.first_name) SEPARATOR ', ') as employees FROM projects
                LEFT JOIN employees ON projects.project_id = employees.project_id
                GROUP BY projects.project_id;";
                $result = mysqli_query($connection, $sql);
                $idNo = 1;
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        print("<tr class='data__body-row'>");
                        print("<td class='data__column'>" . $idNo++ . "</td>");
                        print("<td class='data__column'>" . $row["project_title"] . "</td>");
                        print("<td class='data__column'>" . $row["employees"] . "</td>");
                        print("<td class='data__column'>
                            <a href='?action=delete&id=" . $row["project_id"] . "' class='data__link' tabindex='-1'>
                                <button class='btn' onclick='return confirm(\"Are you sure you want to delete this project?\")'>DELETE</button>
                            </a>
                            <a href='?action=update&id=" . $row["project_id"] . "' class='data__link' tabindex='-1'>
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