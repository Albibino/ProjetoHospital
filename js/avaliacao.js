document.addEventListener('DOMContentLoaded', function() {
    function carregarQuestao() {
        fetch('index.php?action=obter_questao')
            .then(response => response.json())
            .then(data => {
                if (data) {
                    exibirQuestao(data);
                }
            })
            .catch(error => {
                console.error('Erro ao carregar a questão:', error);
                alert('Ocorreu um erro ao carregar a questão. Por favor, tente novamente mais tarde.');
            });
    }

    function exibirQuestao(questao) {
        const container = document.getElementById('avaliacao-container');
        let questionHTML = `
            <div class="containersup">
            <h1>Avaliação de Serviço</h1>
                <div class="textosup"> 
                    <p>${questao.texto}</p>
                </div>
        `;
    
        if (questao.tipo === 'slider') {
            questionHTML += `
                <div class="slider-container">
                    <input type="range" min="0" max="10" value="0" id="slider">
                    <div class="value-display">
                        <p><span id="sliderValue">0</span></p>
                    </div>
                </div>
            `;
        } else if (questao.tipo === 'texto') {
            questionHTML += `
                <div class="text-container">
                    <textarea id="textoResposta" placeholder="Digite sua resposta (opcional)..."></textarea>
                </div>
            `;
        }
    
        questionHTML += `
                <div class="button-container">
                    <button id="btnEnviar">Próxima pergunta</button>
                </div>
            </div>
        `;
    
        container.innerHTML = questionHTML;
    
        if (questao.tipo === 'slider') {
            const slider = document.getElementById('slider');
            const output = document.getElementById('sliderValue');
            atualizarCorSlider(slider);
    
            slider.oninput = function() {
                output.innerHTML = this.value;
                atualizarCorSlider(this);
            };
        }
    
        document.getElementById('btnEnviar').onclick = function() {
            const resposta = questao.tipo === 'slider'
                ? document.getElementById('slider').value
                : document.getElementById('textoResposta').value;
    
            enviarResposta(questao.id, resposta);
        };
    }
    

    function interpolarCores(corInicio, corFim, porcentagem) {
        corInicio = corInicio.replace('#', '');
        corFim = corFim.replace('#', '');
        const rInicio = parseInt(corInicio.substring(0,2), 16);
        const gInicio = parseInt(corInicio.substring(2,4), 16);
        const bInicio = parseInt(corInicio.substring(4,6), 16);
        const rFim = parseInt(corFim.substring(0,2), 16);
        const gFim = parseInt(corFim.substring(2,4), 16);
        const bFim = parseInt(corFim.substring(4,6), 16);
        const rInterpolado = Math.round(rInicio + (rFim - rInicio) * porcentagem);
        const gInterpolado = Math.round(gInicio + (gFim - gInicio) * porcentagem);
        const bInterpolado = Math.round(bInicio + (bFim - bInicio) * porcentagem);
        const rHex = ('0' + rInterpolado.toString(16)).slice(-2);
        const gHex = ('0' + gInterpolado.toString(16)).slice(-2);
        const bHex = ('0' + bInterpolado.toString(16)).slice(-2);

        return `#${rHex}${gHex}${bHex}`;
    }

    function atualizarCorSlider(slider) {
        const valor = slider.value;
        const porcentagem = (valor - slider.min) / (slider.max - slider.min);
        const corInicio = '#f44336';
        const corFim = '#4CAF50';
        const corInterpolada = interpolarCores(corInicio, corFim, porcentagem);
        const cor = `linear-gradient(90deg, ${corInterpolada} ${porcentagem * 100}%, #ddd ${porcentagem * 100}%)`;
        slider.style.background = cor;
    }

    function exibirFooter (){
        const container = document.getElementById('footer');
        container.innerHTML = `
                <div class="footer-content">
                    <p>Sua avaliação espontânea é anônima, nenhuma informação pessoal é solicitada ou armazenada.</p>
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
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro na resposta do servidor: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.fim) {
                window.location.href = 'index.php?action=obrigado';
            } else if (data.erro) {
                alert('Erro: ' + data.erro);
            } else {
                exibirQuestao(data);
            }
        })
        .catch(error => {
            console.error('Erro ao enviar a resposta:', error);
            alert('Ocorreu um erro ao enviar a resposta. Por favor, tente novamente mais tarde.');
        });
    }
     
    carregarQuestao();
    exibirFooter();
});