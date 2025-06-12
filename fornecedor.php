<?php
session_start();

// Verifica se o usuário está logado e se é fornecedor
if (!isset($_SESSION['usuario_id']) || $_SESSION['cargo'] !== 'fornecedor') {
    // Redirecionar para página de login se não estiver logado ou não for fornecedor
    header("Location: telaLogin.php");
    exit;
}

// Resto do código permanece igual...
$fornecedor = [
    'nome' => 'Empresa Fornecedora Ltda.',
    'cnpj' => '12.345.678/0001-90',
    'status' => 'Ativo',
];

$produtos_cadastrados = 45;
$pedidos_pendentes = 12;
$entregas_semana = 8;
$produtos_em_falta = 3;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Painel Fornecedor - AUGEBIT</title>
<link rel="icon" href="img/favicon.ico" type="image/x-icon" />
<style>
/* Adicione aqui todo o CSS que estava no arquivo original */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    background-color: #f8fafc;
    color: #334155;
}

.header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.logo h1 {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.logo p {
    font-size: 0.9rem;
    opacity: 0.9;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.role-badge {
    background: rgba(255, 255, 255, 0.2);
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.logout-btn {
    background: rgba(255, 255, 255, 0.1);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    text-decoration: none;
    transition: background-color 0.3s;
}

.logout-btn:hover {
    background: rgba(255, 255, 255, 0.2);
}

.main-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.welcome-section {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.welcome-section h2 {
    font-size: 1.8rem;
    margin-bottom: 1rem;
    color: #1e293b;
}

.welcome-section p {
    color: #64748b;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.company-info {
    background: #f8fafc;
    border-radius: 8px;
    padding: 1.5rem;
    border-left: 4px solid #667eea;
}

.company-info h3 {
    margin-bottom: 1rem;
    color: #1e293b;
}

.company-info p {
    margin-bottom: 0.5rem;
}

.status-section {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.status-section h3 {
    margin-bottom: 1.5rem;
    color: #1e293b;
}

.status-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.status-item {
    text-align: center;
    padding: 1.5rem;
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    border-radius: 8px;
}

.status-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: #667eea;
    margin-bottom: 0.5rem;
}

.status-label {
    font-size: 0.9rem;
    color: #64748b;
    font-weight: 500;
}

.action-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.action-card {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
}

.action-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.action-card .icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

.action-card .title {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #1e293b;
}

.action-card .description {
    color: #64748b;
    font-size: 0.9rem;
    line-height: 1.5;
}

.footer {
    background: #1e293b;
    color: white;
    text-align: center;
    padding: 2rem;
    margin-top: 3rem;
}

@media (max-width: 768px) {
    .header {
        padding: 1rem;
        flex-direction: column;
        gap: 1rem;
    }
    
    .main-content {
        padding: 1rem;
    }
    
    .action-grid {
        grid-template-columns: 1fr;
    }
    
    .status-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>
</head>
<body>
<header class="header">
<div class="logo">
<h1>Augebit</h1>
<p>Portal do Fornecedor</p>
</div>
<div class="user-info">
<span class="role-badge">FORNECEDOR</span>
<a href="logout.php" class="logout-btn">Sair</a>
</div>
</header>

<main class="main-content">
    <section class="welcome-section">
        <h2>Bem-vindo ao Portal do Fornecedor</h2>
        <p>Gerencie seus produtos, acompanhe pedidos e mantenha suas informações sempre atualizadas. Sua parceria é fundamental para o sucesso do nosso negócio.</p>

        <div class="company-info">
            <h3>Informações da Empresa</h3>
            <p><strong>Razão Social:</strong> <?php echo htmlspecialchars($fornecedor['nome']); ?></p>
            <p><strong>CNPJ:</strong> <?php echo htmlspecialchars($fornecedor['cnpj']); ?></p>
            <p><strong>Status:</strong> 
                <span style="color: <?php echo $fornecedor['status'] === 'Ativo' ? '#059669' : '#b91c1c'; ?>; font-weight: 600;">
                    <?php echo htmlspecialchars($fornecedor['status']); ?>
                </span>
            </p>
        </div>
    </section>

    <div class="status-section">
        <h3>Status dos Seus Produtos</h3>
        <div class="status-grid">
            <div class="status-item">
                <div class="status-number"><?php echo $produtos_cadastrados; ?></div>
                <div class="status-label">Produtos Cadastrados</div>
            </div>
            <div class="status-item">
                <div class="status-number"><?php echo $pedidos_pendentes; ?></div>
                <div class="status-label">Pedidos Pendentes</div>
            </div>
            <div class="status-item">
                <div class="status-number"><?php echo $entregas_semana; ?></div>
                <div class="status-label">Entregas Esta Semana</div>
            </div>
            <div class="status-item">
                <div class="status-number"><?php echo $produtos_em_falta; ?></div>
                <div class="status-label">Produtos em Falta</div>
            </div>
        </div>
    </div>

    <div class="action-grid">
        <a href="#" class="action-card" onclick="showProducts()">
            <div class="icon">📦</div>
            <div class="title">Meus Produtos</div>
            <div class="description">Visualizar e gerenciar catálogo de produtos fornecidos</div>
        </a>

        <a href="#" class="action-card" onclick="showOrders()">
            <div class="icon">📋</div>
            <div class="title">Pedidos Recebidos</div>
            <div class="description">Acompanhar pedidos e status de entrega</div>
        </a>

        <a href="#" class="action-card" onclick="showPrices()">
            <div class="icon">💰</div>
            <div class="title">Tabela de Preços</div>
            <div class="description">Atualizar preços e condições comerciais</div>
        </a>

        <a href="#" class="action-card" onclick="showReports()">
            <div class="icon">📊</div>
            <div class="title">Relatórios</div>
            <div class="description">Relatórios de vendas e performance</div>
        </div>

        <a href="#" class="action-card" onclick="showProfile()">
            <div class="icon">🏢</div>
            <div class="title">Meu Perfil</div>
            <div class="description">Atualizar dados da empresa e contatos</div>
        </a>

        <a href="#" class="action-card" onclick="showSupport()">
            <div class="icon">💬</div>
            <div class="title">Suporte</div>
            <div class="description">Entrar em contato com nossa equipe</div>
        </a>
    </div>
</main>

<footer class="footer">
    <p>&copy; 2025 Augebit. Todos os direitos reservados.</p>
</footer>

<script>
    function showProducts() {
        alert('Redirecionando para catálogo de produtos...');
    }
    function showOrders() {
        alert('Você tem <?php echo $pedidos_pendentes; ?> pedidos pendentes para análise.');
    }
    function showPrices() {
        alert('Redirecionando para tabela de preços...');
    }
    function showReports() {
        alert('Redirecionando para relatórios de vendas...');
    }
    function showProfile() {
        alert('Redirecionando para perfil da empresa...');
    }
    function showSupport() {
        alert('Redirecionando para central de suporte...');
    }
</script>

</body>
</html>