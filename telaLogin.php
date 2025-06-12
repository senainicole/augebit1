<?php
session_start();
require_once "banco.php"; // ajuste seu arquivo de conexão

// Inicializar variáveis de erro/sucesso
$erro_login = "";
$erro_cadastro = "";
$sucesso_cadastro = "";

// Debug: verificar se os dados estão chegando
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    error_log("POST recebido: " . print_r($_POST, true));
}

// Parte do cadastro
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['acao']) && $_POST['acao'] === 'cadastrar') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['password'] ?? '');
    $cargo = $_POST['role'] ?? '';
    $subcargo = $_POST['subRole'] ?? '';

    // Debug
    error_log("Tentativa de cadastro - Nome: $nome, Email: $email, Cargo: $cargo");

    if ($nome && $email && $senha && $cargo) {
        // Verificar se usuário já existe
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $erro_cadastro = "E-mail já cadastrado.";
            } else {
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

                // Inserir usuário - CORRIGIDO: campos correspondem à tabela
                $stmt_insert = $conn->prepare("INSERT INTO usuarios (nome, email, senha, cargo, subcargo) VALUES (?, ?, ?, ?, ?)");
                if ($stmt_insert) {
                    $stmt_insert->bind_param("sssss", $nome, $email, $senha_hash, $cargo, $subcargo);
                    if ($stmt_insert->execute()) {
                        $sucesso_cadastro = "Cadastro realizado com sucesso! Agora faça login.";
                    } else {
                        $erro_cadastro = "Erro ao cadastrar usuário: " . $conn->error;
                        error_log("Erro no INSERT: " . $conn->error);
                    }
                    $stmt_insert->close();
                } else {
                    $erro_cadastro = "Erro na preparação da query de cadastro.";
                    error_log("Erro prepare INSERT: " . $conn->error);
                }
            }
            $stmt->close();
        } else {
            $erro_cadastro = "Erro na preparação da query de verificação.";
            error_log("Erro prepare SELECT: " . $conn->error);
        }
    } else {
        $erro_cadastro = "Preencha todos os campos obrigatórios para cadastro.";
    }
}

