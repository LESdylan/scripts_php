<?php
    declare(strict_types=1);

    function getAllColors(): array
    {
        $list=[
            'black' => 0,
            'brown' => 1,
            'red' => 2,
            'orange' => 3,
            'yellow' => 4,
            'green' => 5,
            'blue' => 6,
            'violet' => 7,
            'grey' => 8,
            'purple' => 9
        ];
        return $list;
    }

    function colorCode(string $color): int
    {
        return getallColors()[$color];
    }


    print_r(getAllColors());
    echo colorCode('orange');