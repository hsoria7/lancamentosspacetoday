<?php

class Database {
    private $driver;
    private $host;
    private $dbname;
    private $username;
    private $password;

    private $con;

    function __construct() {
        $this->driver = "mysql";
        $this->host = "localhost";
        $this->dbname = "lancamentos_space_x";
        $this->username = "root";
        $this->password = "";
    }

    function getConexao() {
        try {
            $this->con = new PDO(
                "{$this->driver}:host={$this->host}:3307;dbname={$this->dbname};charset=utf8",
                $this->username,
                $this->password
            );

            // Define o modo de erros como exceções
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $this->con;
        } catch (PDOException $e) {
            // Log ou mensagem customizada (não exponha informações sensíveis)
            error_log("Erro de conexão: " . $e->getMessage());
            echo "Erro ao conectar ao banco de dados.";
            return false;
        }
    }
}
