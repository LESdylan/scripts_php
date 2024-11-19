<?php
class Employee
{
    private array $listEmployees;
    private ?string $name;
    private ?int $age;
    private ?int $proStatus;
    private string $arrivalDate;
    private $holiCollec = [];

    public function __construct(array $listEmployees = [], ?string $name = null, ?int $age = null, ?int $proStatus = null, ?string $arrivalDate = null)
    {
        $this->listEmployees = $listEmployees;
        $this->name = $name;
        $this->age = $age;
        $this->proStatus = $proStatus;
        $this->arrivalDate = $arrivalDate ?? date("Y-m-d H:i:s");
    }

    public function addItems(array $new_array): void
    {
        foreach ($new_array as $employee) {
            if (is_array($employee) && isset($employee['name'], $employee['age'], $employee['proStatus'], $employee['arrivalDate'])) {
                if (!array_key_exists($employee['name'], $this->listEmployees)) {
                    $this->listEmployees[$employee['name']] = [
                        'age' => $employee['age'],
                        'proStatus' => $employee['proStatus'],
                        'arrivalDate' => $employee['arrivalDate']
                    ];
                } else {
                    echo "Employee {$employee['name']} already exists.\n";
                }
            }
        }
    }

    public function getList(): array
    {
        return $this->listEmployees;
    }

    public function remainTimeBefRetirement(string $name): string
    {
        $retirement_age = 64;

        if (!isset($this->listEmployees[$name])) {
            return "Employee $name does not exist.";
        }

        $age = $this->listEmployees[$name]['age'];
        $remainingYears = $retirement_age - $age;

        return match (true) {
            $age < 60 => "It remains $remainingYears years before $name can retire. Enjoy the journey!",
            $age >= 60 && $age < 64 => "It remains $remainingYears years for $name. Almost there!",
            $age == 64 => "Congratulations! $name will retire this year!",
            default => "$name is already retired."
        };
    }

    public function matchCandRequirements(array $new_candidate): bool
    {
        $requirements = [
            'experience' => 5,
            'degree' => 'Bachelor',
            'skills' => ['PHP', 'MySQL']
        ];

        if ($new_candidate['experience'] < $requirements['experience']) {
            echo "Missing experience.\n";
            return false;
        }

        if ($new_candidate['degree'] !== $requirements['degree']) {
            echo "Missing required degree.\n";
            return false;
        }

        $missingSkills = array_diff($requirements['skills'], $new_candidate['skills']);
        if (!empty($missingSkills)) {
            echo "Missing skills: " . implode(', ', $missingSkills) . "\n";
            return false;
        }

        return true;
    }

    public function evaluateCandidate(array $new_candidate): string
    {
        $requirements = [
            'experience' => 5,
            'degree' => 'Bachelor',
            'skills' => ['PHP', 'MySQL'],
            'age' => 30
        ];

        return match (true) {
            $new_candidate['experience'] < $requirements['experience'] =>
                "The candidate lacks experience. Required: {$requirements['experience']} years.",
            $new_candidate['degree'] !== $requirements['degree'] =>
                "The candidate's degree does not match. Required: {$requirements['degree']}.",
            array_diff($requirements['skills'], $new_candidate['skills']) =>
                "The candidate lacks required skills: " . implode(', ', array_diff($requirements['skills'], $new_candidate['skills'])) . ".",
            $new_candidate['age'] > $requirements['age'] =>
                "The candidate exceeds the age limit of {$requirements['age']}.",
            default =>
                "The candidate meets all criteria."
        };
    }

    public function evaluateExperience(int $experience): string
    {
        return match (true) {
            $experience < 1 => "The candidate is a beginner.",
            $experience >= 1 && $experience < 3 => "The candidate is junior.",
            $experience >= 3 && $experience < 5 => "The candidate is intermediate.",
            $experience >= 5 && $experience < 10 => "The candidate is senior.",
            default => "The candidate is an expert with vast experience."
        };
    }

    public function getStatusMessage(int $proStatus): string
    {
        return match ($proStatus) {
            1 => "The employee status is: Intern.",
            2 => "The employee status is: Employee.",
            3 => "The employee status is: Manager.",
            4 => "The employee status is: Director.",
            default => "Unknown status."
        };
    }
}

class ManagementHolidays
{
    private array $holidays = []; // Tableau de vacances
    private array $holiCollec = []; // Collection des vacances validées

    // Ajouter une période de vacances
    public function addHoliday(string $startDate, string $endDate): void
    {
        // Validation des dates
        if ($this->validateDate($startDate) && $this->validateDate($endDate)) {
            $this->holidays[] = ['start' => $startDate, 'end' => $endDate];
            echo "Holiday from $startDate to $endDate has been added.\n";
        } else {
            echo "Invalid dates provided.\n";
        }
    }

