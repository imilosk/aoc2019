<?php

class IntcodeComputer {

    private int   $relative_base = 0;
    public array $program;
    public  bool  $halt = false;

    function __construct(array $program) {
        $this->program = $program;
    }

    public function resetProgram($program) {
        $this->program = $program;
    }

    public function execute() {
        $program = &$this->program;
        $c = count($program);
        for ($i = 0; $i < $c; $i++) {
            $orig_instruction = (string)$program[$i];
            $opcode           = (int) substr($orig_instruction, strlen($orig_instruction) - 2);
            $instruction      = strrev((string)$program[$i]);
            $modeOfParameter1 = (int) substr($instruction, 2, 1) ?: 0;
            $modeOfParameter2 = (int) substr($instruction, 3, 1) ?: 0;
            $modeOfParameter3 = (int) substr($instruction, 4, 1) ?: 0;
        
            if ($opcode === 99) {
                $this->halt = true;
                break;
            }
        
            switch($modeOfParameter1) {
                case 0:
                    if (!isset($program[$program[$i + 1]]))
                        $program[$program[$i + 1]] = 0;
                    $parameter1 = $program[$i + 1]; 
                    break;
                case 1:
                    if (!isset($program[$i + 1]))
                        $program[$i + 1] = 0;
                    $parameter1 = $i + 1;
                    break;
                case 2:
                    if (!isset($program[$program[$i + 1] + $this->relative_base]))
                        $program[$program[$i + 1] + $this->relative_base] = 0;
                    $parameter1 = $program[$i + 1] + $this->relative_base;
                    break;
            }
        
            switch($modeOfParameter2) {
                case 0:
                    if (!isset($program[$program[$i + 2]]))
                        $program[$program[$i + 2]] = 0;
                    $parameter2 = $program[$i + 2]; 
                    break;
                case 1:
                    if (!isset($program[$i + 2]))
                        $program[$i + 2] = 0;
                    $parameter2 = $i + 2;
                    break;
                case 2:
                    if (!isset($program[$program[$i + 2] + $this->relative_base]))
                        $program[$program[$i + 2] + $this->relative_base] = 0;
                    $parameter2 = $program[$i + 2] + $this->relative_base;
                    break;
            }
        
            switch($modeOfParameter3) {
                case 0:
                    if (!isset($program[$i + 3]))
                        $program[$i + 3] = 0;
                    $parameter3 = $program[$i + 3]; 
                    break;
                case 1:
                    if (!isset($program[$program[$i + 3] + $this->relative_base]))
                        $program[$program[$i + 3] + $this->relative_base] = 0;
                    $parameter3 = $program[$i + 3] + $this->relative_base;
                    break;
                case 2:
                    if (!isset($program[$program[$i + 3] + $this->relative_base]))
                        $program[$program[$i + 3] + $this->relative_base] = 0;
                    $parameter3 = $program[$i + 3] + $this->relative_base;
                    break;
            }
        
            if ($opcode === 1) {
                $program[$parameter3] = $program[$parameter1] + $program[$parameter2];
                $i += 3;
        
            } elseif ($opcode === 2) {
                $program[$parameter3] = $program[$parameter1] * $program[$parameter2];
                $i += 3;
        
            } elseif ($opcode === 3) {
                $input = yield 'Input';
                $program[$parameter1] = $input;
                $i += 1;
        
            } elseif ($opcode === 4) {
                yield $program[$parameter1];
                $i += 1;
        
            } elseif ($opcode === 5) {
                if ($program[$parameter1] !== 0)
                    $i = $program[$parameter2] - 1;
                else
                    $i += 2;
        
            } elseif ($opcode === 6) {
                if ($program[$parameter1] === 0)
                    $i = $program[$parameter2] - 1;
                else
                    $i += 2;
        
            } elseif ($opcode === 7) {
                if ($program[$parameter1] < $program[$parameter2])
                    $program[$parameter3] = 1;
                else
                    $program[$parameter3] = 0;
                $i += 3;
        
            } elseif ($opcode === 8) {
                if ($program[$parameter1] === $program[$parameter2])
                    $program[$parameter3] = 1;
                else
                    $program[$parameter3] = 0;
                $i += 3;
                
            } elseif ($opcode === 9) {
                $this->relative_base += $program[$parameter1];
                $i += 1;
        
            } 
        }

    }
}