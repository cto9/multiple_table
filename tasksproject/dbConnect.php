<?php

$host = "localhost";
$user = "root";
$password = "root";
$database = "tasksproject";

try {
    $db = new PDO(sprintf('mysql:host=%s;dbname=%s', $host, $database), $user, $password);
} catch (PDOException $e) {
    echo '<span class="error_message">Error!: ' . $e->getMessage() . "<br/>";
    die();
}