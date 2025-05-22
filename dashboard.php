<?php
include 'banco.php';

// Consulta para dados dos gráficos de categoria
$sql = "SELECT tipo_item, SUM(quantidade) AS total_quantidade 
        FROM itens_estoque 
        GROUP BY tipo_item 
        ORDER BY tipo_item ASC";

$result = $conn->query($sql);

$tipos = [];
$quantidades = [];

while ($row = $result->fetch_assoc()) {
    $tipos[] = $row['tipo_item'];
    $quantidades[] = (int)$row['total_quantidade'];
}

// CORRIGIDO: Query de fornecedores para evitar duplicação
$fornecedores_sql = "SELECT f.nome, 
                            MIN(f.email) as email, 
                            COUNT(ie.id) as produtos 
                     FROM fornecedores f 
                     LEFT JOIN itens_estoque ie ON f.id = ie.fornecedor_id 
                     WHERE f.nome != 'soffia' AND f.nome != 'Soffia'
                     GROUP BY f.nome 
                     ORDER BY produtos DESC";
$fornecedores_result = $conn->query($fornecedores_sql);

// Dados para cards de estatísticas (baseado nos dados reais)
$total_itens = $conn->query("SELECT SUM(quantidade) as total FROM itens_estoque")->fetch_assoc()['total'] ?? 0;
$total_fornecedores = $conn->query("SELECT COUNT(DISTINCT nome) as total FROM fornecedores WHERE nome != 'soffia' AND nome != 'Soffia'")->fetch_assoc()['total'] ?? 0;
$total_usuarios = $conn->query("SELECT COUNT(*) as total FROM usuarios")->fetch_assoc()['total'] ?? 0;

// Pendências - produtos com estoque baixo
$pendencias_sql = "SELECT nome, quantidade, 
                   CASE 
                       WHEN quantidade < 5 THEN 'Estoque crítico'
                       WHEN quantidade < 10 THEN 'Estoque baixo'
                       ELSE 'Estoque mínimo'
                   END as ocorrencia
                   FROM itens_estoque 
                   WHERE quantidade < 15 
                   ORDER BY quantidade ASC 
                   LIMIT 5";
$pendencias_result = $conn->query($pendencias_sql);

// Eventos do calendário - agora do banco de dados
$eventos_sql = "SELECT id, titulo, data_evento, hora, descricao 
                FROM eventos_calendario 
                WHERE data_evento >= CURDATE() 
                ORDER BY data_evento ASC, hora ASC 
                LIMIT 10";
$eventos_result = $conn->query($eventos_sql);

