<?php
include 'banco.php';

$msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Escapa os dados para evitar SQL Injection
    $nome = $conn->real_escape_string($_POST['nome']);
    $responsavel = $conn->real_escape_string($_POST['responsavel']);
    $email = $conn->real_escape_string($_POST['email']);
    $telefone = $conn->real_escape_string($_POST['telefone']);
    $endereco = $conn->real_escape_string($_POST['endereco']);
    $tipo_produto = $conn->real_escape_string($_POST['tipo_produto']);

    // Insere os dados no banco
    $sql = "INSERT INTO fornecedores (nome, responsavel, email, telefone, endereco, tipo_produto)
            VALUES ('$nome', '$responsavel', '$email', '$telefone', '$endereco', '$tipo_produto')";

    if ($conn->query($sql) === TRUE) {
        $msg = "Novo fornecedor inserido com sucesso!";
    } else {
        $msg = "Erro ao inserir fornecedor: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Inserir Fornecedor</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body id="inserir-fornecedor">

    <div class="form-container">
        <h2>Cadastrar Fornecedor</h2>

        <?php if($msg): ?>
            <p style="padding:10px; background-color: #d4edda; color: #155724; border-radius: 6px;"><?= $msg ?></p>
        <?php endif; ?>

        <form method="POST" action="inserir_fornecedores.php">
            <label for="nome">Fornecedor:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="responsavel">Responsável:</label>
            <input type="text" id="responsavel" name="responsavel" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="telefone">Telefone:</label>
            <input type="tel" id="telefone" name="telefone" required>

            <label for="endereco">Endereço:</label>
            <input type="text" id="endereco" name="endereco" required>

            <label for="tipo_produto">Tipo de Produto:</label>
            <input type="text" id="tipo_produto" name="tipo_produto" required>

            <input type="submit" value="Salvar Fornecedor" class="btn">
        </form>

        <a href="index.php" class="btn" style="margin-top: 20px; display: inline-block;">Voltar</a>
    </div>

</body>
</html>

<?php
$conn->close();
?>
