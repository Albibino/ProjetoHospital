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
    case 'exibir_formulario_cadastro':
        $controller->exibirFormularioCadastro();
        break;
    case 'salvar_questao':
        $controller->salvarQuestao();
        break;
    case 'obrigado':
        $controller->exibirObrigado();
        break;
    case 'listar_questoes':
        $controller->listarQuestoes();
        break;
    case 'editar_questao':
        $controller->editarQuestao();
        break;  
    case 'atualizar_questao':
        $controller->atualizarQuestao();
        break;      
    case 'deletar_questao':
        $controller->deletarQuestao();
        break;
    case 'exibir_login':
        $controller->exibirLogin();
        break;
    case 'login':
        $controller->processarLogin();
        break;
    case 'logout':
        $controller->logout();
        break;
    case 'painel_admin':
        $controller->exibirPainelAdmin();
        break;
    case 'exibir_formulario':
    default: 
        $controller->exibirFormulario();
        break;
}