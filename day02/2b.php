<?php

include '../Intcode/IntcodeComputer.php';

$file = fopen("2.txt","r");
$line = fgets($file);

for ($noun = 0; $noun < 100; $noun++){
    for ($verb = 0; $verb < 100; $verb++) {
        $program = array_map('intval', explode(',', $line));
        $program[1] = $noun;
        $program[2] = $verb;

        $computer = new IntcodeComputer($program);
        $computer->execute(
            null, 
            function ($output) {
                echo $output;
            }
        );

        if ($computer->program[0] === 19690720)
            break;
    }
    if ($computer->program[0] === 19690720)
        break;
}

echo (100 * $noun + $verb) . PHP_EOL;
fclose($file);
