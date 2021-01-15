<?php

function deleteEmployee($conn, $get) {
    $sql = "DELETE FROM employees WHERE employee_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $get);
    $stmt->execute();
    $stmt->close();
}

function deleteProject($conn, $get) {
    $sql = "DELETE FROM projects WHERE project_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $get);
    $stmt->execute();
    $stmt->close();
}

function selectEmployee($conn, $get) {
    $sql = "SELECT employees.employee_id, employees.first_name, employees.last_name, employees.role, employees.project_id, projects.project_title FROM employees
    LEFT JOIN projects ON employees.project_id = projects.project_id WHERE employee_id = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $get);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}

function selectProject($conn, $get) {
    $updateModal = "SELECT project_title FROM projects WHERE project_id = ?";
    $stmt = $conn->prepare($updateModal);
    $stmt->bind_param("i", $get);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

function updateEmployee($conn, $arr, $id) {
    $sql = "UPDATE employees SET first_name = ? , last_name = ?, role = ?, project_id = ? WHERE employee_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $arr["firstName"], $arr["lastName"], $arr["role"], $arr["selected"], $id);
    $stmt->execute();
    $stmt->close();
}

function updateProject($conn, $arr, $id) {
    $sql = "UPDATE projects SET project_title = ? WHERE project_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $arr["project"], $id);
    $stmt->execute();
    $stmt->close();
}

function insertEmployee($conn, $arr) {
    $sql = "INSERT INTO employees (first_name, last_name, role) VALUES (?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $arr["firstName"], $arr["lastName"], $arr["role"]);
    $stmt->execute();
    $stmt->close();
}

function insertProject($conn, $arr) {
    $sql = "INSERT INTO projects (project_title) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $arr["project"]);
    $stmt->execute();
    $stmt->close();
}