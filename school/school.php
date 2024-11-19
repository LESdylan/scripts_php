<?php

class Student
{
    private array $listStudents = []; // Liste des étudiants initialisée à un tableau vide

    // Ajouter un ou plusieurs étudiants à la liste
    public function addStudent(array $new_students): void
    {
        foreach ($new_students as $student) {
            // Vérification si $student est un tableau avec les clés nécessaires
            if (is_array($student) && isset($student['name'], $student['age'], $student['class'], $student['notes'])) {
                array_push($this->listStudents, $student); // Ajoute l'étudiant au tableau
            } else {
                echo "Format incorrect pour l'étudiant : ";
                print_r($student);
            }
        }
    }

    // Calculer et afficher la moyenne des notes pour chaque étudiant
    public function averageNote(): void
    {
        foreach ($this->listStudents as $student) {
            $average = array_sum($student['notes']) / count($student['notes']);
            echo "L'étudiant {$student['name']} a une moyenne de " . round($average, 2) . " dans la classe {$student['class']}.\n";
        }
    }

    // Obtenir la liste des étudiants
    public function getStudents(): array
    {
        return $this->listStudents;
    }
}

// Liste initiale des étudiants
$initialStudents = [
    [
        'name' => 'susana',
        'age' => 11,
        'class' => "5th grade",
        'notes' => [0, 1, 2, 3, 5, 6, 6],
    ],
    [
        'name' => 'dylan',
        'age' => 13,
        'class' => "4th grade",
        'notes' => [0, 1, 2, 3, 5, 6, 6],
    ],
];

// Nouveaux étudiants à ajouter
$newStudents = [
    [
        'name' => 'benji',
        'age' => 14,
        'class' => "7th grade",
        'notes' => [0, 1, 2, 3, 5, 6, 6],
    ],
    [
        'name' => 'emma',
        'age' => 12,
        'class' => "6th grade",
        'notes' => [7, 8, 9, 10],
    ],
];

// Créer une instance de la classe Student
$studentManager = new Student();

// Ajouter les étudiants initiaux
$studentManager->addStudent($initialStudents);

// Ajouter de nouveaux étudiants
$studentManager->addStudent($newStudents);

// Afficher la moyenne des notes pour chaque étudiant
$studentManager->averageNote();
