<?php
$this_page = $_SERVER['SCRIPT_NAME'];
$url = explode("/", $this_page);
$number = count($url) - 1;
?>

<header class="header">
    <nav class="header__nav">
        <ul class="header__list">
            <li class="header__list-item"><a href="index.php" class="header__link <?php if ("index.php" == $url[$number]) echo "header__link--active" ?>">Projects</a></li>
            <li class="header__list-item"><a href="employees.php" class="header__link <?php if ("employees.php" == $url[$number]) echo "header__link--active" ?>">Employees</a></li>
        </ul>
    </nav>
    <h1 class="header__title">Project Manager System</h1>
</header>