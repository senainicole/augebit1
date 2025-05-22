<?php
include 'banco.php';

if (!isset($_GET['id'])) {
    header("Location: painel.php");
    exit;
}

$id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Atualizar dados
    $nome = $conn->real_escape_string($_POST['nome']);
    $tipo_item = $conn->real_escape_string($_POST['tipo_item']);
    $quantidade = intval($_POST['quantidade']);
    $localizacao = $conn->real_escape_string($_POST['localizacao']);
    $observacoes = $conn->real_escape_string($_POST['observacoes']);

    $sql = "UPDATE itens_estoque SET
            nome = '$nome',
            tipo_item = '$tipo_item',
            quantidade = $quantidade,
            localizacao = '$localizacao',
            observacoes = '$observacoes'
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: painel.php");
        exit;
    } else {
        echo "Erro ao atualizar: " . $conn->error;
    }
} else {
    // Buscar dados atuais
    $sql = "SELECT * FROM itens_estoque WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows != 1) {
        echo "Item não encontrado.";
        exit;
    }

    $item = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Editar Item</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        .form-container {
            max-width: 500px;
            margin: 40px auto;
            background: #f4f4f4;
            padding: 20px;
            border-radius: 10px;
        }

        label {
            display: block;
            margin-top: 15px;
        }

        input, textarea {
            width: 100%;
            padding: 8px;
            margin-top: 6px;
            border-radius: 6px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        .btn {
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #5a67d8;
            border: none;
            color: white;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #434190;
        }

        .btn-voltar {
            background-color: #888;
            text-decoration: none;
            display: inline-block;
            padding: 10px 15px;
            margin-top: 10px;
            border-radius: 6px;
            color: white;
        }

        .btn-voltar:hover {
            background-color: #555;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Editar Item</h2>
    <form method="POST" action="">
        <label for="nome">Nome do Item:</label>
        <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($item['nome']) ?>" required>

        <label for="tipo_item">Tipo do Item:</label>
        <input type="text" id="tipo_item" name="tipo_item" value="<?= htmlspecialchars($item['tipo_item']) ?>" required>

        <label for="quantidade">Quantidade:</label>
        <input type="number" id="quantidade" name="quantidade" value="<?= (int)$item['quantidade'] ?>" required>

        <label for="localizacao">Localização:</label>
        <input type="text" id="localizacao" name="localizacao" value="<?= htmlspecialchars($item['localizacao']) ?>" required>

        <label for="observacoes">Observações:</label>
        <textarea id="observacoes" name="observacoes" rows="4" required><?= htmlspecialchars($item['observacoes']) ?></textarea>

        <button type="submit" class="btn">Atualizar Item</button>
    </form>

    <a href="painel.php" class="btn-voltar">Voltar ao Painel</a>
</div>

</body>
</html>
