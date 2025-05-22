<?php
include 'banco.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

$action = $_POST['action'] ?? '';

try {
    switch ($action) {
        case 'add':
            $titulo = $_POST['titulo'] ?? '';
            $data_evento = $_POST['data_evento'] ?? '';
            $hora = $_POST['hora'] ?? '';
            $descricao = $_POST['descricao'] ?? '';

            if (empty($titulo) || empty($data_evento) || empty($hora)) {
                throw new Exception('Título, data e hora são obrigatórios');
            }

            $sql = "INSERT INTO eventos_calendario (titulo, data_evento, hora, descricao, criado_em) 
                    VALUES (?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $titulo, $data_evento, $hora, $descricao);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Evento adicionado com sucesso']);
            } else {
                throw new Exception('Erro ao adicionar evento');
            }
            break;

        case 'edit':
            $id = $_POST['id'] ?? '';
            $titulo = $_POST['titulo'] ?? '';
            $data_evento = $_POST['data_evento'] ?? '';
            $hora = $_POST['hora'] ?? '';
            $descricao = $_POST['descricao'] ?? '';

            if (empty($id) || empty($titulo) || empty($data_evento) || empty($hora)) {
                throw new Exception('ID, título, data e hora são obrigatórios');
            }

            $sql = "UPDATE eventos_calendario 
                    SET titulo = ?, data_evento = ?, hora = ?, descricao = ? 
                    WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $titulo, $data_evento, $hora, $descricao, $id);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo json_encode(['success' => true, 'message' => 'Evento atualizado com sucesso']);
                } else {
                    throw new Exception('Evento não encontrado');
                }
            } else {
                throw new Exception('Erro ao atualizar evento');
            }
            break;

        case 'delete':
            $id = $_POST['id'] ?? '';

            if (empty($id)) {
                throw new Exception('ID do evento é obrigatório');
            }

            $sql = "DELETE FROM eventos_calendario WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo json_encode(['success' => true, 'message' => 'Evento excluído com sucesso']);
                } else {
                    throw new Exception('Evento não encontrado');
                }
            } else {
                throw new Exception('Erro ao excluir evento');
            }
            break;

        default:
            throw new Exception('Ação não reconhecida');
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
}
?>