<?php
include 'banco.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recebe os dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Insere os dados no banco
    $sql = "INSERT INTO usuarios (nome, email, senha)
            VALUES ('$nome', '$email', '$senha')";

    if ($conn->query($sql) === TRUE) {
        echo "Novo usuário inserido com sucesso!";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!-- Formulário para inserir dados -->
<link rel="stylesheet" href="style.css">

<div class="form-container">
    <form method="POST" action="inserir_usuarios.php">
        <h2>Cadastrar Usuário</h2>

        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>

        <input type="submit" value="Salvar Usuário" class="btn">
    </form>
</div>

<div style="margin-top: 20px; text-align: center;">
    <a href="index.php" class="btn">Início</a>
</div>
