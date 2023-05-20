<?php
try {
    $dbh = new PDO('oci:dbname=localhost/XE;charset=UTF8', 'STUDENT', 'STUDENT');
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
