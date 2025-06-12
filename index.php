<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AUGEBIT - Sistema de Gestão</title>
<link rel="icon" href="img/favicon.ico" type="image/x-icon">
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    background: #000;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.video-container {
    width: 100%;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.intro-video {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.skip-button {
    position: absolute;
    top: 30px;
    right: 30px;
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.3);
    padding: 10px 20px;
    border-radius: 25px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 14px;
}

.skip-button:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
}

.fade-out {
    opacity: 0;
    transition: opacity 0.5s ease;
}

@media (max-width: 768px) {
    .skip-button {
        top: 20px;
        right: 20px;
        padding: 8px 16px;
        font-size: 12px;
    }
}
</style>
</head>
<body>
<div class="video-container">
    <video class="intro-video" id="introVideo" autoplay muted>
        <source src="img/AUGEBIT fundo claro.mp4" type="video/mp4">
        <source src="img/AUGEBIT fundo claro.webm" type="video/webm">
        Seu navegador não suporta o elemento de vídeo.
    </video>
    <button class="skip-button" onclick="goToLogin()">Pular</button>
</div>

<script>
    const introVideo = document.getElementById('introVideo');

    introVideo.addEventListener('ended', goToLogin);

    function goToLogin() {
        document.body.classList.add('fade-out');
        setTimeout(() => {
            window.location.href = 'telaLogin.php';
        }, 500);
    }
</script>
</body>
</html>
