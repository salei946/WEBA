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

    public function getCoursesWithExercises(): Response {
        $courses = $this->model->getCoursesWithExercises();

        return new Response(
            httpCode: 200,
            responseString: json_encode($courses)
        );
    }

    public function getCourseById(int $id): Response {
        $course = $this->model->getCourseById($id);

        if ($course) {
            return new Response(
                httpCode: 200,
                responseString: json_encode($course)
            );
        } else {
            return new Response(httpCode: 404);
        }
    }

    //Créer un cours à partir de son nom et sa date d'échéance (deadline) passés dans le corps de la requête (optionnel)
    public function addCourse(string $name, ?string $deadline): Response {
        
        if (!isset($_POST["name"]) || empty($_POST["name"])) {
            return new Response(400);
        }

        $name = $_POST["name"];
        $deadline = $_POST["deadline"] ?? null;

        $id = $this->model->addCourse($name,$deadline);

        return new Response(201,json_encode(["id" => $id]));
    }

    //Supprimer un cours selon son id passé dans l'URL
    public function deleteCourse(int $id): Response {
        $deleted = $this->model->deleteCourse($id);
        if ($deleted) {
            return new Response(204);
        } else {
            return new Response(404);
        }
    }

        








    // Génére une réponse HTTP 200 avec la liste des cours en retard.
    public function getLateCourses(): Response {
        $lateCourses = $this->model->getlateCoursesAndExercices();
        return new Response(200, json_encode($lateCourses));
    }

}    

?>