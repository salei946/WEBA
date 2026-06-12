<?php
require_once("Views/Response.php");
require_once("Controllers/Controller.php");

class Router {

    public static function route(string $method, ?string $action): Response|null {

        if ($action == null) {
            return new Response(httpCode: 400);
        }

        $controller = new Controller();

        switch ($action)
        {
            case "courses":
                if ($method === "GET" && !isset($_GET["id"]) && !isset($_GET["withExercises"])) {
                    return $controller->getCourses();
                }
                elseif ($method === "GET" && !isset($_GET["id"]) && isset($_GET["withExercises"]) && $_GET["withExercises"] === "true") {
                    return $controller->getCoursesWithExercises();
                }   
                break;


        }

        return null;
    }
}
?>