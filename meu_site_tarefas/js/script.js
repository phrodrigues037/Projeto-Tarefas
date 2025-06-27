// js/script.js

document.addEventListener('DOMContentLoaded', () => {
    const editModal = document.getElementById('editTaskModal');
    const closeButton = document.querySelector('.close-button');
    const taskList = document.getElementById('taskList');
    // const editForm = document.getElementById('editTaskForm'); // Não necessário para este JS
    const editTaskId = document.getElementById('editTaskId');
    const editTaskTitle = document.getElementById('editTaskTitle');
    const editTaskDescription = document.getElementById('editTaskDescription');

    // Função para abrir o modal de edição
    taskList.addEventListener('click', (event) => {
        if (event.target.classList.contains('edit-btn')) {
            const taskId = event.target.dataset.id;
            const taskItem = event.target.closest('.task-item');
            // Remove o "[ ] " ou "[x] " do início do título antes de pegar o texto
            const taskTitle = taskItem.querySelector('.task-title').textContent.trim().replace(/^\s*\[\s*\]\s*|\s*\[\s*x\s*\]\s*/, '');
            const taskDescription = taskItem.querySelector('.task-description').textContent.trim();

            editTaskId.value = taskId;
            editTaskTitle.value = taskTitle;
            editTaskDescription.value = taskDescription;
            editModal.style.display = 'flex'; // Exibe o modal como flex para centralizar
        }
    });

    // Função para fechar o modal
    closeButton.addEventListener('click', () => {
        editModal.style.display = 'none';
    });

    // Fechar o modal clicando fora dele
    window.addEventListener('click', (event) => {
        if (event.target === editModal) {
            editModal.style.display = 'none';
        }
    });

    // A submissão do formulário de edição é tratada diretamente pelo PHP (editar.php)
    // e o toggle do checkbox é feito via fetch API no index.php
});