<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Questão - HRAV</title>
    <link rel="stylesheet" type="text/css" href="css/styles3.css">
</head>
<body>
    <main>
        <h1>Cadastrar Nova Questão</h1>
            <form id="formCadastrarQuestao" method="POST" action="index.php?action=salvar_questao">
                <div>
                    <label for="texto">Texto da Questão:</label>
                    <textarea id="texto" name="texto" rows="4" cols="50" required></textarea>
                </div>
                <div>
                    <label for="tipo">Tipo da Questão:</label>
                    <select id="tipo" name="tipo">
                        <option value="slider">Controle Deslizante</option>
                        <option value="texto">Texto Opcional</option>
                    </select>
                </div>
                <div>
                    <button type="submit">Cadastrar Questão</button>
                </div>
            </form>
        <div id="mensagem"></div>
    </main>
    <script>
        const form = document.getElementById('formCadastrarQuestao');
        const mensagem = document.getElementById('mensagem');

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(form);

            fetch('index.php?action=salvar_questao', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.erro) {
                    mensagem.textContent = data.erro;
                    mensagem.style.color = 'red';
                } else {
                    mensagem.textContent = data.sucesso;
                    mensagem.style.color = 'green';
                    form.reset();
                }
            })
            .catch(error => {
                console.error('Erro ao cadastrar a questão:', error);
                mensagem.textContent = 'Erro ao cadastrar a questão. Por favor, tente novamente.';
                mensagem.style.color = 'red';
            });
        });
    </script>
</body>
</html>