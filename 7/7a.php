<?php

function intcodeComputer($program, $inputSetting = null, $inputSignal) {
    $firstArgument = true;
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
            if ($firstArgument && $inputSetting !== null) {
                $program[$program[$i + 1]] = $inputSetting;
                $firstArgument = false;
            }else {
                $program[$program[$i + 1]] = $inputSignal;
            }
            $i += 1;
            
        } elseif ($opcode === 4) {
            $parameter1 = $modeOfParameter1 === 1 ? $program[$i + 1] : $program[$program[$i + 1]];
            return $parameter1;

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
    return 'halt';
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
$program = array_map('intval', explode(',', $line));

$possibleSettings = [0, 1, 2, 3, 4];
$maxSignal = 0;
$settingCombinations = getAllCombos($possibleSettings);

foreach ($settingCombinations as $settings) {
    $signal = 0;
    foreach ($settings as $setting) {
        $signal = intcodeComputer($program, $setting, $signal);
    }
    $maxSignal = max($signal, $maxSignal);
}

echo $maxSignal . PHP_EOL;

fclose($file);