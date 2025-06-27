<?php
// includes/db.php

$servername = "localhost"; // Geralmente 'localhost'
$username = "root";      // Seu usuário do MySQL (ex: 'root' para XAMPP/WAMP)
$password = "";          // Sua senha do MySQL (ex: vazio '' para XAMPP/WAMP)
$dbname = "todolist";    // Nome do banco de dados que criamos

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    // Configura o modo de erro do PDO para lançar exceções
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Define o modo de busca padrão para objetos
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

} catch (PDOException $e) {
    die("Falha na conexão com o banco de dados: " . $e->getMessage());
}
?>