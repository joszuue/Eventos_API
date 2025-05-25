<?php
    class View{
        public $listaOcupaciones;
        public $persona;
        public $listaOcupaciones2;
        public $Valimensaje;
        public $mensaje;
        public $listaPersonas;

        function __construct(){

        }
        
        function renderView($vista){
            require 'views/' . $vista; 
        }
    }
?>