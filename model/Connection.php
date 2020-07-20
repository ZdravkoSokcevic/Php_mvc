<?php
    trait Connection
    {
        private $connection;
        private $conn_string;
        private function getConnString()
        {
            
        }
        private function connection()
        {
            $this->connection= new Pdo();
        }
        private function getConnection()
        {
            return $this->connection();
        }
    }

?>