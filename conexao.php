<?php

function getConexao() {
    $hostname = 'localhost';
    $database = 'lancamentos_space_x';
    $user = 'root';
    $password = '';

        $connection = new mysqli($hostname, $user, $password, $database);

    if ($connection->connect_error) {
        die('Erro ao conectar ao banco de dados: ' . $connection->connect_error);
    }

    return $connection;
}
?>



