<?php

$file = fopen("2.txt","r");
$line = fgets($file);

for ($noun = 0; $noun < 100; $noun++){
    for ($verb = 0; $verb < 100; $verb++) {
        $program = array_map('intval', explode(',', $line));
        $program[1] = $noun;
        $program[2] = $verb;

        for ($i = 0; $i < count($program); $i++) {
            $opcode = $program[$i];
            // echo $opcode . PHP_EOL;
            if ($opcode == 1) {
                $program[$program[$i + 3]] = $program[$program[$i + 1]] + $program[$program[$i + 2]];
                // echo $program[$i + 3] . PHP_EOL;
                $i += 3;
            } elseif ($opcode == 2) {
                $program[$program[$i + 3]] = $program[$program[$i + 1]] * $program[$program[$i + 2]];
                $i += 3;
            } elseif ($opcode == 99) {
                break;
            }
        }

        if ($program[0] == 19690720)
            break;
    }
    if ($program[0] == 19690720)
        break;
}

echo (100 * $noun + $verb) . PHP_EOL;
fclose($file);