    // Valider le format de la date
    private function validateDate(string $date): bool
    {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    // Afficher toutes les périodes de vacances
    public function getHolidays(): array
    {
        return $this->holidays;
    }

    // Calculer le nombre total de jours de vacances
    public function getTotalHolidayDays(): int
    {
        $totalDays = 0;
        foreach ($this->holidays as $holiday) {
            $startDate = date_create($holiday['start']);
            $endDate = date_create($holiday['end']);
            $diff = date_diff($startDate, $endDate);
            $totalDays += $diff->days;
        }
        return $totalDays;
    }

    // Calculer le nombre de jours restants de vacances
    public function getRemainHoliday(): void
    {
        $start = date_create('2023-03-27');
        $end = date_create('2023-04-02');
        $nbdays = date_diff($start, $end);

        // Afficher le nombre de jours restants
        echo $nbdays->format("%d") . ' days remaining.' . PHP_EOL; 
    }

    // Valider et définir les vacances pour plusieurs employés
    public function setHolidays(array $pick): void
    {
        $validatedHolidays = [];

        foreach ($pick as $name => $value) {
            if (isset($value['start_date'], $value['end_date']) && !empty($name)) {
                $startDate = $value['start_date'];
                $endDate = $value['end_date'];

                if ($this->validateDate($startDate) && $this->validateDate($endDate)) {
                    if (strtotime($startDate) <= strtotime($endDate)) {
                        $validatedHolidays[$name] = [
                            'start_date' => $startDate,
                            'end_date' => $endDate
                        ];
                    } else {
                        echo "ERROR: Start date must be earlier than or equal to end date for $name." . PHP_EOL;
                    }
                } else {
                    echo "ERROR: Invalid date format for $name." . PHP_EOL;
                }
            } else {
                echo "ERROR: Missing 'start_date' or 'end_date' for holiday '$name'." . PHP_EOL;
            }
        }

        // Affecter les vacances validées à la collection
        $this->holiCollec = $validatedHolidays;
    }

    // Afficher les vacances validées
    public function holiCollection(): void
    {
        if (is_array($this->holiCollec) && !empty($this->holiCollec)) {
            foreach ($this->holiCollec as $name => $dates) {
                if (isset($dates['start_date'], $dates['end_date'])) {
                    echo "Holiday for $name: Start Date - {$dates['start_date']}, End Date - {$dates['end_date']}" . PHP_EOL;
                } else {
                    echo "Missing 'start_date' or 'end_date' for holiday '$name'." . PHP_EOL;
                }
            }
        } else {
            echo "No holidays are available or the collection is empty." . PHP_EOL;
        }
    }
}


// Existing employees
$list_employee = [
    'Dylan' => ['age' => 45, 'proStatus' => 1, 'arrivalDate' => '2003-01-10'],
    'Pablo' => ['age' => 62, 'proStatus' => 2, 'arrivalDate' => '1990-12-01']
];

// New employees
$new_employees = [
    ['name' => 'Anna', 'age' => 30, 'proStatus' => 3, 'arrivalDate' => '2015-03-15'],
    ['name' => 'Dylan', 'age' => 45, 'proStatus' => 1, 'arrivalDate' => '2003-01-10'], // Duplicate
    ['name' => 'Eve', 'age' => 70, 'proStatus' => 2, 'arrivalDate' => '1985-04-20']
];

//// Initialize the Employee class
//$instance = new Employee($list_employee);
//
//// Add new employees
//$instance->addItems($new_employees);
//
//// Print the updated list of employees
//print_r($instance->getList());
//
//// Check retirement status
//echo $instance->remainTimeBefRetirement('Dylan') . PHP_EOL;
//echo $instance->remainTimeBefRetirement('Pablo') . PHP_EOL;
//echo $instance->remainTimeBefRetirement('Anna') . PHP_EOL;
//echo $instance->remainTimeBefRetirement('Eve') . PHP_EOL;
//
//$candidate = [
//    'name' => 'John Doe',
//    'experience' => 6,
//    'degree' => 'Bachelor',
//    'skills' => ['PHP', 'MySQL', 'JavaScript']
//];
//
//if ($instance->matchCandRequirements($candidate)) {
//    echo "The candidate meets the requirements!\n";
//} else {
//    echo "The candidate does not meet the requirements.\n";
//}
//
//$candidate = [
//    'name' => 'Alice',
//    'experience' => 4,
//    'degree' => 'Master',
//    'skills' => ['PHP', 'HTML', 'CSS'],
//    'age' => 28
//];
//
//$instance = new Employee();
//$result = $instance->evaluateCandidate($candidate);
//echo $result;
//
//$instance = new Employee();
//echo $instance->evaluateExperience(6);
//
//$instance = new Employee();
//echo $instance->getStatusMessage(3);
//$holidays = [
//    'John Doe' => ['start_date' => '2024-01-01', 'end_date' => '2024-01-10'],
//    'Jane Smith' => ['start_date' => '2024-02-15', 'end_date' => '2024-02-20'],
//    'Invalid Person' => ['start_date' => '2024-03-01', 'end_date' => '2024-02-28'], // Date invalide
//    'Missing Dates' => ['start_date' => '', 'end_date' => ''], // Dates manquantes
//];
//
//$object = new Employee();
//$object->setHolidays($holidays);
//$object->holiCollection();
//$object->getremainholiday();

$holidayManager = new ManagementHolidays();

// Ajout de vacances
$holidayManager->addHoliday('2023-03-27', '2023-04-02');
$holidayManager->addHoliday('2023-06-01', '2023-06-10');

// Vérification des vacances enregistrées
$holidayManager->holiCollection();

// Calcul du nombre total de jours de vacances
echo "Total holiday days: " . $holidayManager->getTotalHolidayDays() . PHP_EOL;

// Calcul des jours restants de vacances
$holidayManager->getRemainHoliday();

// Définition de vacances pour plusieurs employés
$pick = [
    'John Doe' => ['start_date' => '2023-07-01', 'end_date' => '2023-07-10'],
    'Jane Doe' => ['start_date' => '2023-08-01', 'end_date' => '2023-08-05']
];
$holidayManager->setHolidays($pick);

// Affichage des vacances validées
$holidayManager->holiCollection();
