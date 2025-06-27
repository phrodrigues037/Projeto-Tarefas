<?php
// deletar.php
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    if (!empty($id)) {
        try {
            $stmt = $conn->prepare("DELETE FROM tarefas WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                header("Location: index.php"); // Redireciona de volta para a página principal
                exit();
            }
        } catch (PDOException $e) {
            echo "<script>alert('Erro ao excluir tarefa: " . $e->getMessage() . "'); window.location.href='index.php';</script>";
        }
    } else {
        echo "<script>alert('ID da tarefa não fornecido.'); window.location.href='index.php';</script>";
    }
} else {
    // Redireciona se a requisição não for POST
    header("Location: index.php");
    exit();
}
?>