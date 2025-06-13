<?php
include 'banco.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recebe os dados do formulário
    $item_id = $_POST['item_id'];
    $tipo_movimentacao = $_POST['tipo_movimentacao'];
    $quantidade = $_POST['quantidade'];
    $data_movimentacao = $_POST['data_movimentacao'];
    $usuario_id = $_POST['usuario_id'];

    // Insere os dados no banco
    $sql = "INSERT INTO movimentacoes_estoque (item_id, tipo_movimentacao, quantidade, data_movimentacao, usuario_id)
            VALUES ($item_id, '$tipo_movimentacao', $quantidade, '$data_movimentacao', $usuario_id)";

    if ($conn->query($sql) === TRUE) {
        echo "Movimentação inserida com sucesso!";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!-- Formulário para inserir dados -->
<link rel="stylesheet" href="style.css">

<div class="form-container">
    <form method="POST" action="inserir_movimentacoes.php">
        <h2>Registrar Movimentação</h2>
        <link rel="icon" href="img/favicon.ico" type="image/x-icon" />

        <label for="item_id">ID do Item:</label>
        <input type="number" id="item_id" name="item_id" required>

        <label for="tipo_movimentacao">Tipo de Movimentação:</label>
        <input type="text" id="tipo_movimentacao" name="tipo_movimentacao" required>

        <label for="quantidade">Quantidade:</label>
        <input type="number" id="quantidade" name="quantidade" required>

        <label for="data_movimentacao">Data da Movimentação:</label>
        <input type="date" id="data_movimentacao" name="data_movimentacao" required>

        <label for="usuario_id">ID do Usuário:</label>
        <input type="number" id="usuario_id" name="usuario_id" required>

        <input type="submit" value="Salvar Movimentação" class="btn">
    </form>
</div>

<div style="margin-top: 20px; text-align: center;">
    <a href="index.php" class="btn">Início</a>
</div>