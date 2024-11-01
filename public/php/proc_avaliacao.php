<?php
$host = 'localhost';
$port = '5432';
$dbname = 'projetohospital';
$user = 'postgres';
$password = 'postgres';

$conn_string = "host=$host port=$port dbname=$dbname user=$user password=$password";
$dbconn = pg_connect($conn_string);

if (!$dbconn) {
    die("Erro: Não foi possível conectar ao banco de dados.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nota = isset($_POST['nota']) ? intval($_POST['nota']) : null;

    if ($nota !== null && $nota >= 0 && $nota <= 10) {
        $query = "INSERT INTO avaliacoes (nota) VALUES ($1)";
        $result = pg_query_params($dbconn, $query, array($nota));

        if ($result) {
            header('Location: obrigado.php');
            exit();
        } else {
            echo 'Erro ao registrar a avaliação.';
        }
    } else {
        echo 'Nota inválida.';
    }
} else {
    echo 'Método de requisição inválido.';
}

pg_close($dbconn);