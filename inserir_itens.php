<?php
include 'banco.php';

// Se o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $tipo_item = $_POST['tipo_item'];
    $categoria = $_POST['categoria'];
    $quantidade = $_POST['quantidade'];
    $localizacao = $_POST['localizacao'];
    $observacoes = $_POST['observacoes'];
    $fornecedor_id = $_POST['fornecedor_id'];

    $sql = "INSERT INTO itens_estoque 
            (nome, preco, tipo_item, categoria, quantidade, localizacao, observacoes, fornecedor_id)
            VALUES 
            ('$nome', $preco, '$tipo_item', '$categoria', $quantidade, '$localizacao', '$observacoes', $fornecedor_id)";

    if ($conn->query($sql) === TRUE) {
        header("Location: painel.php");
        exit();
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}

// Consulta os fornecedores para preencher o select
$fornecedores = $conn->query("SELECT id, nome, email FROM fornecedores");

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Cadastrar Item</title>
    <link rel="icon" href="img/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="style.css" />
</head>
<body id="inserir-itens">

<!-- Logo no topo -->
<div class="topo-logo">
    <div class="logo-container">
        <img src="img/logoroxa.png" alt="" class="logo" />
    </div>
</div>

<!-- Formulário de cadastro de item -->
<div class="form-container">
    <form method="POST" action="inserir_itens.php">
        <h2>Cadastrar Item</h2>

        <label for="nome">Nome do Item:</label>
        <input type="text" id="nome" name="nome" required />

        <label for="preco">Preço (R$):</label>
        <input type="number" step="0.01" id="preco" name="preco" required />

        <label for="tipo_item">Tipo do Item:</label>
        <input type="text" id="tipo_item" name="tipo_item" required />

        <label for="categoria">Categoria:</label>
        <input type="text" id="categoria" name="categoria" required />

        <label for="quantidade">Quantidade:</label>
        <input type="number" id="quantidade" name="quantidade" required />

        <label for="localizacao">Localização:</label>
        <input type="text" id="localizacao" name="localizacao" required />

        <label for="observacoes">Observações:</label>
        <textarea id="observacoes" name="observacoes" rows="4" required></textarea>

        <label for="fornecedor_id">Fornecedor:</label>
        <select name="fornecedor_id" id="fornecedor_id" required>
            <option value="" disabled selected>Selecione um fornecedor</option>
            <?php while($f = $fornecedores->fetch_assoc()): ?>
                <option value="<?= $f['id'] ?>">
                    <?= htmlspecialchars($f['nome']) ?> (<?= htmlspecialchars($f['email']) ?>)
                </option>
            <?php endwhile; ?>
        </select>

        <!-- Botões -->
        <div class="form-buttons">
            <input type="submit" value="Salvar Item" class="btn" />
            <a href="index.php?dashboard=admin" class="btn">Voltar</a>
        </div>
    </form>
</div>

<!-- Link extra para o painel -->
<div style="margin-top: 20px; text-align: center;">
    <a href="painel.php" class="btn">Painel de Itens</a>
</div>

</body>
</html>
