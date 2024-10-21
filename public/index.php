<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="William Wollert - BSN T28 - UNIDAVI">
    <meta name="description" content="Trabalho semestral de Desenvolvimento WEB 1 - UNIDAVI">
    <meta name="keywords" content="HRAV, CSS, HTML, PHP, SQL, UNIDAVI, BSN">
    <title>Avaliação de Serviço - HRAV</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <script src="js/script.js"></script>
    <main>
        <div class="containersup">
            <div class="textosup">
                <h1>Avaliação de Serviço</h1>
                <p>Por favor, avalie nosso serviço:</p>
            </div>
            <div class="slider-container">
                <input type="range" min="0" max="10" value="" id="slider">
                <div class="value-display">
                    <p><span id="sliderValue"></span></p>
                </div>
            </div class="caixainf">
                <div class="button-container">
                <button id="btnLimpar" type="button">Limpar</button>
                <button id="btnEnviar" type="button">Enviar</button>
            </div>
        </div>
    </main>
    <footer>
        <div class="footer-content">
            <p>&copy; 2024 William Wollert. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>
</html>