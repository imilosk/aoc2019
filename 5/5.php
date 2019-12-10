<?php

$file = fopen("5.txt","r");
$line = fgets($file);
//$line = "1002,4,3,4,33";
$program = array_map('intval', explode(',', $line));
//$program[1] = 12;
//$program[2] = 2;

for ($i = 0; $i < count($program); $i++) {
    $instruction      = strrev((string)$program[$i]);
    $opcode           = (int) strrev( substr($instruction, 0, 2) );
    $modeOfParameter1 = (int) substr($instruction, 2, 1) ?: 0;
    $modeOfParameter2 = (int) substr($instruction, 3, 1) ?: 0;
    $modeOfParameter3 = (int) substr($instruction, 4, 1) ?: 0;

    if ($opcode === 1) {
        $parameter1 = $modeOfParameter1 === 1 ? $program[$i + 1] : $program[$program[$i + 1]];
        $parameter2 = $modeOfParameter2 === 1 ? $program[$i + 2] : $program[$program[$i + 2]];
        $parameter3 = $modeOfParameter3 === 1 ? $program[$program[$i + 3]] : $program[$i + 3];
        $program[$parameter3] = $parameter1 + $parameter2;
        $i += 3;

    } elseif ($opcode === 2) {
        $parameter1 = $modeOfParameter1 === 1 ? $program[$i + 1] : $program[$program[$i + 1]];
        $parameter2 = $modeOfParameter2 === 1 ? $program[$i + 2] : $program[$program[$i + 2]];
        $parameter3 = $modeOfParameter3 === 1 ? $program[$program[$i + 3]] : $program[$i + 3];
        $program[$parameter3] = $parameter1 * $parameter2;
        $i += 3;

    } elseif ($opcode === 3) {
        $handle = fopen ("php://stdin","r");
        echo 'Enter an integer: ';
        $input = (int) fgets($handle);
        $program[$program[$i + 1]] = $input;
        $i += 1;

    } elseif ($opcode === 4) {
        $parameter1 = $modeOfParameter1 === 1 ? $program[$i + 1] : $program[$program[$i + 1]] ;
        echo $parameter1 . PHP_EOL;
        $i += 1;

    } elseif ($opcode === 5) {
        $parameter1 = $modeOfParameter1 === 1 ? $program[$i + 1] : $program[$program[$i + 1]];
        $parameter2 = $modeOfParameter2 === 1 ? $program[$i + 2] : $program[$program[$i + 2]];
        if ($parameter1 !== 0)
            $i = $parameter2 - 1;
        else
            $i += 2;

    } elseif ($opcode === 6) {
        $parameter1 = $modeOfParameter1 === 1 ? $program[$i + 1] : $program[$program[$i + 1]];
        $parameter2 = $modeOfParameter2 === 1 ? $program[$i + 2] : $program[$program[$i + 2]];
        if ($parameter1 === 0)
            $i = $parameter2 - 1;
        else
            $i += 2;

    } elseif ($opcode === 7) {
        $parameter1 = $modeOfParameter1 === 1 ? $program[$i + 1] : $program[$program[$i + 1]];
        $parameter2 = $modeOfParameter2 === 1 ? $program[$i + 2] : $program[$program[$i + 2]];
        $parameter3 = $modeOfParameter3 === 1 ? $program[$program[$i + 3]] : $program[$i + 3];
        if ($parameter1 < $parameter2)
            $program[$parameter3] = 1;
        else
            $program[$parameter3] = 0;
        $i += 3;

    } elseif ($opcode === 8) {
        $parameter1 = $modeOfParameter1 === 1 ? $program[$i + 1] : $program[$program[$i + 1]];
        $parameter2 = $modeOfParameter2 === 1 ? $program[$i + 2] : $program[$program[$i + 2]];
        $parameter3 = $modeOfParameter3 === 1 ? $program[$program[$i + 3]] : $program[$i + 3];
        if ($parameter1 === $parameter2)
            $program[$parameter3] = 1;
        else
            $program[$parameter3] = 0;
        $i += 3;
        
    } elseif ($opcode === 99) {
        break;
    }
}

// var_dump($program);
// echo $program[0] . PHP_EOL;
fclose($file);
