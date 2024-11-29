<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Obrigado - HRAV</title>
    <link rel="stylesheet" type="text/css" href="css/styles2.css">
    <script>
        const totalTime = 9500;

        function atualizarBarra() {
            const progressBar = document.getElementById('progress-bar');
            const interval = 100;
            let elapsedTime = 0;

            const timer = setInterval(() => {
                elapsedTime += interval;
                const progress = 100 - (elapsedTime / totalTime) * 100;
                progressBar.style.width = `${progress}%`;

                if (elapsedTime >= totalTime) {
                    clearInterval(timer);
                    window.location.href = 'index.php';
                }
            }, interval);
        }

        window.onload = atualizarBarra;
    </script>
</head>
<body>
    <main>
    <div class="container">
    <div class="caixa-texto">
        <h1 id="titulo">Agradecemos sua Avaliação!</h1>
        <p id="texto">O Hospital Regional Alto Vale (HRAV) agradece sua resposta e ela é muito importante para nós, pois nos ajuda a melhorar continuamente nossos serviços.</p>
    </div>
    <div class="progress-container">
        <div id="progress-bar"></div>
    </div>
</div>
        </div>
    </main>
</body>
</html>
