<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Perfis da Equipe - TechCorp</title>
<style>
* {
margin: 0;
padding: 0;
box-sizing: border-box;
}

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 20px;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Header com Logo */
    .header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 20px 30px;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .company-logo {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .logo-img {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        object-fit: cover;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    .company-info h1 {
        font-size: 1.8rem;
        color: #2c3e50;
        font-weight: 700;
    }

    .company-info p {
        color: #7f8c8d;
        font-size: 0.9rem;
        margin-top: 2px;
    }

    .auth-section {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .user-info {
        display: none;
        align-items: center;
        gap: 10px;
        color: #2c3e50;
        font-weight: 500;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    /* Modal de Login */
    .login-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .modal-content {
        background: white;
        padding: 40px;
        border-radius: 20px;
        width: 90%;
        max-width: 400px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }

    .modal-title {
        text-align: center;
        color: #2c3e50;
        font-size: 1.8rem;
        margin-bottom: 30px;
        font-weight: 600;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        color: #555;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .form-group input {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e1e8ed;
        border-radius: 10px;
        font-size: 1rem;
        transition: border-color 0.3s ease;
    }

    .form-group input:focus {
        outline: none;
        border-color: #667eea;
    }

    .login-btn {
        width: 100%;
        padding: 12px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.2s ease;
    }

    .login-btn:hover {
        transform: translateY(-2px);
    }

    .access-btn {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 500;
        transition: transform 0.2s ease;
    }

    .access-btn:hover {
        transform: translateY(-2px);
    }

    .logout-btn {
        background: #e74c3c;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.9rem;
        transition: background 0.3s ease;
    }

    .logout-btn:hover {
        background: #c0392b;
    }

    /* Seção de Perfis */
    .profiles-section {
        display: none;
    }

    .section-title {
        text-align: center;
        color: white;
        font-size: 2.2rem;
        font-weight: 300;
        margin-bottom: 40px;
        text-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }

    .profile-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 40px;
        text-align: center;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        max-width: 500px;
        margin: 0 auto;
        position: relative;
        overflow: hidden;
    }

    .profile-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #ff6b6b, #4ecdc4, #45b7d1, #96ceb4);
    }

    .profile-image {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        margin: 0 auto 25px;
        border: 5px solid #f8f9fa;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }

    .profile-name {
        font-size: 2rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 10px;
    }

    .profile-role {
        font-size: 1.2rem;
        color: #667eea;
        margin-bottom: 20px;
        font-weight: 600;
    }

    .profile-details {
        text-align: left;
        margin: 30px 0;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
        padding: 12px;
        background: #f8f9fa;
        border-radius: 10px;
    }

    .detail-icon {
        font-size: 1.2rem;
        width: 25px;
    }

    .detail-text {
        color: #555;
        font-size: 1rem;
    }

    .error-message {
        color: #e74c3c;
        text-align: center;
        margin-top: 15px;
        font-size: 0.9rem;
    }

    .welcome-message {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 40px;
        text-align: center;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }

    .welcome-message h2 {
        color: #2c3e50;
        font-size: 2rem;
        margin-bottom: 15px;
    }

    .welcome-message p {
        color: #7f8c8d;
        font-size: 1.1rem;
    }

    @media (max-width: 768px) {
        .header {
            flex-direction: column;
            gap: 20px;
            text-align: center;
        }

        .modal-content {
            padding: 30px 20px;
        }

        .profile-card {
            padding: 30px 20px;
        }
    }
</style>

</head>
<body>
<div class="container">
<!-- Header com Logo da Empresa -->
<div class="header">
<div class="company-logo">
<img src="img/logo-empresa.png" alt="Logo TechCorp" class="logo-img">
<div class="company-info">
<h1>TechCorp Solutions</h1>
<p>Inovação e Tecnologia</p>
</div>
</div>

        <div class="auth-section">
            <div class="user-info" id="userInfo">
                <img src="" alt="Avatar" class="user-avatar" id="userAvatar">
                <span id="userName"></span>
                <button class="logout-btn" onclick="logout()">Sair</button>
            </div>
            <button class="access-btn" id="accessBtn" onclick="showLogin()">Acessar Perfil</button>
        </div>
    </div>

    <!-- Mensagem de Boas-vindas -->
    <div class="welcome-message" id="welcomeMessage">
        <h2>Bem-vindo à TechCorp Solutions</h2>
        <p>Faça login para acessar seu perfil e informações da equipe</p>
    </div>

    <!-- Seção de Perfil Individual -->
    <div class="profiles-section" id="profileSection">
        <h2 class="section-title">Meu Perfil</h2>
        <div class="profile-card" id="profileCard">
            <!-- Conteúdo será carregado dinamicamente -->
        </div>
    </div>
</div>

<!-- Modal de Login -->
<div class="login-modal" id="loginModal" style="display: none;">
    <div class="modal-content">
        <h2 class="modal-title">Acesso ao Perfil</h2>
        <form id="loginForm">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="password">Senha:</label>
                <input type="password" id="password" required>
            </div>
            <button type="submit" class="login-btn">Entrar</button>
            <div class="error-message" id="errorMessage"></div>
        </form>
    </div>
</div>

<script>
    // Base de dados dos usuários
    const users = {
        'joao.silva@techcorp.com': {
            password: '123456',
            name: 'João Silva',
            role: 'Desenvolvedor Frontend',
            image: 'img/homem1.jpg',
            email: 'joao.silva@techcorp.com',
            phone: '+55 11 98765-4321',
            department: 'Desenvolvimento',
            joined: 'Janeiro 2020',
            skills: 'React, Vue.js, JavaScript, TypeScript',
            description: 'Especialista em desenvolvimento frontend com mais de 5 anos de experiência criando interfaces modernas e responsivas.'
        },
        'ana.costa@techcorp.com': {
            password: '123456',
            name: 'Ana Costa',
            role: 'UI/UX Designer',
            image: 'img/mulher1.jpg',
            email: 'ana.costa@techcorp.com',
            phone: '+55 11 98765-4322',
            department: 'Design',
            joined: 'Março 2021',
            skills: 'Figma, Adobe XD, Design Thinking, Prototipagem',
            description: 'Designer criativa focada em experiência do usuário, com expertise em design thinking e prototipagem.'
        },
        'carlos.santos@techcorp.com': {
            password: '123456',
            name: 'Carlos Santos',
            role: 'Desenvolvedor Backend',
            image: 'img/homem2.jpg',
            email: 'carlos.santos@techcorp.com',
            phone: '+55 11 98765-4323',
            department: 'Desenvolvimento',
            joined: 'Setembro 2019',
            skills: 'Node.js, Python, MongoDB, AWS',
            description: 'Engenheiro de software especializado em Node.js e Python, com foco em arquiteturas escaláveis e APIs robustas.'
        },
        'maria.oliveira@techcorp.com': {
            password: '123456',
            name: 'Maria Oliveira',
            role: 'Gerente de Projetos',
            image: 'img/mulher2.jpg',
            email: 'maria.oliveira@techcorp.com',
            phone: '+55 11 98765-4324',
            department: 'Gestão',
            joined: 'Fevereiro 2018',
            skills: 'Scrum, Kanban, Jira, Gestão de Equipes',
            description: 'Profissional certificada em metodologias ágeis, com experiência em liderar equipes multidisciplinares.'
        }
    };

    let currentUser = null;

    function showLogin() {
        document.getElementById('loginModal').style.display = 'flex';
    }

    function hideLogin() {
        document.getElementById('loginModal').style.display = 'none';
        document.getElementById('errorMessage').textContent = '';
    }

    function login(email, password) {
        const user = users[email];
        if (user && user.password === password) {
            currentUser = user;
            hideLogin();
            showProfile();
            return true;
        }
        return false;
    }

    function logout() {
        currentUser = null;
        document.getElementById('profileSection').style.display = 'none';
        document.getElementById('welcomeMessage').style.display = 'block';
        document.getElementById('userInfo').style.display = 'none';
        document.getElementById('accessBtn').style.display = 'block';
    }

    function showProfile() {
        if (!currentUser) return;

        // Atualizar header
        document.getElementById('userAvatar').src = currentUser.image;
        document.getElementById('userName').textContent = currentUser.name;
        document.getElementById('userInfo').style.display = 'flex';
        document.getElementById('accessBtn').style.display = 'none';
        document.getElementById('welcomeMessage').style.display = 'none';

        // Mostrar perfil
        document.getElementById('profileSection').style.display = 'block';

        // Carregar dados do perfil
        const profileCard = document.getElementById('profileCard');
        profileCard.innerHTML = `
            <img src="${currentUser.image}" alt="${currentUser.name}" class="profile-image">
            <h3 class="profile-name">${currentUser.name}</h3>
            <p class="profile-role">${currentUser.role}</p>

            <div class="profile-details">
                <div class="detail-item">
                    <span class="detail-icon">📧</span>
                    <span class="detail-text">${currentUser.email}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-icon">📱</span>
                    <span class="detail-text">${currentUser.phone}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-icon">🏢</span>
                    <span class="detail-text">${currentUser.department}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-icon">📅</span>
                    <span class="detail-text">Desde ${currentUser.joined}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-icon">🛠️</span>
                    <span class="detail-text">${currentUser.skills}</span>
                </div>
            </div>

            <p style="color: #666; line-height: 1.6; margin-top: 20px; font-style: italic;">
                "${currentUser.description}"
            </p>
        `;
    }

    // Event listeners
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        if (login(email, password)) {
            document.getElementById('email').value = '';
            document.getElementById('password').value = '';
        } else {
            document.getElementById('errorMessage').textContent = 'Email ou senha incorretos';
        }
    });

    // Fechar modal clicando fora
    document.getElementById('loginModal').addEventListener('click', function(e) {
        if (e.target === this) {
            hideLogin();
        }
    });
</script>

</body>
</html>