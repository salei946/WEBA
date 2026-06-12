<?php

include_once("Model/Model.php");
include_once("Views/Response.php");

class Controller {

    private $model;

    public function __construct() {
        $this->model = new Model(withErrors: true);
    }

    // TODO
}

?>