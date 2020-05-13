<?php
    class dbGlobal{

        private $host = '10.1.1.20';
        private $db = 'rrhh';
        private $user = 'soporte';
        private $password = '4c3r04dm1n';

        public function setDataConn($host,$db,$user,$password) {
            $this->host = $host;
            $this->db = $db;
            $this->user = $user;
            $this->password = $user;
        }

        public function changeDB($db){
            $this->db = $db;
        }

        // conexion a la BD
        public function conectar(){

            $conexion_mysql = "mysql:host=$this->host;dbname=$this->db";
            $conexion_db = new PDO($conexion_mysql, $this->user, $this->password);
            $conexion_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

            $conexion_db-> exec("set names utf8");

            return $conexion_db;
        }
    }
?>