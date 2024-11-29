<?php
class AvaliacaoModel {
    private $dbconn;

    public function __construct() {
        $config = include('config/database.php');
    
        $conn_string = "host={$config['host']} port={$config['port']} dbname={$config['dbname']} user={$config['user']} password={$config['password']}";
        $this->dbconn = @pg_connect($conn_string);
        if (!$this->dbconn) {
            throw new Exception("Não foi possível conectar ao banco de dados.");
        }
    }

    public function getDbConnection() {
        return $this->dbconn;
    }
    
    public function getQuestao($id) {
        $query = "SELECT * FROM questoes WHERE id = $1";
        $result = pg_query_params($this->dbconn, $query, [$id]);
    
        if (!$result || pg_num_rows($result) === 0) {
            return false;
        }
    
        return pg_fetch_assoc($result);
    }
    
    

    public function salvarResposta($questao_id, $resposta) {
        $query = "INSERT INTO respostas (questao_id, resposta) VALUES ($1, $2)";
        $result = pg_query_params($this->dbconn, $query, [$questao_id, $resposta]);
        if (!$result) {
            throw new Exception('Erro ao salvar a resposta no banco de dados.');
        }
    }
    

    public function adicionarQuestao($texto, $tipo = 'slider') {
        $query = "INSERT INTO questoes (texto, tipo) VALUES ($1, $2)";
        $result = pg_query_params($this->dbconn, $query, [$texto, $tipo]);
        if (!$result) {
            throw new Exception('Erro ao inserir a questão no banco de dados.');
        }
    }

    public function getTotalQuestoes() {
        $query = "SELECT COUNT(*) AS total FROM questoes";
        $result = pg_query($this->dbconn, $query);
        $row = pg_fetch_assoc($result);
        return intval($row['total']);
    }

    public function questaoExiste($id) {
        $query = "SELECT COUNT(*) FROM questoes WHERE id = $1";
        $result = pg_query_params($this->dbconn, $query, array($id));
        $row = pg_fetch_row($result);
        return $row[0] > 0;
    }

    public function __destruct() {
        pg_close($this->dbconn);
    }

    public function editarQuestao($id, $novoTexto, $tipo) {
        if (!is_numeric($id)) {
            throw new Exception('ID inválido fornecido. Deve ser um número.');
        }
    
        if (empty(trim($novoTexto))) {
            throw new Exception('O texto da questão não pode estar vazio.');
        }
    
        if (!in_array($tipo, ['slider', 'texto'])) {
            throw new Exception('Tipo de questão inválido. Deve ser "slider" ou "texto".');
        }
    
        error_log("Chamando editarQuestao com ID: $id, Texto: $novoTexto, Tipo: $tipo");
    
        $query = "UPDATE questoes SET texto = $1, tipo = $2 WHERE id = $3";
        
        $result = pg_query_params($this->dbconn, $query, [$novoTexto, $tipo, $id]);
    
        if (!$result) {
            $pgError = pg_last_error($this->dbconn);
            error_log("Erro ao executar a query no banco de dados: $pgError");
            throw new Exception("Erro ao atualizar a questão no banco de dados: $pgError");
        }
    
        error_log('Questão atualizada com sucesso no banco de dados.');
    }
    
    public function excluirQuestao($id) {
        $query = "DELETE FROM questoes WHERE id = $1";
        $result = pg_query_params($this->dbconn, $query, [$id]);
        if (!$result) {
            throw new Exception('Erro ao excluir a questão no banco de dados.');
        }
    }
    
    public function getTodasQuestoes() {
        $query = "SELECT * FROM questoes ORDER BY id";
        $result = pg_query($this->dbconn, $query);
        if (!$result) {
            throw new Exception('Erro ao obter as questões.');
        }
        $questoes = [];
        while ($row = pg_fetch_assoc($result)) {
            $questoes[] = $row;
        }
        return $questoes;
    }
}