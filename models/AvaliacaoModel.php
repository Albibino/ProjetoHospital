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
    

    public function getQuestao($id) {
        $query = "SELECT id, texto, tipo FROM questoes WHERE id = $1";
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
}