$eventos = [];
if ($eventos_result && $eventos_result->num_rows > 0) {
    while ($row = $eventos_result->fetch_assoc()) {
        $data = new DateTime($row['data_evento']);
        $eventos[] = [
            'id' => $row['id'],
            'data' => $data->format('d'),
            'mes' => $data->format('D'),
            'evento' => $row['titulo'],
            'hora' => substr($row['hora'], 0, 5),
            'descricao' => $row['descricao']
        ];
    }
} else {
    // Eventos padrão se não houver no banco
    $eventos = [
        ['id' => null, 'data' => '16', 'mes' => 'Qui', 'evento' => 'Remessa Cisco', 'hora' => '15:20', 'descricao' => ''],
        ['id' => null, 'data' => '17', 'mes' => 'Sex', 'evento' => 'Reunião estoque', 'hora' => '9:00', 'descricao' => ''],
    ];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Augebit</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root[data-theme="dark"] {
            --bg-gradient: linear-gradient(135deg, #555586 0%, #764ba2 100%);
            --card-bg: rgba(255, 255, 255, 0.1);
            --text-color: white;
            --chart-bg: rgba(255, 255, 255, 0.95);
            --chart-text: #333;
        }

        :root[data-theme="light"] {
            --bg-gradient: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            --card-bg: rgba(255, 255, 255, 0.8);
            --text-color: #333;
            --chart-bg: rgba(255, 255, 255, 0.95);
            --chart-text: #333;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--bg-gradient);
            min-height: 100vh;
            color: var(--text-color);
            transition: all 0.3s ease;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 0 10px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 24px;
            font-weight: bold;
        }

        .logo::before {
            content: "⚡";
            font-size: 28px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .theme-toggle {
            background: none;
            border: none;
            color: var(--text-color);
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 15px;
            transition: background 0.3s ease;
            font-size: 14px;
        }

        .theme-toggle:hover {
            background: var(--card-bg);
        }

        .theme-toggle.active {
            background: var(--card-bg);
            font-weight: bold;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #667eea;
            font-weight: bold;
        }

        .welcome-section {
            margin-bottom: 30px;
        }

        .welcome-section h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .welcome-section p {
            opacity: 0.9;
            font-size: 16px;
        }

        .stats-cards {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            justify-content: flex-end;
            align-items: center;
        }

        .stat-card {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            min-width: 120px;
        }

        .stat-number {
            font-size: 36px;
            font-weight: bold;
            display: block;
        }

        .stat-label {
            font-size: 14px;
            opacity: 0.8;
            margin-top: 5px;
        }

        .add-to-list-btn {
            background: var(--card-bg);
            border: none;
            color: var(--text-color);
            padding: 12px 20px;
            border-radius: 20px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .add-to-list-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .main-content {
            display: grid;
            grid-template-columns: 300px 1fr 350px;
            gap: 30px;
            margin-bottom: 30px;
        }

        .sidebar-left {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .suppliers-section {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
        }

        .suppliers-section h3 {
            margin-bottom: 20px;
            font-size: 18px;
        }

        .supplier-item {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            padding: 10px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        .supplier-logo {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .supplier-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .supplier-info {
            flex: 1;
        }

        .supplier-name {
            font-weight: bold;
            margin-bottom: 2px;
        }

        .supplier-products {
            font-size: 12px;
            opacity: 0.8;
        }

        .contact-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: var(--text-color);
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .contact-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .charts-section {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .chart-container {
            background: var(--chart-bg);
            border-radius: 15px;
            padding: 20px;
            color: var(--chart-text);
        }

        .chart-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
            color: var(--chart-text);
        }

        canvas {
            max-height: 300px !important;
        }

        .sidebar-right {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .pendencies-section, .calendar-section {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
        }

        .view-details {
            font-size: 12px;
            opacity: 0.8;
            cursor: pointer;
            transition: opacity 0.3s ease;
        }

        .view-details:hover {
            opacity: 1;
        }

        .pendency-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .pendency-info {
            flex: 1;
        }

        .pendency-name {
            font-weight: 500;
            margin-bottom: 2px;
            font-size: 14px;
        }

        .pendency-issue {
            font-size: 12px;
            opacity: 0.8;
        }

        .pendency-quantity {
            font-weight: bold;
            font-size: 16px;
        }

        .calendar-event {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .event-date {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 40px;
        }

        .event-day {
            font-size: 18px;
            font-weight: bold;
        }

        .event-weekday {
            font-size: 12px;
            opacity: 0.8;
        }

        .event-info {
            flex: 1;
        }

        .event-name {
            font-weight: 500;
            margin-bottom: 2px;
        }

        .event-time {
            font-size: 12px;
            opacity: 0.8;
        }

        .event-description {
            font-size: 11px;
            opacity: 0.7;
            margin-top: 2px;
            font-style: italic;
        }

        .event-actions {
            display: flex;
            gap: 5px;
        }

        .edit-event-btn, .delete-event-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: background 0.2s ease;
            font-size: 12px;
        }

        .edit-event-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .delete-event-btn:hover {
            background: rgba(255, 0, 0, 0.2);
        }

        .add-event-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: var(--text-color);
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .add-event-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .no-events {
            text-align: center;
            padding: 20px;
            opacity: 0.7;
        }

        .no-events p {
            margin-bottom: 5px;
        }

        .calendar-event {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            padding: 8px;
            border-radius: 8px;
            transition: background 0.2s ease;
        }

        .calendar-event:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .success-message, .error-message {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: bold;
            z-index: 2000;
            animation: slideIn 0.3s ease;
        }

        .success-message {
            background: #4CAF50;
            color: white;
        }

        .error-message {
            background: #f44336;
            color: white;
        }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .btn-back {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border: none;
            color: var(--text-color);
            padding: 15px 20px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        /* Modal para adicionar à lista */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border-radius: 15px;
            width: 80%;
            max-width: 500px;
            color: #333;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: black;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .btn-primary {
            background-color: #667eea;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-primary:hover {
            background-color: #434190;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-secondary:hover {
            background-color: #545b62;
        }

        @media (max-width: 1200px) {
            .main-content {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .stats-cards {
                justify-content: center;
                flex-wrap: wrap;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <div class="logo">AUGEBIT</div>
            <div class="user-info">
                <button class="theme-toggle" onclick="toggleTheme('dark')" id="darkBtn">Escuro</button>
                <button class="theme-toggle active" onclick="toggleTheme('light')" id="lightBtn">Claro</button>
                <div class="user-avatar">S</div>
            </div>
        </header>

        <div class="welcome-section">
            <h1>Olá, Soffia!</h1>
            <p>Dê uma olhada na atividade de estoque da Augebit. Confira o Dashboard!</p>
        </div>

        <div class="stats-cards">
            <div class="stat-card">
                <span class="stat-number"><?= $total_itens ?></span>
                <div class="stat-label">Total Itens</div>
            </div>
            <div class="stat-card">
                <span class="stat-number"><?= $total_fornecedores ?></span>
                <div class="stat-label">Fornecedores</div>
            </div>
            <div class="stat-card">
                <span class="stat-number"><?= $total_usuarios ?></span>
                <div class="stat-label">Usuários</div>
            </div>
            <button class="add-to-list-btn" onclick="openModal()">+ Adicionar à lista</button>
        </div>

        <div class="main-content">
            <div class="sidebar-left">
                <div class="suppliers-section">
                    <h3>Fornecedores em destaque</h3>
                    <?php if ($fornecedores_result->num_rows > 0): ?>
                        <?php while ($fornecedor = $fornecedores_result->fetch_assoc()): 
                            // Buscar imagem do fornecedor
                            $nomeArquivo = strtolower(str_replace(' ', '_', $fornecedor['nome']));
                            $extensoes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                            $caminhoImagem = '';

                            foreach ($extensoes as $ext) {
                                $arquivoTeste = "img/{$nomeArquivo}.{$ext}";
                                if (file_exists($arquivoTeste)) {
                                    $caminhoImagem = $arquivoTeste;
                                    break;
                                }
                            }
                        ?>
                            <div class="supplier-item">
                                <div class="supplier-logo">
                                    <?php if ($caminhoImagem): ?>
                                        <img src="<?= $caminhoImagem ?>" alt="<?= htmlspecialchars($fornecedor['nome']) ?>">
                                    <?php else: ?>
                                        <?= strtoupper(substr($fornecedor['nome'], 0, 1)) ?>
                                    <?php endif; ?>
                                </div>
                                <div class="supplier-info">
                                    <div class="supplier-name"><?= htmlspecialchars($fornecedor['nome']) ?></div>
                                    <div class="supplier-products"><?= $fornecedor['produtos'] ?> produtos</div>
                                </div>
                                <button class="contact-btn" onclick="contactSupplier('<?= htmlspecialchars($fornecedor['email']) ?>')">Contato</button>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="supplier-item">
                            <div class="supplier-logo">📦</div>
                            <div class="supplier-info">
                                <div class="supplier-name">Nenhum fornecedor</div>
                                <div class="supplier-products">Cadastre fornecedores</div>
                            </div>
                            <button class="contact-btn" onclick="window.location.href='inserir_fornecedores.php'">Cadastrar</button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="charts-section">
                <div class="chart-container">
                    <div class="chart-title">Demanda por categoria</div>
                    <canvas id="categoryChart"></canvas>
                </div>
                <div class="chart-container">
                    <div class="chart-title">Demanda por setor</div>
                    <canvas id="sectorChart"></canvas>
                </div>
            </div>

            <div class="sidebar-right">
                <div class="pendencies-section">
                    <div class="section-header">
                        <div class="section-title">Pendências</div>
                        <div class="view-details" onclick="window.location.href='painel.php'">Ver detalhes</div>
                    </div>
                    
                    <?php if ($pendencias_result->num_rows > 0): ?>
                        <?php while ($pendencia = $pendencias_result->fetch_assoc()): ?>
                            <div class="pendency-item">
                                <div class="pendency-info">
                                    <div class="pendency-name"><?= htmlspecialchars($pendencia['nome']) ?></div>
                                    <div class="pendency-issue"><?= htmlspecialchars($pendencia['ocorrencia']) ?></div>
                                </div>
                                <div class="pendency-quantity"><?= $pendencia['quantidade'] ?></div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="pendency-item">
                            <div class="pendency-info">
                                <div class="pendency-name">Nenhuma pendência</div>
                                <div class="pendency-issue">Todos os itens com estoque adequado</div>
                            </div>
                            <div class="pendency-quantity">✓</div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="calendar-section">
                    <div class="section-header">
                        <div class="section-title">Calendário</div>
                        <button class="add-event-btn" onclick="openEventModal()">+ Evento</button>
                    </div>
                    
                    <div class="calendar-events">
                        <?php if (!empty($eventos)): ?>
                            <?php foreach ($eventos as $evento): ?>
                                <div class="calendar-event" data-event-id="<?= $evento['id'] ?>">
                                    <div class="event-date">
                                        <div class="event-day"><?= $evento['data'] ?></div>
                                        <div class="event-weekday"><?= $evento['mes'] ?></div>
                                    </div>
                                    <div class="event-info">
                                        <div class="event-name"><?= htmlspecialchars($evento['evento']) ?></div>
                                        <div class="event-time"><?= $evento['hora'] ?></div>
                                        <?php if (!empty($evento['descricao'])): ?>
                                            <div class="event-description"><?= htmlspecialchars($evento['descricao']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="event-actions">
                                        <button class="edit-event-btn" onclick="editEvent(<?= $evento['id'] ?>, '<?= htmlspecialchars($evento['evento']) ?>', '<?= $evento['hora'] ?>', '<?= htmlspecialchars($evento['descricao']) ?>')">✏️</button>
                                        <button class="delete-event-btn" onclick="deleteEvent(<?= $evento['id'] ?>)">🗑️</button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="no-events">
                                <p>📅 Nenhum evento agendado</p>
                                <small>Clique em "+ Evento" para adicionar</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <a href="painel.php" class="btn-back">⬅ Voltar ao Painel</a>

    <!-- Modal para adicionar/editar evento -->
    <div id="eventModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEventModal()">&times;</span>
            <h2 id="eventModalTitle">Adicionar Evento</h2>
            <form id="eventForm" onsubmit="saveEvent(event)">
                <input type="hidden" id="eventId" name="eventId">
                <div class="form-group">
                    <label for="eventTitle">Título do Evento:</label>
                    <input type="text" id="eventTitle" name="eventTitle" required>
                </div>
                <div class="form-group">
                    <label for="eventDate">Data:</label>
                    <input type="date" id="eventDate" name="eventDate" required>
                </div>
                <div class="form-group">
                    <label for="eventTime">Hora:</label>
                    <input type="time" id="eventTime" name="eventTime" required>
                </div>
                <div class="form-group">
                    <label for="eventDescription">Descrição (opcional):</label>
                    <textarea id="eventDescription" name="eventDescription" rows="3"></textarea>
                </div>
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn-primary">Salvar Evento</button>
                    <button type="button" class="btn-secondary" onclick="closeEventModal()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal para adicionar item -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Adicionar Novo Item</h2>
            <form id="addItemForm" onsubmit="addNewItem(event)">
                <div class="form-group">
                    <label for="itemName">Nome do Item:</label>
                    <input type="text" id="itemName" name="itemName" required>
                </div>
                <div class="form-group">
                    <label for="itemType">Tipo:</label>
                    <input type="text" id="itemType" name="itemType" required>
                </div>
                <div class="form-group">
                    <label for="itemQuantity">Quantidade:</label>
                    <input type="number" id="itemQuantity" name="itemQuantity" required>
                </div>
                <div class="form-group">
                    <label for="itemLocation">Localização:</label>
                    <input type="text" id="itemLocation" name="itemLocation" required>
                </div>
                <div class="form-group">
                    <label for="itemNotes">Observações:</label>
                    <textarea id="itemNotes" name="itemNotes" rows="3"></textarea>
                </div>
                <button type="submit" class="btn-primary">Adicionar Item</button>
            </form>
        </div>
    </div>

    <script>
        // Configuração do tema
        const root = document.documentElement;
        let currentTheme = 'light';

        function toggleTheme(theme) {
            currentTheme = theme;
            root.setAttribute('data-theme', theme);
            
            // Atualizar botões ativos
            document.getElementById('darkBtn').classList.remove('active');
            document.getElementById('lightBtn').classList.remove('active');
            document.getElementById(theme + 'Btn').classList.add('active');
            
            // Salvar preferência
            localStorage.setItem('theme', theme);
        }

        // Carregar tema salvo
        const savedTheme = localStorage.getItem('theme') || 'light';
        toggleTheme(savedTheme);

        // Gráfico de Categoria (baseado nos dados reais)
        const categoryLabels = <?= json_encode($tipos) ?>;
        const categoryData = <?= json_encode($quantidades) ?>;

        const categoryChart = new Chart(document.getElementById('categoryChart'), {
            type: 'line',
            data: {
                labels: categoryLabels,
                datasets: [{
                    label: 'Quantidade',
                    data: categoryData,
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    }
                }
            }
        });

        // Gráfico de Setor (baseado em dados simulados mas realistas)
        const sectorChart = new Chart(document.getElementById('sectorChart'), {
            type: 'bar',
            data: {
                labels: ['REC', 'INV', 'OUT', 'DEV'],
                datasets: [{
                    data: [<?= $total_itens * 0.4 ?>, <?= $total_itens * 0.3 ?>, <?= $total_itens * 0.2 ?>, <?= $total_itens * 0.1 ?>],
                    backgroundColor: ['#667eea', '#764ba2', '#667eea', '#999']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Funções do Calendário
        function openEventModal(isEdit = false) {
            document.getElementById('eventModal').style.display = 'block';
            document.getElementById('eventModalTitle').textContent = isEdit ? 'Editar Evento' : 'Adicionar Evento';
            
            if (!isEdit) {
                document.getElementById('eventForm').reset();
                document.getElementById('eventId').value = '';
                // Definir data padrão como hoje
                const today = new Date().toISOString().split('T')[0];
                document.getElementById('eventDate').value = today;
            }
        }

        function closeEventModal() {
            document.getElementById('eventModal').style.display = 'none';
        }

        function editEvent(id, title, time, description) {
            openEventModal(true);
            document.getElementById('eventId').value = id;
            document.getElementById('eventTitle').value = title;
            document.getElementById('eventTime').value = time;
            document.getElementById('eventDescription').value = description || '';
        }

        function saveEvent(event) {
            event.preventDefault();
            
            const formData = new FormData();
            const eventId = document.getElementById('eventId').value;
            formData.append('action', eventId ? 'edit' : 'add');
            formData.append('id', eventId);
            formData.append('titulo', document.getElementById('eventTitle').value);
            formData.append('data_evento', document.getElementById('eventDate').value);
            formData.append('hora', document.getElementById('eventTime').value);
            formData.append('descricao', document.getElementById('eventDescription').value);

            fetch('gerenciar_eventos.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage('Evento salvo com sucesso!', 'success');
                    closeEventModal();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showMessage('Erro ao salvar evento: ' + data.message, 'error');
                }
            })
            .catch(error => {
                showMessage('Erro de conexão', 'error');
                console.error('Error:', error);
            });
        }

        function deleteEvent(id) {
            if (!confirm('Tem certeza que deseja excluir este evento?')) {
                return;
            }

            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('id', id);

            fetch('gerenciar_eventos.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage('Evento excluído com sucesso!', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showMessage('Erro ao excluir evento: ' + data.message, 'error');
                }
            })
            .catch(error => {
                showMessage('Erro de conexão', 'error');
                console.error('Error:', error);
            });
        }

        function showMessage(message, type) {
            const messageDiv = document.createElement('div');
            messageDiv.className = type + '-message';
            messageDiv.textContent = message;
            document.body.appendChild(messageDiv);
            
            setTimeout(() => {
                messageDiv.remove();
            }, 3000);
        }

        // Funções do Modal original
        function openModal() {
            document.getElementById('addModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('addModal').style.display = 'none';
        }

        function addNewItem(event) {
            event.preventDefault();
            // Aqui você pode adicionar a lógica para enviar os dados via AJAX
            alert('Funcionalidade de adicionar item será implementada!');
            closeModal();
        }

        function contactSupplier(email) {
            window.location.href = `mailto:${email}`;
        }

        // Fechar modais ao clicar fora
        window.onclick = function(event) {
            const addModal = document.getElementById('addModal');
            const eventModal = document.getElementById('eventModal');
            
            if (event.target == addModal) {
                closeModal();
            }
            if (event.target == eventModal) {
                closeEventModal();
            }
        }
    </script>
</body>
</html>