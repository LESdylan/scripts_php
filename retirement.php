<?php
class Employee
{
    private array $listEmployees;
    private ?string $name;
    private ?int $age;
    private ?int $proStatus;
    private string $arrivalDate;

    public function __construct(array $listEmployees = [], ?string $name = null, ?int $age = null, ?int $proStatus = null, ?string $arrivalDate = null)
    {
        $this->listEmployees = $listEmployees; // Initialize with existing employees
        $this->name = $name;
        $this->age = $age;
        $this->proStatus = $proStatus;
        $this->arrivalDate = $arrivalDate ?? date("Y-m-d H:i:s"); // Default to current datetime
    }

    // Add new employees to the list
    public function addItems(array $new_array): void
    {
        foreach ($new_array as $employee) {
            // Ensure the employee is an array with expected keys
            if (is_array($employee) && isset($employee['name'], $employee['age'], $employee['proStatus'], $employee['arrivalDate'])) {
                // Check for duplicates by name
                if (!array_key_exists($employee['name'], $this->listEmployees)) {
                    $this->listEmployees[$employee['name']] = [
                        'age' => $employee['age'],
                        'proStatus' => $employee['proStatus'],
                        'arrivalDate' => $employee['arrivalDate']
                    ];
                }
            }
        }
    }

    // Get the list of employees
    public function getList(): array
    {
        return $this->listEmployees;
    }

    // Calculate remaining time before retirement
    public function remainTimeBefRetirement(string $name): void
    {
        $retirement_age = 64;

        // Check if the employee exists
        if (!isset($this->listEmployees[$name])) {
            echo "Employee $name does not exist.";
        }

        $age = $this->listEmployees[$name]['age'];
        $remainingYears = $retirement_age - $age;
        
        switch (true) { // each becomes a conditional check because switch evaludate the true literal.
            case ($age == 64):
                echo "Congrats, you'll get retired this year.";
                break;
            case ($age < 60):
                echo "You still have quite some time before retiring.";
                break;
            case ($age > 60 && $age < 64):
                echo "It shouldn't take long by now.";
                break;
            default:
                echo "You're already retired.";
        }

        //if ($age < 60) {
        //    return "It remains $remainingYears years before $name can retire. Enjoy the journey!";
        //} elseif ($age >= 60 && $age < 64) {
        //    return "It remains $remainingYears years for $name. Almost there!";
        //} elseif ($age == 64) {
        //    return "Congratulations! $name will retire this year!";
        //} else {
        //    return "$name is already retired.";
        //}
    }

    public function matchCandRequirements(array $new_candidate): bool
{
    // Define the requirements
    $requirements = [
        'experience' => 5, // Minimum 5 years of experience
        'degree' => 'Bachelor', // Must have at least a Bachelor's degree
        'skills' => ['PHP', 'MySQL'], // Must possess these skills
    ];

    // Check if the candidate meets the experience requirement
    if ($new_candidate['experience'] < $requirements['experience']) {
        return false; // Doesn't meet the experience requirement
    }

    // Check if the candidate meets the degree requirement
    if ($new_candidate['degree'] !== $requirements['degree']) {
        return false; // Doesn't meet the degree requirement
    }

    // Check if the candidate has the required skills
    $missingSkills = array_diff($requirements['skills'], $new_candidate['skills']);
    if (!empty($missingSkills)) {
        return false; // Doesn't have all the required skills
    }

    // If all checks pass, return true
    return true;
}
public function evaluateCandidate(array $new_candidate): string
{
    // Définir les critères de sélection
    $requirements = [
        'experience' => 5, // Minimum 5 ans d'expérience
        'degree' => 'Bachelor', // Diplôme requis
        'skills' => ['PHP', 'MySQL'], // Compétences obligatoires
        'age' => 30 // Âge maximum
    ];

    // Vérifier chaque critère avec une structure switch et des cas spécifiques
    switch (true) {
        case ($new_candidate['experience'] < $requirements['experience']):
            return "Le candidat n'a pas assez d'expérience. Expérience requise: {$requirements['experience']} ans.";

        case ($new_candidate['degree'] !== $requirements['degree']):
            return "Le diplôme du candidat ne correspond pas. Diplôme requis: {$requirements['degree']}.";

        case (array_diff($requirements['skills'], $new_candidate['skills'])):
            $missingSkills = implode(', ', array_diff($requirements['skills'], $new_candidate['skills']));
            return "Le candidat n'a pas les compétences requises: {$missingSkills}.";

        case ($new_candidate['age'] > $requirements['age']):
            return "Le candidat dépasse l'âge requis. Âge maximum: {$requirements['age']}.";

        default:
            return "Le candidat remplit tous les critères.";
    }
}
public function evaluateExperience(int $experience): string
{
    return match (true) {
        $experience < 1 => "Le candidat est débutant.\n",
        $experience >= 1 && $experience < 3 => "Le candidat est junior.\n",
        $experience >= 3 && $experience < 5 => "Le candidat est intermédiaire.\n",
        $experience >= 5 && $experience < 10 => "Le candidat est senior.\n",
        default => "Le candidat est expert avec une vaste expérience.\n",
    };
}

public function getStatusMessage(int $proStatus): string
{
    return match ($proStatus) {
        1 => "Le statut de l'employé est : Stagiaire.\n",
        2 => "Le statut de l'employé est : Employé.\n",
        3 => "Le statut de l'employé est : Manager.\n",
        4 => "Le statut de l'employé est : Directeur.\n",
        default => "Statut inconnu.",
    };
}

}
// Example usage

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

// Initialize the Employee class
$instance = new Employee($list_employee);

// Add new employees
$instance->addItems($new_employees);

// Print the updated list of employees
print_r($instance->getList());

// Check retirement status
echo $instance->remainTimeBefRetirement('Dylan') . PHP_EOL;
echo $instance->remainTimeBefRetirement('Pablo') . PHP_EOL;
echo $instance->remainTimeBefRetirement('Anna') . PHP_EOL;
echo $instance->remainTimeBefRetirement('Eve') . PHP_EOL;

$candidate = [
    'name' => 'John Doe',
    'experience' => 6,
    'degree' => 'Bachelor',
    'skills' => ['PHP', 'MySQL', 'JavaScript']
];

if ($instance->matchCandRequirements($candidate)) {
    echo "The candidate meets the requirements!\n";
} else {
    echo "The candidate does not meet the requirements.\n";
}

$candidate = [
    'name' => 'Alice',
    'experience' => 4,
    'degree' => 'Master',
    'skills' => ['PHP', 'HTML', 'CSS'],
    'age' => 28
];

$instance = new Employee();
$result = $instance->evaluateCandidate($candidate);
echo $result;

$instance = new Employee();
echo $instance->evaluateExperience(6);

$instance = new Employee();
echo $instance->getStatusMessage(3);
