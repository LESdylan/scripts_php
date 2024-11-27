<?php
declare(strict_types=1);

namespace App\Src;

function validityArray(array $abstracts, bool $verbose = true): int
{
    $validAbstracts = 0;
    $abstractCount = count($abstracts); // Total number of abstracts
    $validAbstractIds = [];

    foreach ($abstracts as $abstract) {
        // Ensure the abstract is an array with required keys
        if (!is_array($abstract) || !isset($abstract['id'], $abstract['name'], $abstract['url'])) {
            if ($verbose) {
                echo "Skipping an invalid abstract structure." . PHP_EOL;
            }
            continue;
        }

        // Validate the 'name' and 'url'
        if (!empty($abstract['name']) && filter_var($abstract['url'], FILTER_VALIDATE_URL)) {
            $validAbstracts++;
            $validAbstractIds[] = $abstract['id']; // Collect valid abstract IDs
        }
    }

    // Output the results if verbose is enabled
    if ($verbose) {
        echo "Total of valid abstracts: {$validAbstracts}/{$abstractCount}" . PHP_EOL;
        if (!empty($validAbstractIds)) {
            echo "The list of valid abstract IDs: " . implode(", ", $validAbstractIds) . PHP_EOL;
        } else {
            echo "No valid abstracts found." . PHP_EOL;
        }
    }

    return $validAbstracts;
}
