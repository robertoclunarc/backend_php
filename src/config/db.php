<?php
    class db {

        private $host = ""; //'10.1.1.32';
       // private $host = 'localhost';
        private $db = ""; //'intranet';
        private $user = ""; //'root';
        /*  private $password = 'root';*/
        private $password = ""; // = '4c3r04dm1n';
        private $port = '';

        function __construct() {
            $this->host = isset($_SERVER["DEPLOYMENT"]) ? $_SERVER["DEPLOYMENT"] :"10.1.1.32";
            $this->db = isset($_SERVER["MYSQL_DB"]) ? $_SERVER["MYSQL_DB"] : "intranet";
            $this->user = isset($_SERVER["MYSQL_USER"]) ?$_SERVER["MYSQL_USER"] : "root";
            $this->password = isset($_SERVER["MYSQL_PW"]) ?$_SERVER["MYSQL_PW"] : "4c3r04dm1n";
            $this->port = isset($_SERVER["MYSQL_PORT"]) ?$_SERVER["MYSQL_PORT"] : "3306";
        }

        public function setDataConn($host, $db, $user, $password) {
            $this->host = $host;
            $this->db = $db;
            $this->user = $user;
            $this->password = $password;
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
