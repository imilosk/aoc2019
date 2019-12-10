<?php

function intcodeComputer(&$program, &$queues, $id, $startFrom) {
    for ($i = $startFrom; $i < count($program); $i++) {
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
            if ($queues[$id]->count() === 0)
                return $i;
            
            $v = $queues[$id]->dequeue();
            $program[$program[$i + 1]] = intval($v);
            
            $i += 1;
            
        } elseif ($opcode === 4) {
            $parameter1 = $modeOfParameter1 === 1 ? $program[$i + 1] : $program[$program[$i + 1]];
            $queues[($id + 1) % 5]->enqueue($parameter1);
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
    return count($program);
}

function getAllCombos($arr) {
    $combinations = array();
    $words = sizeof($arr);
    $combos = 1;
    for($i = $words; $i > 0; $i--) {
        $combos *= $i;
    }
    while(sizeof($combinations) < $combos) {
        shuffle($arr);
        $combo = implode(" ", $arr);
        if(!in_array($arr, $combinations)) {
            $combinations[] = $arr;
        }
    }
    return $combinations;
}

$file = fopen("7.txt", "r");
$line = fgets($file);
// $line = "3,26,1001,26,-4,26,3,27,1002,27,2,27,1,27,26,27,4,27,1001,28,-1,28,1005,28,6,99,0,0,5";

$programs = [];
for ($i = 0; $i < 5; $i++) {
    $programs[$i] = array_map('intval', explode(',', $line));
}

$amplifiers = [0, 1, 2, 3, 4];
$possibleSettings = [5, 6, 7, 8, 9];
$settingCombinations = getAllCombos($possibleSettings);
$maxSignal = 0;

foreach ($settingCombinations as $settings) {
    $i = 0;
    $output = "";
    $signal = 0;
    $i = 0;

    $programs = [];
    for ($i = 0; $i < 5; $i++) {
        $programs[$i] = array_map('intval', explode(',', $line));
    }

    $length = count($amplifiers);
    $queues = [];
    for ($i = 0; $i < $length; $i++) {
        $queues[$i] = new SplQueue();
    }
    for ($i = 0; $i < $length; $i++) {
        $queues[$i]->enqueue($settings[$i]);
    }
    
    $queues[0]->enqueue(0);
    $startFrom = [0, 0, 0, 0, 0];

    while ($length > 0) {
        $code = intcodeComputer($programs[$i % 5], $queues, $i % 5, $startFrom[$i % 5]);
        if ($code == count($programs[$i % 5])) {
            $length--;
        } else {
            $startFrom[$i % 5] = $code;
        }
        $i++;
    }
    $maxSignal = max($maxSignal, intval($queues[0]->dequeue()));
}

echo $maxSignal . PHP_EOL;
fclose($file);