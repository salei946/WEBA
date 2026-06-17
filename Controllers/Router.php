<?php
require_once("Views/Response.php");
require_once("Controllers/Controller.php");

class Router {

    public static function route(string $method, ?string $action): Response|null {

        if ($action == null) {
            return new Response(httpCode: 400);
        }

        $controller = new Controller();

        switch ($action){
            case "courses":
                switch ($method) {
                    case "GET":
                        if (!isset($_GET["id"]) && !isset($_GET["withExercises"])) {
                        return $controller->getCourses();
                        }   
                        elseif (!isset($_GET["id"]) && isset($_GET["withExercises"]) && $_GET["withExercises"] === "true") {
                            return $controller->getCoursesWithExercises();
                        }
                        elseif (isset($_GET["id"])) {
                            $id = (int)$_GET["id"];
                            return $controller->getCourseById((int)$id);
                        }
                        break;

                    case "POST":
                        return $controller->addCourse($_POST["name"], $_POST["deadline"] ?? null);
                        break;

                    case "DELETE":
                        if (isset($_GET["id"])) {
                            $id = (int)$_GET["id"];
                            return $controller->deleteCourse($id);
                        }
                        break;
                }
                break;

            // appelle de la fonction des cours en retard dans le controller
            case "latecourses":
                switch ($method) {
                    case "GET":
                        if($action === 'latecourses') {
                            return $controller->getLateCourses();
                        }
                        break;
                }
                break;

            case "exercises":
                switch ($method) {
                    case "GET":
                        break;
                        //action d'ajout
                    case "POST":
                        break;
                        //action de suppression
                    case "DELETE":
                        if (isset($_GET['id']) && !empty($_GET['id'])) {
                            return $controller->deleteExercices((int)$_GET["id"]);
                        }
                        break;
                }
                break;
        }
        return null;
    }
}
?>