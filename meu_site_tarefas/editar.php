<?php
// editar.php
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $titulo = trim($_POST['titulo']);
    $descricao = trim($_POST['descricao']);

    if (!empty($id) && !empty($titulo)) {
        try {
            $stmt = $conn->prepare("UPDATE tarefas SET titulo = :titulo, descricao = :descricao WHERE id = :id");
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                header("Location: index.php"); // Redireciona de volta para a página principal
                exit();
            }
        } catch (PDOException $e) {
            echo "<script>alert('Erro ao atualizar tarefa: " . $e->getMessage() . "'); window.location.href='index.php';</script>";
        }
    } else {
        echo "<script>alert('O ID e o título da tarefa não podem ser vazios.'); window.location.href='index.php';</script>";
    }
} else {
    // Redireciona se a requisição não for POST
    header("Location: index.php");
    exit();
}
?>