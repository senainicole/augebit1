<?php
include 'banco.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Verifica se o item existe
    $checkSql = "SELECT * FROM itens_estoque WHERE id = $id";
    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows > 0) {
        // Deleta o item
        $sql = "DELETE FROM itens_estoque WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            // Redireciona de volta ao painel após deletar
            header("Location: painel.php");
            exit();
        } else {
            echo "Erro ao excluir item: " . $conn->error;
        }
    } else {
        echo "Item não encontrado.";
    }
} else {
    echo "ID do item não foi especificado.";
}

$conn->close();
?>
