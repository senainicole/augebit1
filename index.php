<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AUGEBIT - Sistema de Gestão de Estoque</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f5f5f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* === ESTILOS DA TELA DE LOGIN === */
        .login-screen {
            display: flex;
            width: 100%;
            max-width: 1400px;
            height: 700px;
            background: white;
            border-radius: 12px;
            overflow: visible;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        }

        .left-section {
            flex: 1;
            padding: 100px 80px 80px 80px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #f8f8f8;
        }

        .logo {
            display: flex;
            align-items: center;
            margin-bottom: 60px;
        }

        .logo img {
            height: 48px;
            width: auto;
        }

        .welcome-title {
            font-size: 32px;
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
            line-height: 1.2;
        }

        .welcome-subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 40px;
        }

        .form-group {
            margin-bottom: 30px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            color: #333;
            margin-bottom: 8px;
            font-weight: 400;
        }

        .form-input {
            width: 100%;
            padding: 20px 0;
            border: none;
            border-bottom: 1px solid #e0e0e0;
            background: transparent;
            font-size: 16px;
            color: #333;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .form-input:focus {
            border-bottom-color: #6366f1;
        }

        .form-select {
            width: 100%;
            padding: 20px 0;
            border: none;
            border-bottom: 1px solid #e0e0e0;
            background: transparent;
            font-size: 16px;
            color: #333;
            outline: none;
            transition: border-color 0.3s ease;
            cursor: pointer;
        }

        .form-select:focus {
            border-bottom-color: #6366f1;
        }

        .form-select option {
            background: white;
            color: #333;
            padding: 10px;
        }

        .login-button {
            width: 100%;
            padding: 16px;
            background: #000;
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .login-button:hover {
            background: #333;
            transform: translateY(-2px);
        }

        .forgot-password {
            text-align: center;
            margin-top: 25px;
        }

        .forgot-link {
            color: #6366f1;
            text-decoration: none;
            font-size: 14px;
            font-weight: 400;
            transition: color 0.3s ease;
        }

        .forgot-link:hover {
            color: #4f46e5;
            text-decoration: underline;
        }

        .right-section {
            flex: 1;
            background: linear-gradient(135deg, 
                #6366f1 0%, 
                #3b82f6 20%, 
                #06b6d4 40%, 
                #8b5cf6 60%, 
                #c084fc 80%, 
                #a855f7 100%);
            position: relative;
            overflow: hidden;
            border-radius: 20px;
            margin: 20px;
        }

        .gradient-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(ellipse at 30% 20%, 
                rgba(255, 255, 255, 0.4) 0%, 
                rgba(255, 255, 255, 0.2) 30%, 
                transparent 60%),
            radial-gradient(ellipse at 70% 70%, 
                rgba(147, 197, 253, 0.3) 0%, 
                rgba(147, 197, 253, 0.1) 40%, 
                transparent 70%);
        }

        .gradient-blur {
            position: absolute;
            top: 15%;
            left: 25%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, 
                rgba(255, 255, 255, 0.5) 0%, 
                rgba(147, 197, 253, 0.3) 30%, 
                rgba(196, 181, 253, 0.2) 60%, 
                transparent 80%);
            border-radius: 50%;
            filter: blur(50px);
        }

        /* === ESTILOS DO DASHBOARD === */
        .dashboard-screen {
            display: none;
            width: 100%;
            min-height: 100vh;
            background-color: #f5f5f5;
            flex-direction: column;
        }

        .dashboard-header {
            background: linear-gradient(135deg, #6366f1 0%, #3b82f6 100%);
            color: white;
            padding: 30px 50px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .dashboard-logo {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .dashboard-logo h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .dashboard-logo p {
            font-size: 16px;
            opacity: 0.9;
        }

        .logout-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .dashboard-content {
            flex: 1;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .dashboard-content h2 {
            font-size: 36px;
            color: #333;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .dashboard-content p {
            font-size: 18px;
            color: #666;
            margin-bottom: 50px;
            max-width: 600px;
            line-height: 1.6;
        }

        .dashboard-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            width: 100%;
            max-width: 800px;
        }

        .dashboard-btn {
            background: white;
            color: #333;
            text-decoration: none;
            padding: 30px 40px;
            border-radius: 15px;
            font-size: 16px;
            font-weight: 600;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .dashboard-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            border-color: #6366f1;
            color: #6366f1;
        }

        .dashboard-footer {
            background: white;
            padding: 30px;
            text-align: center;
            color: #666;
            border-top: 1px solid #eee;
        }

        /* ANIMAÇÕES */
        .fade-out {
            opacity: 0;
            transform: scale(0.95);
            transition: all 0.5s ease;
        }

        .fade-in {
            opacity: 1;
            transform: scale(1);
            transition: all 0.5s ease 0.3s;
        }

        /* RESPONSIVO */
        @media (max-width: 768px) {
            .login-screen {
                flex-direction: column;
                height: auto;
                max-width: 400px;
                margin: 20px;
            }

            .left-section {
                padding: 40px 30px;
            }

            .right-section {
                height: 200px;
            }

            .welcome-title {
                font-size: 24px;
            }

            .dashboard-header {
                padding: 20px 30px;
            }

            .dashboard-content {
                padding: 40px 30px;
            }

            .dashboard-content h2 {
                font-size: 28px;
            }

            .dashboard-buttons {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .dashboard-btn {
                padding: 25px 30px;
            }
        }
    </style>
</head>
<body>
    <!-- TELA DE LOGIN -->
    <div class="login-screen" id="loginScreen">
        <div class="left-section">
            <div class="logo">
                <img src="img/logorosa.png" alt="AUGEBIT">
            </div>
            
            <h1 class="welcome-title">Olá, bem-vindo(a) de volta!</h1>
            <p class="welcome-subtitle">Insira seus dados para efetuar o login.</p>
            
            <form id="loginForm">
                <div class="form-group">
                    <label class="form-label" for="email">E-mail*</label>
                    <input type="email" id="email" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="password">Senha*</label>
                    <input type="password" id="password" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="role">Cargo*</label>
                    <select id="role" class="form-select" required>
                        <option value="">Selecione seu cargo</option>
                        <option value="cliente">Cliente</option>
                        <option value="fornecedor">Fornecedor</option>
                        <option value="admin">Administrador</option>
                        <option value="funcionario">Funcionário</option>
                    </select>
                </div>
                
                <button type="submit" class="login-button">Entrar</button>
                
                <div class="forgot-password">
                    <a href="#" class="forgot-link" onclick="showForgotPassword()">Esqueceu sua senha?</a>
                </div>
            </form>
        </div>
        
        <div class="right-section">
            <div class="gradient-overlay"></div>
            <div class="gradient-blur"></div>
        </div>
    </div>

    <!-- TELA DO DASHBOARD -->
    <div class="dashboard-screen" id="dashboardScreen">
        <header class="dashboard-header">
            <div class="dashboard-logo">
                <div>
                    <h1>Augebit</h1>
                    <p>Sistema de Gestão de Estoque</p>
                </div>
                <button class="logout-btn" onclick="logout()">Sair</button>
            </div>
        </header>

        <div class="dashboard-content">
            <h2>Bem-vindo ao Sistema de Gestão de Estoque Augebit</h2>
            <p>Gerencie os itens de estoque, fornecedores e usuários de forma fácil e eficiente. Escolha uma das opções abaixo para começar.</p>
            
            <div class="dashboard-buttons">
                <a href="inserir_itens.php" class="dashboard-btn" onclick="showMessage('Novo Item')">
                    📦 Novo Item
                </a>
                <a href="inserir_usuarios.php" class="dashboard-btn" onclick="showMessage('Novo Usuário')">
                    👤 Novo Usuário
                </a>
                <a href="inserir_fornecedores.php" class="dashboard-btn" onclick="showMessage('Novo Fornecedor')">
                    🏢 Novo Fornecedor
                </a>
            </div>
        </div>

        <footer class="dashboard-footer">
            <p>&copy; 2025 Augebit. Todos os direitos reservados.</p>
        </footer>
    </div>

    <script>
        // Adicionar efeito de foco suave nos inputs e select
        const inputs = document.querySelectorAll('.form-input, .form-select');
        
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                if (!this.value) {
                    this.parentElement.classList.remove('focused');
                }
            });
        });

        // Função de login
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const role = document.getElementById('role').value;
            
            if (email && password && role) {
                // Simular processo de login
                const loginButton = document.querySelector('.login-button');
                loginButton.textContent = 'Entrando...';
                loginButton.disabled = true;
                
                setTimeout(() => {
                    // Esconder tela de login com animação
                    const loginScreen = document.getElementById('loginScreen');
                    const dashboardScreen = document.getElementById('dashboardScreen');
                    
                    loginScreen.classList.add('fade-out');
                    
                    setTimeout(() => {
                        loginScreen.style.display = 'none';
                        dashboardScreen.style.display = 'flex';
                        dashboardScreen.classList.add('fade-in');
                        
                        // Resetar botão de login
                        loginButton.textContent = 'Entrar';
                        loginButton.disabled = false;
                        
                        // Mostrar cargo selecionado no dashboard
                        updateDashboardForRole(role);
                    }, 500);
                    
                }, 1500);
            } else {
                alert('Por favor, preencha todos os campos obrigatórios.');
            }
        });

        // Função de logout
        function logout() {
            const loginScreen = document.getElementById('loginScreen');
            const dashboardScreen = document.getElementById('dashboardScreen');
            
            dashboardScreen.classList.remove('fade-in');
            dashboardScreen.classList.add('fade-out');
            
            setTimeout(() => {
                dashboardScreen.style.display = 'none';
                loginScreen.style.display = 'flex';
                loginScreen.classList.remove('fade-out');
                
                // Limpar campos de login
                document.getElementById('email').value = '';
                document.getElementById('password').value = '';
                document.getElementById('role').value = '';
            }, 500);
        }

        // Função para atualizar dashboard baseado no cargo
        function updateDashboardForRole(role) {
            const welcomeTitle = document.querySelector('.dashboard-content h2');
            const welcomeText = document.querySelector('.dashboard-content p');
            
            const roleNames = {
                'cliente': 'Cliente',
                'fornecedor': 'Fornecedor', 
                'admin': 'Administrador',
                'funcionario': 'Funcionário'
            };
            
            const roleName = roleNames[role] || 'Usuário';
            welcomeTitle.textContent = `Bem-vindo, ${roleName}!`;
            
            // Personalizar mensagem baseada no cargo
            switch(role) {
                case 'cliente':
                    welcomeText.textContent = 'Acesse suas informações, histórico de pedidos e gerencie sua conta.';
                    break;
                case 'fornecedor':
                    welcomeText.textContent = 'Gerencie seus produtos, pedidos e mantenha suas informações atualizadas.';
                    break;
                case 'admin':
                    welcomeText.textContent = 'Controle total do sistema. Gerencie usuários, produtos e todas as funcionalidades.';
                    break;
                case 'funcionario':
                    welcomeText.textContent = 'Gerencie o estoque, processe pedidos e mantenha o sistema organizado.';
                    break;
                default:
                    welcomeText.textContent = 'Gerencie os itens de estoque, fornecedores e usuários de forma fácil e eficiente.';
            }
        }

        // Função para mostrar mensagem temporária (substituir por navegação real)
        function showMessage(action) {
            alert(`Funcionalidade "${action}" será implementada em breve!`);
        }

        // Função para recuperação de senha
        function showForgotPassword() {
            // Redirecionar para a página de inserir usuários
            window.location.href = 'inserir_usuarios.php';
        }
    </script>
</body>
</html>