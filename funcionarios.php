<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Painel Funcionário - AUGEBIT</title>
<link rel="icon" href="img/favicon.ico" type="image/x-icon">
<style>
* {
margin: 0;
padding: 0;
box-sizing: border-box;
}

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        background-color: #f5f5f5;
        min-height: 100vh;
    }

    .header {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 25px 40px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .logo h1 {
        font-size: 28px;
        font-weight: 700;
    }

    .logo p {
        font-size: 14px;
        opacity: 0.9;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .role-badge {
        background: rgba(255, 255, 255, 0.2);
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .logout-btn {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 8px 20px;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 14px;
        text-decoration: none;
    }

    .logout-btn:hover {
        background: rgba(255, 255, 255, 0.1);
        transform: translateY(-2px);
    }

    .main-content {
        padding: 50px 40px;
        text-align: center;
    }

    .welcome-section h2 {
        font-size: 32px;
        color: #333;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .welcome-section p {
        font-size: 16px;
        color: #666;
        margin-bottom: 40px;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .action-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        max-width: 900px;
        margin: 0 auto;
    }

    .action-card {
        background: white;
        padding: 30px 25px;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        text-decoration: none;
        color: #333;
        border: 2px solid transparent;
    }

    .action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        border-color: #10b981;
        color: #10b981;
    }

    .action-card .icon {
        font-size: 40px;
        margin-bottom: 15px;
    }

    .action-card .title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .action-card .description {
        font-size: 14px;
        color: #666;
        line-height: 1.4;
    }

    .quick-stats {
        background: white;
        margin: 40px auto;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        max-width: 900px;
    }

    .quick-stats h3 {
        font-size: 20px;
        color: #333;
        margin-bottom: 20px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .stat-item {
        text-align: center;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .stat-number {
        font-size: 28px;
        font-weight: 700;
        color: #10b981;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 14px;
        color: #666;
    }

    .footer {
        background: white;
        padding: 25px;
        text-align: center;
        color: #666;
        border-top: 1px solid #eee;
        margin-top: 50px;
    }

    @media (max-width: 768px) {
        .header {
            padding: 20px 25px;
            flex-direction: column;
            gap: 15px;
        }

        .main-content {
            padding: 30px 25px;
        }

        .welcome-section h2 {
            font-size: 26px;
        }

        .action-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .action-card {
            padding: 25px 20px;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

</head>
<body>
<header class="header">
<div class="logo">
<h1>Augebit</h1>
<p>Painel do Funcionário</p>
</div>
<div class="user-info">
<span class="role-badge" id="roleBadge">FUNCIONÁRIO</span>
<a href="login.html" class="logout-btn">Sair</a>
</div>
</header>

<main class="main-content">
    <section class="welcome-section">
        <h2>Painel do Funcionário</h2>
        <p>Gerencie o estoque, processe pedidos e mantenha o sistema organizado com eficiência.</p>
    </section>

    <div class="quick-stats">
        <h3>Resumo Rápido</h3>
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">156</div>
                <div class="stat-label">Produtos em Estoque</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">23</div>
                <div class="stat-label">Produtos com Baixo Estoque</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">8</div>
                <div class="stat-label">Pedidos Pendentes</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">12</div>
                <div class="stat-label">Fornecedores Ativos</div>
            </div>
        </div>
    </div>

    <div class="action-grid">
        <a href="dashboard.php" class="action-card">
            <div class="icon">📊</div>
            <div class="title">Relatórios</div>
            <div class="description">Visualizar dados e estatísticas</div>
        </a>

        <a href="painel.php" class="action-card">
            <div class="icon">🎛️</div>
            <div class="title">Painel de Controle</div>
            <div class="description">Acesso ao painel principal</div>
        </a>

        <a href="inserir_itens.php" class="action-card">
            <div class="icon">📦</div>
            <div class="title">Gerenciar Produtos</div>
            <div class="description">Adicionar e editar produtos</div>
        </a>

        <a href="inserir_fornecedores.php" class="action-card">
            <div class="icon">🏢</div>
            <div class="title">Fornecedores</div>
            <div class="description">Cadastrar novos fornecedores</div>
        </a>

        <a href="#" class="action-card" onclick="showInventory()">
            <div class="icon">📋</div>
            <div class="title">Inventário</div>
            <div class="description">Controle de estoque atual</div>
        </a>

        <a href="#" class="action-card" onclick="showAlerts()">
            <div class="icon">⚠️</div>
            <div class="title">Alertas</div>
            <div class="description">Produtos com baixo estoque</div>
        </a>
    </div>
</main>

<footer class="footer">
    <p>&copy; 2025 Augebit. Todos os direitos reservados.</p>
</footer>

<script>
    // Detecta o tipo de funcionário da URL ou sessionStorage
    document.addEventListener('DOMContentLoaded', function() {
        const url