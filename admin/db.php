<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $host = 'localhost';
    $user = 'root'; 
    $pass = '';     
    $dbname = 'store';
    $conn = new mysqli($host, $user, $pass, $dbname);
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    error_log("Database error: " . $e->getMessage());
    die("We're experiencing technical difficulties. Please try again later.");
}
?>