<?php
require_once 'controllers/AvaliacaoController.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'exibir_formulario';

$controller = new AvaliacaoController();

switch ($action) {
    case 'obter_questao':
        $controller->obterQuestao();
        break;
    case 'processar_resposta':
        $controller->processarResposta();
        break;
    case 'obrigado':
        $controller->exibirObrigado();
        break;
    case 'exibir_formulario':
    default:
        $controller->exibirFormulario();
        break;
}