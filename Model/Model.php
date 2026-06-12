<?php

class Model {

    private $db;

    public function __construct(bool $withErrors = false) {
        $this->db = new PDO("mysql:host=localhost;dbname=weba-te03-2026", "root", "");

        if ($withErrors) {
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }

    // fonction qui récupère tout les cours.
    public function getCourses(): array{
        $statement = $this->db->prepare(" SELECT id, name, deadline FROM course ORDER BY id ");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

    //fonction qui récupère tout les cours avec leurs exercices liés.
    public function getCoursesWithExercises(): array{
            $statement = $this->db->prepare("SELECT c.id AS course_id,c.name AS course_name,c.deadline,e.id AS exercise_id,e.description AS exercise_description FROM course c LEFT JOIN exercise e ON c.id = e.courseId ORDER BY c.id, e.id;");
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

}

?>
