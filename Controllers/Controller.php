<?php

include_once("Model/Model.php");
include_once("Views/Response.php");

class Controller {

    private $model;

    public function __construct() {
        $this->model = new Model(withErrors: true);
    }

    // 2. Générer une réponse HTTP 200 avec la liste de tous les cours
    public function getCourses(): Response{
        $courses = $this->model->getCourses();
        if(empty($courses)) {
            return new Response(httpCode: 404);
        }else{
            return new Response(
            httpCode: 200,
            responseString: json_encode($courses)
            );
        }
    }
    
    // 3. Générer une réponse HTTP 200 avec les cours et leurs exercices liés.
    public function getCoursesWithExercises(): Response {
        $courses = $this->model->getCoursesWithExercises();

        return new Response(
            httpCode: 200,
            responseString: json_encode($courses)
        );
    }
    // 4. Générer une réponse HTTP 200 avec les détails d'un cours selon son id et donne le % des exercices terminés
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

    // 5. Générer une réponse HTTP 201 avec l'id du cours ajouté
    public function addCourse(string $name, ?string $deadline): Response {
        
        if (!isset($_POST["name"]) || empty($_POST["name"])) {
            return new Response(400);
        }

        $name = $_POST["name"];
        $deadline = $_POST["deadline"] ?? null;

        $id = $this->model->addCourse($name,$deadline);

        return new Response(201,json_encode(["id" => $id]));
    }

    // 6. Générer une réponse HTTP 204 après suppression
    public function deleteCourse(int $id): Response {
        $course = $this->model->getCourseById($id);
        if (!$course) {
            return new Response(404);
        }else {
            $this->model->deleteCourse($id);
            return new Response(204);
        }
    }

    // 7. Générer une réponse HTTP 201 avec l'id de l'exercise ajouté
    public function addExercise(int $id, string $name, ?string $description): Response {
        
        if (!isset($name) || empty($name)) {
            return new Response(400);
        }
        $id = $this->model->addExercise($id, $name,$description);
        return new Response(201,json_encode(["id" => $id]));
    }

    // 8. Générer une réponse HTTP 204 après suppression
    public function deleteExercices(int $id): Response {
        // va récupérer des informations si l'exercise existe bien
        $exercise = $this->model->getExerciseByID($id);
        // contrôle si $exercise existe bel et bien.
        if(!$exercise) {
            return new Response(404);
        } else {
            $this->model->deleteExercices($id);
            return new Response(204);
        }
    }

    // 9. Génére une réponse HTTP 200 avec la liste des cours en retard.
    public function getLateCourses(): Response {
        $lateCourses = $this->model->getlateCoursesAndExercices();
        return new Response(200, json_encode($lateCourses));
    }

}    

?>