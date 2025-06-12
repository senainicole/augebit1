<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Painel Administrador - AUGEBIT</title>
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
        background: linear-gradient(135deg, #6366f1 0%, #3b82f6 100%);
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
        max-width: 1000px;
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
        border-color: #6366f1;
        color: #6366f1;
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
    }
</style>

</head>
<body>
<header class="header">
<div class="logo">
<h1>Augebit</h1>
<p>Painel Administrativo</p>
</div>
<div class="user-info">
<span class="role-badge">ADMINISTRADOR</span>
<a href="telaLogin.php" class="logout-btn">Sair</a>
</div>
</header>

<main class="main-content">
    <section class="welcome-section">
        <h2>Painel do Administrador</h2>
        <p>Controle total do sistema. Gerencie usuários, produtos e todas as funcionalidades do sistema de estoque.</p>
    </section>

    <div class="action-grid">
        <a href="dashboard.php" class="action-card">
            <div class="icon">📊</div>
            <div class="title">Relatórios</div>
            <div class="description">Visualize dados e estatísticas do sistema</div>
        </a>

        <a href="painel.php" class="action-card">
            <div class="icon">🎛️</div>
            <div class="title">Painel Geral</div>
            <div class="description">Controle geral do sistema</div>
        </a>

        <a href="inserir_itens.php" class="action-card">
            <div class="icon">📦</div>
            <div class="title">Gerenciar Produtos</div>
            <div class="description">Adicionar, editar e remover itens</div>
        </a>

        <a href="inserir_usuarios.php" class="action-card">
            <div class="icon">👥</div>
            <div class="title">Gerenciar Usuários</div>
            <div class="description">Cadastrar e gerenciar funcionários</div>
        </a>

        <a href="inserir_fornecedores.php" class="action-card">
            <div class="icon">🏢</div>
            <div class="title">Gerenciar Fornecedores</div>
            <div class="description">Cadastrar e gerenciar fornecedores</div>
        </a>

        <a href="#" class="action-card" onclick="showConfig()">
            <div class="icon">⚙️</div>
            <div class="title">Configurações</div>
            <div class="description">Configurar parâmetros do sistema</div>
        </a>
    </div>
</main>

<footer class="footer">
    <p>&copy; 2025 Augebit. Todos os direitos reservados.</p>
</footer>

<script>
    function showConfig() {
        alert('Funcionalidade em desenvolvimento');
    }
</script>

</body>
</html>