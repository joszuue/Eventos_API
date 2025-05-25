<?php
    header("Content-Type: application/json");
    class ErrorController extends Controller{
        function __construct(){
            parent::__construct();
            print_r("404");
        }
    }
?>