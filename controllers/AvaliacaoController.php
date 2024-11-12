<?php
require_once 'models/AvaliacaoModel.php';

class AvaliacaoController {
    private $model;

    public function __construct() {
        $this->model = new AvaliacaoModel();
    }

    public function exibirFormulario() {
        session_start();
        $_SESSION['questao_atual'] = 1;
        $_SESSION['total_questoes'] = $this->model->getTotalQuestoes();
        include 'views/avaliacao_form.php';
    }

    public function obterQuestao() {
        session_start();
        $questao_id = $_SESSION['questao_atual'];
        $questao = $this->model->getQuestao($questao_id);
        echo json_encode($questao);
    }

    public function processarResposta() {
        session_start();
        $dados = json_decode(file_get_contents('php://input'), true);
        $questao_id = $dados['questao_id'];
        $resposta = intval($dados['resposta']);

        if (!$this->model->questaoExiste($questao_id) || $resposta < 0 || $resposta > 10) {
            echo json_encode(['erro' => 'Dados inválidos.']);
            return;
        }

        $this->model->salvarResposta($questao_id, $resposta);

        $_SESSION['questao_atual']++;

        if ($_SESSION['questao_atual'] > $_SESSION['total_questoes']) {
            echo json_encode(['fim' => true]);
        } else {
            $questao = $this->model->getQuestao($_SESSION['questao_atual']);
            echo json_encode($questao);
        }
    }

    public function exibirObrigado() {
        include 'views/obrigado.php';
    }
}