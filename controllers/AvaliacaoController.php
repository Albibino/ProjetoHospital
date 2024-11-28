<?php
require_once 'models/AvaliacaoModel.php';

class AvaliacaoController {
    private $model;

    public function __construct() {
        $session_options = [
            'cookie_httponly' => true,
            'cookie_samesite' => 'Strict',
            'use_strict_mode' => true,
        ];
    
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            $session_options['cookie_secure'] = true;
        }
    
        if (!isset($_SESSION)) {
            session_start($session_options);
        }
    
        $this->model = new AvaliacaoModel();
    }
    

    public function exibirFormulario() {
        if (!isset($_SESSION)) {
            session_start([
                'cookie_httponly' => true,
                'cookie_samesite' => 'Strict',
                'use_strict_mode' => true,
                'cookie_secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'
            ]);
        }
    
        $_SESSION['questao_atual'] = 1;
        $_SESSION['total_questoes'] = $this->model->getTotalQuestoes();
        include 'views/avaliacao_form.php';
    }

    public function obterQuestao() {
        if (!isset($_SESSION)) {
            session_start();
        }
    
        if (!isset($_SESSION['questao_atual'])) {
            http_response_code(400); 
            echo json_encode(['erro' => 'Sessão expirada ou inválida.']);
            exit;
        }
    
        $questao_id = $_SESSION['questao_atual'];
    
        if (!filter_var($questao_id, FILTER_VALIDATE_INT)) {
            http_response_code(400);
            echo json_encode(['erro' => 'ID da questão inválido.']);
            exit;
        }
    
        try {
            $questao = $this->model->getQuestao($questao_id);
    
            if ($questao === false) {
                http_response_code(404);
                echo json_encode(['erro' => 'Questão não encontrada.']);
                exit;
            }
    
            header('Content-Type: application/json');
            echo json_encode($questao);
    
        } catch (Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao obter a questão.']);
            exit;
        }
    }
    
    public function processarResposta() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['erro' => 'Método não permitido.']);
            exit;
        }
    
        if (!isset($_SESSION)) {
            session_start();
        }
    
        $dados = json_decode(file_get_contents('php://input'), true);
    
        if (empty($dados['questao_id']) || !filter_var($dados['questao_id'], FILTER_VALIDATE_INT)) {
            http_response_code(400);
            echo json_encode(['erro' => 'ID da questão inválido.']);
            exit;
        }
    
        if (!isset($dados['resposta']) || !filter_var($dados['resposta'], FILTER_VALIDATE_INT)) {
            http_response_code(400);
            echo json_encode(['erro' => 'Resposta inválida.']);
            exit;
        }
    
        $questao_id = $dados['questao_id'];
        $resposta = $dados['resposta'];
    
        try {
            $this->model->salvarResposta($questao_id, $resposta);
        } catch (Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao salvar a resposta.']);
            exit;
        }
    
        $_SESSION['questao_atual']++;
    
        if ($_SESSION['questao_atual'] > $_SESSION['total_questoes']) {
            echo json_encode(['fim' => true]);
            exit;
        }
    
        try {
            $proxima_questao = $this->model->getQuestao($_SESSION['questao_atual']);
    
            if ($proxima_questao === false) {
                http_response_code(404);
                echo json_encode(['erro' => 'Próxima questão não encontrada.']);
                exit;
            }
    
            echo json_encode($proxima_questao);
    
        } catch (Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao carregar a próxima questão.']);
            exit;
        }
    }

    public function exibirFormularioCadastro() {
        include 'views/cadastrar_questao.php';
    }
    
    public function salvarQuestao() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Método não permitido
            echo json_encode(['erro' => 'Método não permitido.']);
            exit;
        }
    
        if (!isset($_POST['texto']) || empty(trim($_POST['texto']))) {
            http_response_code(400); // Bad Request
            echo json_encode(['erro' => 'Texto da questão é obrigatório.']);
            exit;
        }
    
        $texto = trim($_POST['texto']);
    
        try {
            $this->model->adicionarQuestao($texto);
            echo json_encode(['sucesso' => 'Questão cadastrada com sucesso.']);
        } catch (Exception $e) {
            error_log($e->getMessage());
            http_response_code(500); // Internal Server Error
            echo json_encode(['erro' => 'Erro ao salvar a questão.']);
        }
    }
    
    
    public function exibirObrigado() {
        include 'views/obrigado.php';
    }
}