<?php
include 'banco.php';

function exibirTabela($conn, $nomeTabela, $titulo) {
    echo "<h2>$titulo</h2>";
    $sql = "SELECT * FROM $nomeTabela";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        echo "<table border='1' cellpadding='8' cellspacing='0'>";
        
        // Cabeçalho com nomes das colunas
        $campos = array_keys($result->fetch_assoc());
        echo "<tr>";
        foreach ($campos as $campo) {
            echo "<th>$campo</th>";
        }
        echo "</tr>";

        // Voltar o ponteiro do result e exibir os dados
        $result->data_seek(0);
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($row as $valor) {
                echo "<td>" . htmlspecialchars($valor) . "</td>";
            }
            echo "</tr>";
        }

        echo "</table><br>";
    } else {
        echo "<p>Nenhum dado encontrado na tabela <strong>$nomeTabela</strong>.</p><br>";
    }
}

// Executa a função para cada tabela
exibirTabela($conn, 'itens_estoque', 'Itens de Estoque');
exibirTabela($conn, 'movimentacoes_estoque', 'Movimentações de Estoque');


$conn->close();
?>
