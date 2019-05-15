<?php
    class Database {
        private $host = 'localhost';
        private $dbname = 'video_annotation';
        private $username = 'root';
        private $password = '';
        private $charset = 'utf8';
        private $connection;
        
        public function getConnection() {
            $this->connection = null;

            try {
                $this->connection = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=' . $this->charset, $this->username, $this->password);
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch(PDOException $e) {
                echo 'Connection error: ' . $e->getMessage();
            }

            return $this->connection;
        }
    }
    