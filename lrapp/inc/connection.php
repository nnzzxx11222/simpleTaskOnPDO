<?php 

$hostname = "localhost";
$dbname="lrapp";
$name="root";
$password="";
$charset= "utf8mb4";
$dsn="mysql:host=$hostname;dbname=$dbname;charset=$charset";
$option=[
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false 
];
try{
$pdo= new PDO($dsn,$name,$password,$option);
}catch(PDOException $e){

    die(print_r($e->getMessage()));
}