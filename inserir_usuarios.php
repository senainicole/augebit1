<?php 
include 'banco.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recebe os dados do formulário
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $telefone = $_POST['telefone'];
    $data_nascimento = $_POST['data_nascimento'];
    $cargo = $_POST['cargo'];

    // Insere os dados no banco
    $sql = "INSERT INTO usuarios (nome, sobrenome, email, senha, telefone, data_nascimento, cargo)
            VALUES ('$nome', '$sobrenome', '$email', '$senha', '$telefone', '$data_nascimento', '$cargo')";

    if ($conn->query($sql) === TRUE) {
        $message = "Novo usuário inserido com sucesso!";
        $message_type = "success";
    } else {
        $message = "Erro: " . $sql . "<br>" . $conn->error;
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
    <title>Meu Perfil - Augebit</title>
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
                    AJ
                </div>
            </div>
        </div>
    </div>

    <div class="main-container">
        <div class="profile-card">
            <h1 class="profile-title">Meu perfil</h1>
            
            <div class="profile-form-container">
                <?php if (isset($message)): ?>
                    <div class="<?php echo $message_type; ?>-message">
                        <?php echo $message; ?>
                        <div class="form-group full-width">
                            <label class="form-label" for="senha">Senha</label>
                            <input type="password" id="senha" name="senha" class="form-input" placeholder="Digite sua senha" required>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="profile-header-section">
                    <div class="profile-avatar">
                        <div class="avatar-image" id="profileAvatar">
                            <span id="avatarInitials">AJ</span>
                        </div>
                        <div class="avatar-badge">✓</div>
                    </div>
                    <div class="profile-info">
                        <h2 class="profile-name" id="displayName">Ana Julia Ferrara</h2>
                        <p class="profile-role">Assistente de Logística</p>
                        <a href="#" class="edit-link">Editar informações</a>
                    </div>
                </div>

                <form method="POST" action="inserir_usuarios.php">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label" for="nome">Nome</label>
                            <input type="text" id="nome" name="nome" class="form-input" value="Ana Julia" required onkeyup="updateProfile()">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="telefone">Número de telefone</label>
                            <div class="phone-group">
                                <div class="country-code">
                                    <span>🇧🇷</span>
                                    <span>+55</span>
                                </div>
                                <input type="tel" id="telefone" name="telefone" class="form-input" value="11 99808-9845" style="flex: 1;">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="sobrenome">Sobrenome</label>
                            <input type="text" id="sobrenome" name="sobrenome" class="form-input" value="Ferrara" required onkeyup="updateProfile()">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="email">E-mail</label>
                            <input type="email" id="email" name="email" class="form-input" value="anajulia@augebitoad.com" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="data_nascimento">Data de admissão e local</label>
                            <div class="date-input-wrapper">
                                <input type="text" id="data_nascimento" name="data_nascimento" class="form-input" value="São Paulo, Paulista - 12 de setembro de 2025" required>
                                <span class="calendar-icon">📅</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="cargo">Cargo</label>
                            <select id="cargo" name="cargo" class="form-input select-input" required>
                                <option value="Assistente de Logística" selected>Assistente de Logística</option>
                                <option value="Analista">Analista</option>
                                <option value="Coordenador">Coordenador</option>
                                <option value="Gerente">Gerente</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="save-btn">Salvar</button>
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
            
            const fullName = `${nome} ${sobrenome}`.trim();
            displayName.textContent = fullName || 'Nome do Usuário';
            
            const initials = getInitials(nome, sobrenome);
            avatarInitials.textContent = initials;
        }

        function getInitials(nome, sobrenome) {
            const primeiroNome = nome ? nome.charAt(0).toUpperCase() : '';
            const ultimoNome = sobrenome ? sobrenome.charAt(0).toUpperCase() : '';
            return `${primeiroNome}${ultimoNome}` || 'U';
        }

        function formatPhone() {
            const phoneInput = document.getElementById('telefone');
            let value = phoneInput.value.replace(/\D/g, '');
            let formattedValue = value.replace(/(\d{2})(\d{5})(\d{4})/, '$1 $2-$3');
            phoneInput.value = formattedValue;
        }

        function handleSubmit(event) {
            // Remover esta função já que agora o form será processado pelo PHP
            // O formulário será submetido normalmente para o servidor
        }

        document.getElementById('telefone').addEventListener('input', formatPhone);
        
        document.addEventListener('DOMContentLoaded', function() {
            updateProfile();
        });
    </script>
</body>
</html>