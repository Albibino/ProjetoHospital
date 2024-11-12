document.addEventListener('DOMContentLoaded', function() {
    function carregarQuestao() {
        fetch('index.php?action=obter_questao')
            .then(response => response.json())
            .then(data => {
                if (data) {
                    exibirQuestao(data);
                }
            });
    }

    function exibirQuestao(questao) {
        const container = document.getElementById('avaliacao-container');
        container.innerHTML = `
            <div class="containersup">
                <div class="textosup">
                    <h1>Avaliação de Serviço</h1>
                    <p>${questao.texto}</p>
                </div>
                <div class="slider-container">
                    <input type="range" min="0" max="10" value="5" id="slider">
                    <div class="value-display">
                        <p><span id="sliderValue">5</span></p>
                    </div>
                </div>
                <div class="button-container">
                    <button id="btnEnviar">Enviar</button>
                </div>
            </div>
        `;

        const slider = document.getElementById('slider');
        const output = document.getElementById('sliderValue');

        slider.oninput = function() {
            output.innerHTML = this.value;
        }

        document.getElementById('btnEnviar').onclick = function() {
            enviarResposta(questao.id, slider.value);
        }
    }

    function exibirFooter (){
        const container = document.getElementById('footer');
        container.innerHTML = `
                <div class="footer-content">
                    <p>&copy; 2024 William Wollert. Todos os direitos reservados.</p>
                </div>
        `;
    }

    function enviarResposta(questaoId, resposta) {
        fetch('index.php?action=processar_resposta', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                questao_id: questaoId,
                resposta: resposta
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.fim) {
                window.location.href = 'index.php?action=obrigado';
            } else if (data.erro) {
                alert(data.erro);
            } else {
                exibirQuestao(data);
            }
        });
    }
    carregarQuestao();
    exibirFooter();
});