// Parte do login - PRINCIPAL CORREÇÃO AQUI
if ($_SERVER["REQUEST_METHOD"] === "POST" && (!isset($_POST['acao']) || $_POST['acao'] === 'login')) {
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['password'] ?? '');
    $cargo = $_POST['role'] ?? '';
    $subcargo = $_POST['subRole'] ?? '';

    // Debug
    error_log("Tentativa de login - Email: $email, Cargo: $cargo");

    if ($email && $senha && $cargo) {
        // USUÁRIO ADMINISTRADOR PADRÃO - ALTERE AS CREDENCIAIS AQUI
        $admin_email = "admin@empresa.com";
        $admin_senha = "senha123";
        $admin_nome = "Administrador Sistema";
        
        // Verificar se é o usuário admin padrão
        if ($cargo === 'admin' && $email === $admin_email && $senha === $admin_senha) {
            // Login do admin padrão
            $_SESSION['usuario_id'] = 999; // ID fictício para o admin
            $_SESSION['usuario_email'] = $admin_email;
            $_SESSION['usuario_nome'] = $admin_nome;
            $_SESSION['cargo'] = 'admin';
            $_SESSION['subcargo'] = '';
            
            error_log("Login admin padrão bem-sucedido");
            header("Location: paineladm.php");
            exit();
        }
        
        // CORREÇÃO: Buscar na tabela correta baseada no cargo
        $tabela = 'usuarios'; // tabela padrão
        
        // Se for fornecedor, buscar na tabela fornecedores
        if ($cargo === 'fornecedor') {
            $stmt = $conn->prepare("SELECT * FROM fornecedores WHERE email = ?");
        } else {
            $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
        }
        
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado->num_rows > 0) {
                $usuario = $resultado->fetch_assoc();

                // Debug
                error_log("Usuário encontrado: " . print_r($usuario, true));

                // Verificar senha
                $senha_valida = false;
                
                // Para fornecedores, a senha pode estar em texto simples
                if ($cargo === 'fornecedor') {
                    // Primeiro tenta verificar se é hash
                    if (password_verify($senha, $usuario['senha'])) {
                        $senha_valida = true;
                    } elseif ($senha === $usuario['senha']) {
                        // Para senhas não criptografadas (dados existentes)
                        $senha_valida = true;
                    }
                } else {
                    // Para outros usuários, usar verificação normal
                    if (password_verify($senha, $usuario['senha'])) {
                        $senha_valida = true;
                    } elseif ($senha === $usuario['senha']) {
                        // Para senhas não criptografadas (dados de teste)
                        $senha_valida = true;
                    }
                }

                if ($senha_valida) {
                    // Configurar sessão
                    $_SESSION['usuario_id'] = $usuario['id'];
                    $_SESSION['usuario_email'] = $usuario['email'];
                    $_SESSION['usuario_nome'] = $usuario['nome'];
                    $_SESSION['cargo'] = $cargo;
                    $_SESSION['subcargo'] = $subcargo;

                    // Debug
                    error_log("Login bem-sucedido. Cargo: $cargo");
                    error_log("Sessão criada: " . print_r($_SESSION, true));

                    // Redirecionamentos com verificação adicional
                    if ($cargo === 'admin') {
                        error_log("Redirecionando para paineladm.php");
                        header("Location: paineladm.php");
                        exit();
                    } elseif ($cargo === 'fornecedor') {
                        $_SESSION['fornecedor_id'] = $usuario['id'];
                        error_log("Redirecionando para fornecedor.php");
                        header("Location: fornecedor.php");
                        exit();
                    } elseif ($cargo === 'funcionario') {
                        // Verificar se subcargo foi selecionado
                        if (empty($subcargo)) {
                            $erro_login = "Selecione o tipo de funcionário.";
                        } else {
                            error_log("Redirecionando para funcionarios.php");
                            header("Location: funcionarios.php");
                            exit();
                        }
                    } else {
                        $erro_login = "Cargo inválido selecionado.";
                    }
                } else {
                    $erro_login = "Senha incorreta.";
                    error_log("Senha incorreta para o usuário: $email");
                }
            } else {
                $erro_login = "Usuário não encontrado.";
                error_log("Usuário não encontrado: $email");
            }
            $stmt->close();
        } else {
            $erro_login = "Erro na preparação da query de login.";
            error_log("Erro prepare login: " . $conn->error);
        }
    } else {
        $erro_login = "Preencha todos os campos obrigatórios.";
        error_log("Campos obrigatórios não preenchidos - Email: $email, Senha: " . (!empty($senha) ? 'preenchida' : 'vazia') . ", Cargo: $cargo");
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login / Cadastro - AUGEBIT</title>
    <link rel="icon" href="img/favicon.ico" type="image/x-icon" />
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
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            width: 100%;
            max-width: 1200px;
            height: 600px;
            background: white;
            border-radius: 12px;
            display: flex;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .left-section {
            flex: 1;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .left-section img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }
        .right-section {
            flex: 1;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .welcome-title {
            font-size: 28px;
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
        }
        .welcome-subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 25px;
        }
        .form-label {
            display: block;
            font-size: 14px;
            color: #333;
            margin-bottom: 8px;
        }
        .form-input, .form-select {
            width: 100%;
            padding: 15px 0;
            border: none;
            border-bottom: 1px solid #e0e0e0;
            background: transparent;
            font-size: 16px;
            color: #333;
            outline: none;
            transition: border-color 0.3s ease;
        }
        .form-input:focus, .form-select:focus {
            border-bottom-color: #6366f1;
        }
        .sub-role-container {
            display: none;
            margin-top: 15px;
        }
        .sub-role-container.show {
            display: block;
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
            margin-top: 15px;
        }
        .login-button:hover {
            background: #333;
            transform: translateY(-2px);
        }
        .forgot-password {
            text-align: center;
            margin-top: 20px;
        }
        .forgot-link {
            color: #6366f1;
            text-decoration: none;
            font-size: 14px;
        }
        .forgot-link:hover {
            text-decoration: underline;
        }
        .error-message {
            background: #ffe5e5;
            color: #c00;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }
        .success-message {
            background: #e5ffe5;
            color: #080;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }
        .admin-info {
            background: #e3f2fd;
            color: #1976d2;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 12px;
            text-align: center;
        }
        .admin-info strong {
            display: block;
            margin-bottom: 5px;
        }
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                height: auto;
                max-width: 400px;
                margin: 20px;
            }
            .left-section {
                height: 200px;
            }
            .right-section {
                padding: 30px 25px;
            }
            .welcome-title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="left-section">
            <img src="img/transparente-login.png" alt="Login Image" />
        </div>

        <div class="right-section">
            <h1 class="welcome-title">Bem-vindo de volta!</h1>
            <p class="welcome-subtitle">Insira seus dados para acessar o sistema ou cadastre-se.</p>

            <?php if (!empty($erro_login)): ?>
                <div class="error-message"><?= htmlspecialchars($erro_login) ?></div>
            <?php endif; ?>

            <?php if (!empty($erro_cadastro)): ?>
                <div class="error-message"><?= htmlspecialchars($erro_cadastro) ?></div>
            <?php endif; ?>

            <?php if (!empty($sucesso_cadastro)): ?>
                <div class="success-message"><?= htmlspecialchars($sucesso_cadastro) ?></div>
            <?php endif; ?>

            <!-- Formulário de Login -->
            <form method="POST" action="" id="formLogin">
                <input type="hidden" name="acao" value="login" />
                <div class="form-group">
                    <label class="form-label" for="email_login">E-mail*</label>
                    <input type="email" id="email_login" name="email" class="form-input" required />
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_login">Senha*</label>
                    <input type="password" id="password_login" name="password" class="form-input" required />
                </div>

                <div class="form-group">
                    <label class="form-label" for="role_login">Cargo*</label>
                    <select id="role_login" name="role" class="form-select" required>
                        <option value="">Selecione seu cargo</option>
                        <option value="fornecedor">Fornecedor</option>
                        <option value="admin">Administrador</option>
                        <option value="funcionario">Funcionário</option>
                    </select>
                </div>

                <div id="subRoleContainerLogin" class="sub-role-container">
                    <div class="form-group">
                        <label class="form-label" for="subRole_login">Tipo de Funcionário*</label>
                        <select id="subRole_login" name="subRole" class="form-select">
                            <option value="">Selecione o tipo</option>
                            <option value="vendedor">Vendedor</option>
                            <option value="caixa">Operador de Caixa</option>
                            <option value="estoque">Controle de Estoque</option>
                            <option value="supervisor">Supervisor</option>
                            <option value="gerente">Gerente</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="login-button">Entrar</button>

                <div class="forgot-password">
                    <a href="#formCadastro" onclick="mostrarCadastro()" class="forgot-link">Não tem conta? Cadastre-se</a>
                </div>
            </form>

            <!-- Formulário de Cadastro -->
            <form method="POST" action="" id="formCadastro" style="display:none; margin-top:40px;">
                <input type="hidden" name="acao" value="cadastrar" />
                <h2>Cadastro</h2>
                <div class="form-group">
                    <label class="form-label" for="nome_cad">Nome Completo*</label>
                    <input type="text" id="nome_cad" name="nome" class="form-input" required />
                </div>
                <div class="form-group">
                    <label class="form-label" for="email_cad">E-mail*</label>
                    <input type="email" id="email_cad" name="email" class="form-input" required />
                </div>
                <div class="form-group">
                    <label class="form-label" for="password_cad">Senha*</label>
                    <input type="password" id="password_cad" name="password" class="form-input" required />
                </div>
                <div class="form-group">
                    <label class="form-label" for="role_cad">Cargo*</label>
                    <select id="role_cad" name="role" class="form-select" required>
                        <option value="">Selecione seu cargo</option>
                        <option value="fornecedor">Fornecedor</option>
                        <option value="admin">Administrador</option>
                        <option value="funcionario">Funcionário</option>
                    </select>
                </div>
                <div id="subRoleContainerCad" class="sub-role-container">
                    <div class="form-group">
                        <label class="form-label" for="subRole_cad">Tipo de Funcionário*</label>
                        <select id="subRole_cad" name="subRole" class="form-select">
                            <option value="">Selecione o tipo</option>
                            <option value="vendedor">Vendedor</option>
                            <option value="caixa">Operador de Caixa</option>
                            <option value="estoque">Controle de Estoque</option>
                            <option value="supervisor">Supervisor</option>
                            <option value="gerente">Gerente</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="login-button">Cadastrar</button>
                <div class="forgot-password" style="margin-top:10px;">
                    <a href="#formLogin" onclick="mostrarLogin()" class="forgot-link">Já tem conta? Faça login</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleSubRole(selectEl, subRoleContainer, subRoleSelect) {
            if (selectEl.value === 'funcionario') {
                subRoleContainer.classList.add('show');
                subRoleSelect.required = true;
            } else {
                subRoleContainer.classList.remove('show');
                subRoleSelect.value = '';
                subRoleSelect.required = false;
            }
        }

        const roleLogin = document.getElementById('role_login');
        const subRoleContainerLogin = document.getElementById('subRoleContainerLogin');
        const subRoleLogin = document.getElementById('subRole_login');

        roleLogin.addEventListener('change', () => {
            toggleSubRole(roleLogin, subRoleContainerLogin, subRoleLogin);
        });

        const roleCad = document.getElementById('role_cad');
        const subRoleContainerCad = document.getElementById('subRoleContainerCad');
        const subRoleCad = document.getElementById('subRole_cad');

        roleCad.addEventListener('change', () => {
            toggleSubRole(roleCad, subRoleContainerCad, subRoleCad);
        });

        function mostrarCadastro() {
            document.getElementById('formLogin').style.display = 'none';
            document.getElementById('formCadastro').style.display = 'block';
        }

        function mostrarLogin() {
            document.getElementById('formLogin').style.display = 'block';
            document.getElementById('formCadastro').style.display = 'none';
        }
    </script>

</body>
</html>