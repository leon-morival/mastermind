<?php

$con = mysqli_connect('localhost', 'root', '', 'mastermind');

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}