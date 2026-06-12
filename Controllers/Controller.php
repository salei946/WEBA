<?php

include_once("Model/Model.php");
include_once("Views/Response.php");

class Controller {

    private $model;

    public function __construct() {
        $this->model = new Model(withErrors: true);
    }

    //Générer une réponse HTTP 200 avec la liste de tous les cours
    public function getCourses(): Response{
        $courses = $this->model->getCourses();

        return new Response(
            httpCode: 200,
            responseString: json_encode($courses)
        );
    }


}

?>