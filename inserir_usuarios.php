<?php
include 'banco.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recebe os dados do formulário
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $email = $_POST['email'];
    $senha = isset($_POST['senha']) ? $_POST['senha'] : ''; // Agora recebe a senha do formulário
    $telefone = $_POST['telefone'];
    $data_nascimento = $_POST['data_nascimento'];
    $cargo = $_POST['cargo'];

    // Hash da senha para segurança (apenas se a senha não estiver vazia)
    $senha_hash = !empty($senha) ? password_hash($senha, PASSWORD_DEFAULT) : '';

    // Combina nome e sobrenome em um único campo
    $nome_completo = $nome . ' ' . $sobrenome;

    // Primeiro, vamos verificar quais colunas existem na tabela
    $check_columns = "SHOW COLUMNS FROM usuarios";
    $result = $conn->query($check_columns);

    $columns = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $columns[] = $row['Field'];
        }
    }

    // Constrói a query baseada nas colunas que existem
    $sql_fields = [];
    $sql_values = [];

    // Sempre inclui nome e email (assumindo que existem)
    $sql_fields[] = 'nome';
    $sql_values[] = "'" . $conn->real_escape_string($nome_completo) . "'";

    $sql_fields[] = 'email';
    $sql_values[] = "'" . $conn->real_escape_string($email) . "'";

    // Adiciona campos opcionais se existirem na tabela
    if (in_array('senha', $columns)) {
        $sql_fields[] = 'senha';
        $sql_values[] = "'" . $conn->real_escape_string($senha_hash) . "'";
    }

    if (in_array('telefone', $columns)) {
        $sql_fields[] = 'telefone';
        $sql_values[] = "'" . $conn->real_escape_string($telefone) . "'";
    }

    if (in_array('data_nascimento', $columns)) {
        $sql_fields[] = 'data_nascimento';
        $sql_values[] = "'" . $conn->real_escape_string($data_nascimento) . "'";
    }

    if (in_array('cargo', $columns)) {
        $sql_fields[] = 'cargo';
        $sql_values[] = "'" . $conn->real_escape_string($cargo) . "'";
    }

    // Monta a query final
    $sql = "INSERT INTO usuarios (" . implode(', ', $sql_fields) . ") VALUES (" . implode(', ', $sql_values) . ")";

    if ($conn->query($sql) === TRUE) {
        $message = "Novo usuário inserido com sucesso!";
        $message_type = "success";
    } else {
        $message = "Erro: " . $conn->error;
        $message_type = "error";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário - Augebit</title>
    <link rel="icon" href="img/favicon.ico" type="image/x-icon" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f5f5f7;
            min-height: 100vh;
            transition: all 0.3s ease;
        }
        
        body.dark-theme {
            background: #1a1a1a;
        }
        
        .header {
            background: #fff;
            padding: 15px 30px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }
        
        body.dark-theme .header {
            background: #2d2d2d;
            box-shadow: 0 1px 3px rgba(255,255,255,0.1);
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #333;
            font-size: 18px;
            font-weight: 600;
        }
        
        body.dark-theme .logo-container {
            color: #fff;
        }
        
        .logo-icon {
            width: 32px;
            height: 32px;
            background: #6366f1;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            font-weight: bold;
        }
        
        .header-controls {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .theme-toggle {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #6b7280;
            padding: 6px 12px;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        body.dark-theme .theme-toggle {
            background: #4b5563;
        }
        
        .theme-toggle span {
            font-size: 12px;
            color: white;
            font-weight: 500;
        }
        
        .theme-switch {
            width: 32px;
            height: 16px;
            background: #9ca3af;
            border-radius: 8px;
            position: relative;
            transition: all 0.3s ease;
        }
        
        .theme-switch.dark {
            background: #ffffff;
        }
        
        .theme-switch::after {
            content: '';
            position: absolute;
            width: 12px;
            height: 12px;
            background: white;
            border-radius: 50%;
            top: 2px;
            left: 2px;
            transition: all 0.3s ease;
        }
        
        .theme-switch.dark::after {
            background: #6b7280;
            left: 18px;
        }
        
        .notification-btn {
            width: 40px;
            height: 40px;
            background: #f1f1f3;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: none;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        body.dark-theme .notification-btn {
            background: #404040;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            cursor: pointer;
        }
        
        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
        }
        
        .profile-card {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }
        
        body.dark-theme .profile-card {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        }
        
        .profile-title {
            color: white;
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 30px;
        }
        
        .profile-form-container {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        body.dark-theme .profile-form-container {
            background: #2d2d2d;
            box-shadow: 0 4px 20px rgba(255,255,255,0.05);
        }
        
        .profile-header-section {
            display: flex;
            gap: 30px;
            margin-bottom: 40px;
            align-items: center;
        }
        
        .profile-avatar {
            position: relative;
        }
        
        .avatar-image {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            font-weight: 600;
            overflow: hidden;
        }
        
        .avatar-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .avatar-badge {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 24px;
            height: 24px;
            background: #10b981;
            border-radius: 50%;
            border: 3px solid white;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 10px;
        }
        
        .profile-info {
            flex: 1;
        }
        
        .profile-name {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }
        
        body.dark-theme .profile-name {
            color: #fff;
        }
        
        .profile-role {
            color: #666;
            font-size: 16px;
            margin-bottom: 10px;
        }
        
        body.dark-theme .profile-role {
            color: #ccc;
        }
        
        .edit-link {
            color: #6366f1;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }
        
        .edit-link:hover {
            text-decoration: underline;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        
        .form-label {
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 8px;
        }
        
        body.dark-theme .form-label {
            color: #d1d5db;
        }
        
        .form-input {
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 16px;
            background: #f9fafb;
            transition: all 0.2s ease;
        }
        
        body.dark-theme .form-input {
            background: #374151;
            border-color: #4b5563;
            color: #fff;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #6366f1;
            background: white;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        
        body.dark-theme .form-input:focus {
            background: #4b5563;
            border-color: #6366f1;
        }
        
        .phone-group {
            display: flex;
            gap: 10px;
        }
        
        .country-code {
            flex-shrink: 0;
            width: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f9fafb;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 16px;
            gap: 5px;
        }
        
        body.dark-theme .country-code {
            background: #374151;
            border-color: #4b5563;
            color: #fff;
        }
        
        .date-input-wrapper {
            position: relative;
        }
        
        .calendar-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 18px;
        }
        
        .select-input {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
            padding-right: 40px;
        }

        .password-input-wrapper {
            position: relative;
        }



        .password-strength {
            margin-top: 8px;
            display: flex;
            gap: 4px;
            align-items: center;
        }

        .strength-bar {
            height: 4px;
            flex: 1;
            background: #e5e7eb;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .strength-bar.weak {
            background: #ef4444;
        }

        .strength-bar.medium {
            background: #f59e0b;
        }

        .strength-bar.strong {
            background: #10b981;
        }

        .strength-text {
            font-size: 12px;
            color: #6b7280;
            min-width: 60px;
        }

        body.dark-theme .strength-text {
            color: #9ca3af;
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
        }
        
        .save-btn {
            background: #6366f1;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .save-btn:hover {
            background: #5856eb;
            transform: translateY(-1px);
        }
        
        .success-message, .error-message {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .success-message {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        
        .error-message {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
        
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .profile-header-section {
                flex-direction: column;
                text-align: center;
                gap: 20px;
            }
            
            .phone-group {
                flex-direction: column;
            }
            
            .country-code {
                width: 100%;
            }
            
            .main-container {
                padding: 20px 15px;
            }
            
            .profile-card {
                padding: 30px 20px;
            }
            
            .profile-form-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-container">
            <img src="img/logoescura1.png" alt="">
            <span>AUGEBIT</span>
        </div>
        <div class="header-controls">
            <div class="theme-toggle" onclick="toggleTheme()">
                <span>Escuro</span>
                <div class="theme-switch" id="themeSwitch"></div>
                <span>Claro</span>
            </div>
            <button class="notification-btn">🔔</button>
            <div class="user-avatar">
                <div style="width: 100%; height: 100%; background: linear-gradient(45deg, #6366f1, #8b5cf6); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                    ?
                </div>
            </div>
        </div>
    </div>

    <div class="main-container">
        <div class="profile-card">
            <h1 class="profile-title">Cadastro de Usuário</h1>

            <div class="profile-form-container">
                <?php if (isset($message)): ?>
                    <div class="<?php echo $message_type; ?>-message">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <div class="profile-header-section">
                    <div class="profile-avatar">
                        <div class="avatar-image" id="profileAvatar">
                            <span id="avatarInitials">?</span>
                        </div>
                        <div class="avatar-badge">+</div>
                    </div>
                    <div class="profile-info">
                        <h2 class="profile-name" id="displayName">Insira o nome do usuário</h2>
                        <p class="profile-role">Selecione o cargo</p>
                        <a href="#" class="edit-link">Preencha as informações abaixo</a>
                    </div>
                </div>

                <form method="POST" action="inserir_usuarios.php">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label" for="nome">Nome</label>
                            <input type="text" id="nome" name="nome" class="form-input" placeholder="Digite o nome" required onkeyup="updateProfile()">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="sobrenome">Sobrenome</label>
                            <input type="text" id="sobrenome" name="sobrenome" class="form-input" placeholder="Digite o sobrenome" required onkeyup="updateProfile()">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="email">E-mail</label>
                            <input type="email" id="email" name="email" class="form-input" placeholder="exemplo@empresa.com" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="senha">Senha</label>
                            <input type="password" id="senha" name="senha" class="form-input" placeholder="Digite uma senha segura" required oninput="checkPasswordStrength()">
                            <div class="password-strength" id="passwordStrength">
                                <div class="strength-bar" id="strengthBar1"></div>
                                <div class="strength-bar" id="strengthBar2"></div>
                                <div class="strength-bar" id="strengthBar3"></div>
                                <div class="strength-bar" id="strengthBar4"></div>
                                <span class="strength-text" id="strengthText">Fraca</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="telefone">Número de telefone</label>
                            <div class="phone-group">
                                <div class="country-code">
                                    <span>🇧🇷</span>
                                    <span>+55</span>
                                </div>
                                <input type="tel" id="telefone" name="telefone" class="form-input" placeholder="11 99999-9999" style="flex: 1;">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="cargo">Cargo</label>
                            <select id="cargo" name="cargo" class="form-input select-input" required>
                                <option value="">Selecione o cargo</option>
                                <option value="Fornecedor">Fornecedor</option>
                                <option value="Fornecedor">Funcionário</option>
                                <option value="Gerente">Gerente</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="data_nascimento">Data de admissão e local</label>
                            <div class="date-input-wrapper">
                                <input type="text" id="data_nascimento" name="data_nascimento" class="form-input" placeholder="Ex: São Paulo, SP - 01 de janeiro de 2024" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="save-btn">Cadastrar Usuário</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let isDarkTheme = false;

        function toggleTheme() {
            isDarkTheme = !isDarkTheme;
            const body = document.body;
            const themeSwitch = document.getElementById('themeSwitch');

            if (isDarkTheme) {
                body.classList.add('dark-theme');
                themeSwitch.classList.add('dark');
            } else {
                body.classList.remove('dark-theme');
                themeSwitch.classList.remove('dark');
            }
        }

        function updateProfile() {
            const nome = document.getElementById('nome').value;
            const sobrenome = document.getElementById('sobrenome').value;
            const displayName = document.getElementById('displayName');
            const avatarInitials = document.getElementById('avatarInitials');

            if (nome || sobrenome) {
                const fullName = `${nome} ${sobrenome}`.trim();
                displayName.textContent = fullName || 'Insira o nome do usuário';

                const initials = getInitials(nome, sobrenome);
                avatarInitials.textContent = initials;
            } else {
                displayName.textContent = 'Insira o nome do usuário';
                avatarInitials.textContent = '?';
            }
        }

        function getInitials(nome, sobrenome) {
            const primeiroNome = nome ? nome.charAt(0).toUpperCase() : '';
            const ultimoNome = sobrenome ? sobrenome.charAt(0).toUpperCase() : '';
            return `${primeiroNome}${ultimoNome}` || '?';
        }

        function togglePassword() {
            const passwordInput = document.getElementById('senha');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        }

        function checkPasswordStrength() {
            const password = document.getElementById('senha').value;
            const strengthBars = [
                document.getElementById('strengthBar1'),
                document.getElementById('strengthBar2'),
                document.getElementById('strengthBar3'),
                document.getElementById('strengthBar4')
            ];
            const strengthText = document.getElementById('strengthText');

            // Reset all bars
            strengthBars.forEach(bar => {
                bar.className = 'strength-bar';
            });

            let strength = 0;
            let strengthLabel = 'Fraca';

            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/\d/)) strength++;
            if (password.match(/[^a-zA-Z\d]/)) strength++;

            // Apply strength classes
            for (let i = 0; i < strength; i++) {
                if (strength <= 1) {
                    strengthBars[i].classList.add('weak');
                    strengthLabel = 'Fraca';
                } else if (strength <= 2) {
                    strengthBars[i].classList.add('medium');
                    strengthLabel = 'Média';
                } else if (strength <= 3) {
                    strengthBars[i].classList.add('medium');
                    strengthLabel = 'Boa';
                } else {
                    strengthBars[i].classList.add('strong');
                    strengthLabel = 'Forte';
                }
            }

            strengthText.textContent = strengthLabel;
        }

        function formatPhone() {
            const phoneInput = document.getElementById('telefone');
            let value = phoneInput.value.replace(/\D/g, '');
            if (value.length <= 11) {
                let formattedValue = value.replace(/(\d{2})(\d{5})(\d{4})/, '$1 $2-$3');
                if (value.length < 11) {
                    formattedValue = value.replace(/(\d{2})(\d{4})(\d{4})/, '$1 $2-$3');
                }
                phoneInput.value = formattedValue;
            }
        }

        document.getElementById('telefone').addEventListener('input', formatPhone);

        document.addEventListener('DOMContentLoaded', function() {
            updateProfile();
        });
    </script>
</body>
</html>