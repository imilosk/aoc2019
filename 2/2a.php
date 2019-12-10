<?php

$file = fopen("2.txt","r");
$line = fgets($file);
//$line = "1,9,10,3,2,3,11,0,99,30,40,50";
$program = array_map('intval', explode(',', $line));
$program[1] = 12;
$program[2] = 2;

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

// var_dump($program);
echo $program[0] . PHP_EOL;
fclose($file);
