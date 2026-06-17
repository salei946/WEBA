<?php

class Model {

    private $db;

    public function __construct(bool $withErrors = false) {
        $this->db = new PDO("mysql:host=localhost;dbname=weba-te03-2026", "root", "");

        if ($withErrors) {
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }

    // 2. fonction qui récupère tout les cours.
    public function getCourses(): array{
        $statement = $this->db->prepare(" SELECT id, name, deadline FROM course ORDER BY id ");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

    // 3. fonction qui récupère tout les cours avec leurs exercices liés.
    public function getCoursesWithExercises(): array{
        $statement = $this->db->prepare("SELECT c.id AS course_id,c.name AS course_name,c.deadline,e.id AS exercise_id,e.description AS exercise_description FROM course c LEFT JOIN exercise e ON c.id = e.courseId ORDER BY c.id, e.id;");
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    // 4. Fonction qui récupère les détails d'un cours selon son id et donne le % des exercices terminés
    public function getCourseById(int $id): ?array {
        $statement = $this->db->prepare("SELECT c.id AS course_id, c.name AS course_name, c.deadline, e.id AS exercise_id, e.description AS exercise_description, e.finished FROM course c LEFT JOIN exercise e ON c.id = e.courseId WHERE c.id = :id");
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        $courseData = $statement->fetchAll(PDO::FETCH_ASSOC);

        if (empty($courseData)) {
            return null;
        }

        // Calcul du pourcentage d'exercices terminés
        $totalExercises = 0;
        $finishedExercises = 0;

        //Pour chaque cours, on compte le nombre total d'exercices et le nombre d'exercices terminés (finished = 1)
        foreach ($courseData as $row) {
            if ($row['exercise_id'] !== null) {
                $totalExercises++;
                if ($row['finished'] == 1) {
                    $finishedExercises++;
                }
            }
        }
        //
        $completionPercentage = ($totalExercises > 0) ? ($finishedExercises / $totalExercises) * 100 : 0;

        // Ajout du pourcentage de complétion à la réponse
        return [
            'course_id' => $courseData[0]['course_id'],
            'course_name' => $courseData[0]['course_name'],
            'deadline' => $courseData[0]['deadline'],
            'exercises' => array_map(function($row) {
                return [
                    'exercise_id' => $row['exercise_id'],
                    'exercise_description' => $row['exercise_description'],
                    'finished' => $row['finished']
                ];
            }, $courseData),
            'completion_percentage' => round($completionPercentage, 2)
        ];
    }

    //5. Créer un nouveau cours à partir des informations passées dans le corps de la requête (nom et deadline)
    public function addCourse(string $name, ?string $deadline): int {
        $statement = $this->db->prepare("INSERT INTO course (name, deadline) VALUES (:name, :deadline)");
        $statement->execute([
            "name" => $name,
            "deadline" => $deadline
        ]);
        return (int)$this->db->lastInsertId();
    }

    //6. Supprimer un cours selon son id passé dans l'URL
    public function deleteCourse(int $id): bool {

        $statement = $this->db->prepare("DELETE FROM course WHERE id = :id");
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        return $statement->execute();
    } 





    //8. Supprimer un exercice selon son id passé dans l'URL
    // fonction qui récupère le cours avec son id
    public function getExerciseByID(int $id): bool{
        // Requête qui récupère l'exercice 1 pour voir s'il existe.
        $stmt = $this->db->prepare("SELECT * FROM exercise WHERE id = :id");
        // execute la commande
        return $stmt->execute(['id' => $id]);
    }

    public function deleteExercices(int $id): bool {
        // Requête effectuant la suppression du cours numéro 1.
        $stmt = $this->db->prepare("DELETE FROM exercise WHERE id = :id");
        // execute la commande

        return  $stmt->execute(['id' => $id]);
    }




    // 9. Obtenir les cours en retard (deadline passée) avec le nombre d'exercices restants (finished = 0)
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
