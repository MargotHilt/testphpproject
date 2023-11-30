<?php
require '../vendor/autoload.php';
require './MyHandler.php';

session_start();
if (isset($_SESSION['userId']) && is_numeric($_SESSION['userId']) > 0){
    header('Location: dashboard.php');
    exit();
}


if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['password'])) {

    $firstName = MyHandler::handleServerRequest('post', 'first_name');
    $lastName = MyHandler::handleServerRequest('post', 'last_name');
    $email = MyHandler::handleServerRequest('post', 'email');;
    $password = password_hash(MyHandler::handleServerRequest('post', 'password'), PASSWORD_BCRYPT);


    $pdo = new PDO('mysql:host=mysql_db;dbname=kaboom', 'root', 'root');
#username and password from db in pdo method.

    $sql = 'INSERT INTO 
            user (email, password, first_name, last_name) 
        VALUES 
            (:email, :password, :first_name, :last_name)';

    $statement = $pdo->prepare($sql);

    $statement->bindParam(':email', $email);
    $statement->bindParam(':password', $password);
    $statement->bindParam(':first_name', $firstName);
    $statement->bindParam(':last_name', $lastName);

    $statement->execute();

    $userId = $pdo->lastInsertId();
}

$loader = new \Twig\Loader\FilesystemLoader('../src/User/Templates');
$twig = new \Twig\Environment($loader, [
    'cache' => false,
]);

echo $twig->render('register.twig', ['first_name' => 'first_name']);



