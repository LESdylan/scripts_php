<?php
declare(strict_types=1);

namespace App\Src;

function validityArray(array $abstracts, bool $verbose = true): int
{
    // Return early if the array is empty
    if (empty($abstracts)) {
        if ($verbose) {
            echo "No abstracts to process." . PHP_EOL;
        }
        return 0;
    }

    $validAbstracts = 0;
    $abstractCount = count($abstracts); // Total number of abstracts
    $validAbstractIds = [];

    foreach ($abstracts as $abstract) {
        // Ensure the abstract is an array with required keys and valid types
        if (!is_array($abstract) || !isset($abstract['id'], $abstract['name'], $abstract['url']) ||
            (!is_int($abstract['id']) && !is_string($abstract['id'])) || !is_string($abstract['name']) || !is_string($abstract['url'])) {
            if ($verbose) {
                echo "Skipping an invalid abstract structure (missing or incorrect types)." . PHP_EOL;
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
