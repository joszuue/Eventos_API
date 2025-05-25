<?php
    class Model{
        public $con;
        function __construct(){
            $this->con= new Conexion();
        }
    }
?>