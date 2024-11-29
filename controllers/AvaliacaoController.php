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
        $questao_id = $dados['questao_id'];
        $resposta = isset($dados['resposta']) ? trim($dados['resposta']) : null;
        $questao = $this->model->getQuestao($questao_id);

        if (!$questao) {
            http_response_code(404);
            echo json_encode(['erro' => 'Questão não encontrada.']);
            exit;
        }
    
        if ($questao['tipo'] === 'slider') {
            if (!is_numeric($resposta) || $resposta < 0 || $resposta > 10) {
                http_response_code(400);
                echo json_encode(['erro' => 'Resposta inválida para pergunta do tipo slider.']);
                exit;
            }
        } elseif ($questao['tipo'] === 'texto') {
            if (strlen($resposta) > 255) {
                http_response_code(400);
                echo json_encode(['erro' => 'Resposta de texto muito longa.']);
                exit;
            }
        } else {
            http_response_code(400);
            echo json_encode(['erro' => 'Tipo de pergunta desconhecido.']);
            exit;
        }
    
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
            if (!$proxima_questao) {
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
        $this->verificarAutenticacao();
        include 'views/cadastrar_questao.php';
    }
    
    
    public function salvarQuestao() {
        $this->verificarAutenticacao();
    
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo 'Método não permitido.';
            exit;
        }
    
        $texto = $_POST['texto'] ?? null;
        $tipo = $_POST['tipo'] ?? null;
    
        if (empty(trim($texto)) || !in_array($tipo, ['slider', 'texto'])) {
            echo 'Todos os campos são obrigatórios e o tipo deve ser válido.';
            exit;
        }
    
        try {
            $this->model->adicionarQuestao($texto, $tipo);
            header('Location: index.php?action=listar_questoes');
            exit;
        } catch (Exception $e) {
            error_log("Erro ao salvar a questão: " . $e->getMessage());
            echo 'Erro ao salvar a questão.';
        }
    }
    
    public function listarQuestoes() {
        $this->verificarAutenticacao();
        $questoes = $this->model->getTodasQuestoes();
        include 'views/listar_questoes.php';
    }
    
    
    public function exibirObrigado() {
        include 'views/obrigado.php';
    }

    public function editarQuestao() {
        if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
            header('Location: index.php');
            exit;
        }
        $id = $_GET['id'];
        try {
            $questao = $this->model->getQuestao($id);
            if (!$questao) {
                header('Location: index.php?action=listar_questoes');
                exit;
            }
            include 'views/editar_questao.php';
        } catch (Exception $e) {
            error_log("Erro ao carregar a questão: " . $e->getMessage());
            header('Location: index.php');
            exit;
        }
    }
    
    public function atualizarQuestao() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo 'Método não permitido.';
            exit;
        }
    
        $id = $_POST['id'] ?? null;
        $novoTexto = $_POST['texto'] ?? null;
        $tipo = $_POST['tipo'] ?? null;
    
        if (!$id || !is_numeric($id)) {
            http_response_code(400);
            echo 'ID inválido.';
            exit;
        }
    
        if (empty(trim($novoTexto))) {
            http_response_code(400);
            echo 'O texto da questão não pode estar vazio.';
            exit;
        }
    
        if (!in_array($tipo, ['slider', 'texto'])) {
            http_response_code(400);
            echo 'Tipo de questão inválido.';
            exit;
        }
    
        try {
            $this->model->editarQuestao($id, $novoTexto, $tipo);
            header('Location: index.php?action=listar_questoes');
            exit;
        } catch (Exception $e) {
            error_log("Erro ao atualizar a questão: " . $e->getMessage());
            http_response_code(500);
            echo 'Erro ao atualizar a questão. Por favor, tente novamente mais tarde.';
        }
    }
    
    public function deletarQuestao() {
        $id = $_GET['id'] ?? null;
    
        if (!$id) {
            echo 'ID da questão não fornecido.';
            exit;
        }
    
        try {
            $this->model->excluirQuestao($id);
            header('Location: index.php?action=listar_questoes');
        } catch (Exception $e) {
            error_log($e->getMessage());
            echo 'Erro ao excluir a questão.';
        }
    }

    public function exibirLogin() {
        include 'views/login.php';
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: index.php');
        exit;
    }
    
    
    public function processarLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo 'Método não permitido.';
            exit;
        }
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        if (empty($username) || empty($password)) {
            echo 'Usuário e senha são obrigatórios.';
            exit;
        }
        try {
            $query = "SELECT * FROM usuarios WHERE username = $1 AND password = $2";
            $result = pg_query_params($this->model->getDbConnection(), $query, [$username, $password]);
            $usuario = pg_fetch_assoc($result);
            if ($usuario) {
                session_start();
                $_SESSION['admin'] = true;
                $_SESSION['username'] = $usuario['username'];
                header('Location: index.php?action=painel_admin');
                exit;
            } else {
                echo 'Usuário ou senha inválidos.';
                exit;
            }
        } catch (Exception $e) {
            error_log("Erro ao processar login: " . $e->getMessage());
            http_response_code(500);
            echo 'Erro interno. Por favor, tente novamente mais tarde.';
        }
    }

    private function verificarAutenticacao() {
        if (empty($_SESSION['admin'])) {
            header('Location: index.php?action=exibir_login');
            exit;
        }
    }

    public function exibirPainelAdmin() {
        $this->verificarAutenticacao();
        include 'views/admin_painel.php';
    } 
}