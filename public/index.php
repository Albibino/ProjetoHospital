<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliação de Serviço - HRAV</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <script src="js/script.js"></script>
    <main>
        <form id="avaliacaoForm" method="post" action="php\proc_avaliacao.php">
            <div class="containersup">
                <div class="textosup">
                    <h1>Avaliação de Serviço</h1>
                    <p>Por favor, avalie nosso serviço:</p>
                </div>
                <div class="slider-container">
                    <input type="range" min="0" max="10" value="5" id="slider">
                    <div class="value-display">
                        <p><span id="sliderValue"></span></p>
                    </div>
                </div>
                <input type="hidden" name="nota" id="notaInput">
                <div class="button-container">
                    <button id="btnLimpar" type="button">Limpar</button>
                    <button id="btnEnviar" type="submit">Enviar</button>
                </div>
            </div>
        </form>
    </main>
    <footer>
        <div class="footer-content">
            <p>&copy; 2024 William Wollert. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>
</html>