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






    // 8. Obtenir les cours en retard (deadline passée) avec le nombre d'exercices restants (finished = 0)
    public function getlateCoursesAndExercices(): array {
        // Requête effectuant le calcule de l'heure ainsi que l'affichage des cours avec un délai dépassé.
        $stmt = $this->db->query("SELECT c.id, c.name, c.deadline,  COUNT(CASE WHEN e.finished = 0 THEN 1 END) AS remainingExercises FROM course c LEFT JOIN exercise e ON c.id = e.courseId WHERE c.deadline IS NOT NULL AND c.deadline < NOW() GROUP BY c.id, c.name, c.deadline HAVING COUNT(CASE WHEN e.finished = 0 THEN 1 END) > 0;");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
       
        // Forcer le typage de l'agrégation en entier
        foreach ($results as &$row) {
            $row['remainingExercises'] = (int)$row['remainingExercises'];
        }
        return $results;
    }

}

?>
