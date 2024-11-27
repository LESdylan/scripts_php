<?php
declare(strict_types=1);
function distance(string $strandA, string $strandB): int
{
    if(strlen($strandA) !== strlen($strandB)) {
        throw new InvalidArgumentException('strands must be of equal length.');
    }
    $diff = array_diff_assoc(str_split($strandA), str_split($strandB));
    return count($diff);
}
