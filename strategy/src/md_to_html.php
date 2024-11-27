<?php
namespace App;

function validityArray(array $abstracts): int
{
    $valid_abstract = 0;
    $abstract_number = count($abstracts);  // Count the total number of abstracts
    $abstract_id = [];

    foreach ($abstracts as $abstract) {
        // Check if both 'name' is non-empty and 'url' is a valid URL
        if (!empty($abstract['name']) && filter_var($abstract['url'], FILTER_VALIDATE_URL)) {
            $valid_abstract++;
            $abstract_id[] = $abstract['id'];  // Collect valid abstract IDs
        }
    }

    // Output the total count of valid abstracts and the number of total abstracts
    echo "Total of valid abstracts: {$valid_abstract}/{$abstract_number}" . PHP_EOL;
    
    // Output the list of valid abstract IDs (if any)
    if (count($abstract_id) > 0) {
        echo "The list of valid abstract IDs: " . implode(", ", $abstract_id) . PHP_EOL;
    } else {
        echo "No valid abstracts found." . PHP_EOL;
    }

    return $valid_abstract;
}