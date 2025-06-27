-- Cria o banco de dados 'todolist' se ele não existir
CREATE DATABASE IF NOT EXISTS todolist;

-- Seleciona o banco de dados 'todolist' para as operações seguintes
USE todolist;

-- Cria a tabela 'tarefas' se ela não existir
-- Inclui as colunas id, titulo, descricao, data_criacao e concluida
CREATE TABLE IF NOT EXISTS tarefas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    concluida BOOLEAN DEFAULT FALSE
);

-- Adiciona a coluna 'icone' à tabela 'tarefas' se ela ainda não existir
-- Verifica se a coluna não existe antes de adicioná-la para evitar erros em execuções repetidas
-- Esta sintaxe com IF NOT EXISTS para colunas é específica do MySQL 8.0.13+
-- Se você estiver usando uma versão mais antiga, pode precisar remover o "IF NOT EXISTS"
-- e rodar o ALTER TABLE apenas na primeira vez, ou usar um script mais robusto para isso.
ALTER TABLE tarefas
ADD COLUMN IF NOT EXISTS icone VARCHAR(50) DEFAULT NULL;

-- Exemplo de dados para teste (opcional)
-- Descomente as linhas abaixo se quiser inserir algumas tarefas de exemplo
/*
INSERT INTO tarefas (titulo, descricao, icone, concluida) VALUES
('Fazer compras no supermercado', 'Comprar leite, pão, ovos e frutas.', 'fas fa-shopping-cart', FALSE),
('Revisar código do projeto X', 'Verificar as funcionalidades de login e cadastro.', 'fas fa-briefcase', FALSE),
('Estudar para a prova de história', 'Capítulo 5 e 6 sobre a Segunda Guerra Mundial.', 'fas fa-graduation-cap', FALSE),
('Ir à academia', 'Treino de pernas e cárdio.', 'fas fa-dumbbell', TRUE),
('Pagar contas online', 'Água, luz e internet.', 'fas fa-home', FALSE);
*/
