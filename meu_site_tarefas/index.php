<?php
// index.php
require_once 'includes/db.php';

// ADICIONAR TAREFA (CREATE)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_task'])) {
    $titulo = trim($_POST['titulo']);
    $descricao = trim($_POST['descricao']);

    if (!empty($titulo)) {
        try {
            $stmt = $conn->prepare("INSERT INTO tarefas (titulo, descricao) VALUES (:titulo, :descricao)");
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':descricao', $descricao);
            if ($stmt->execute()) {
                // Redireciona para evitar reenvio do formulário ao recarregar a página
                header("Location: index.php");
                exit();
            }
        } catch (PDOException $e) {
            echo "<script>alert('Erro ao adicionar tarefa: " . $e->getMessage() . "');</script>";
        }
    } else {
        echo "<script>alert('O título da tarefa não pode ser vazio.');</script>";
    }
}

// ATUALIZAR STATUS DA TAREFA (UPDATE - concluída) - Via requisição AJAX (fetch)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_status'])) {
    $id = $_POST['task_id'];
    // Garante que o valor seja booleano e depois convertido para INT (1 ou 0)
    $concluida = filter_var($_POST['concluida'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;

    try {
        $stmt = $conn->prepare("UPDATE tarefas SET concluida = :concluida WHERE id = :id");
        $stmt->bindParam(':concluida', $concluida, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        echo "Status atualizado com sucesso."; // Resposta para a requisição AJAX
        exit(); // Importante para não renderizar o HTML completo para uma requisição AJAX
    } catch (PDOException $e) {
        http_response_code(500); // Envia um código de erro HTTP
        echo "Erro ao atualizar status: " . $e->getMessage();
        exit();
    }
}

// LISTAR TAREFAS (READ)
try {
    $stmt = $conn->query("SELECT * FROM tarefas ORDER BY data_criacao DESC");
    $tarefas = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "<p style='color: red; text-align: center;'>Erro ao carregar tarefas: " . $e->getMessage() . "</p>";
    $tarefas = []; // Garante que $tarefas seja um array vazio em caso de erro
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Tarefas</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Minhas Tarefas</h1>

        <form class="add-task-form" action="index.php" method="POST">
            <input type="text" name="titulo" placeholder="Título da Tarefa" required>
            <textarea name="descricao" placeholder="Descrição (opcional)"></textarea>
            <button type="submit" name="add_task">Adicionar Tarefa</button>
        </form>

        <ul class="task-list" id="taskList">
            <?php if (empty($tarefas)): ?>
                <p style="text-align: center; color: #888;">Nenhuma tarefa encontrada. Adicione uma nova!</p>
            <?php else: ?>
                <?php foreach ($tarefas as $tarefa): ?>
                    <li class="task-item <?php echo $tarefa->concluida ? 'completed' : ''; ?>">
                        <span class="task-title">
                            <input type="checkbox" class="task-checkbox" data-id="<?php echo $tarefa->id; ?>" <?php echo $tarefa->concluida ? 'checked' : ''; ?>>
                            <?php echo htmlspecialchars($tarefa->titulo); ?>
                        </span>
                        <?php if (!empty($tarefa->descricao)): ?>
                            <span class="task-description"><?php echo nl2br(htmlspecialchars($tarefa->descricao)); ?></span>
                        <?php endif; ?>
                        <div class="task-actions">
                            <button class="edit-btn" data-id="<?php echo $tarefa->id; ?>">Editar</button>
                            <form action="deletar.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $tarefa->id; ?>">
                                <button type="submit" class="delete-btn" onclick="return confirm('Tem certeza que deseja excluir esta tarefa?');">Excluir</button>
                            </form>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>

    <div id="editTaskModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h2>Editar Tarefa</h2>
            <form id="editTaskForm" action="editar.php" method="POST">
                <input type="hidden" id="editTaskId" name="id">
                <label for="editTaskTitle">Título:</label>
                <input type="text" id="editTaskTitle" name="titulo" required>
                <label for="editTaskDescription">Descrição:</label>
                <textarea id="editTaskDescription" name="descricao"></textarea>
                <button type="submit">Salvar Alterações</button>
            </form>
        </div>
    </div>

    <script src="js/script.js"></script>
    <script>
        // Lógica para toggle do checkbox (para evitar recarregamento da página)
        document.querySelectorAll('.task-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const taskId = this.dataset.id;
                const isChecked = this.checked;

                fetch('index.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `toggle_status=true&task_id=${taskId}&concluida=${isChecked}`
                })
                .then(response => {
                    if (response.ok) {
                        const taskItem = this.closest('.task-item');
                        if (isChecked) {
                            taskItem.classList.add('completed');
                        } else {
                            taskItem.classList.remove('completed');
                        }
                    } else {
                        // Tratar erros, por exemplo, exibir uma mensagem para o usuário
                        alert('Erro ao atualizar status da tarefa.');
                        // Opcional: Reverter o estado do checkbox se a atualização falhar
                        this.checked = !isChecked;
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro de rede ao atualizar status da tarefa.');
                    this.checked = !isChecked; // Reverte o estado do checkbox em caso de erro de rede
                });
            });
        });
    </script>
</body>
</html>