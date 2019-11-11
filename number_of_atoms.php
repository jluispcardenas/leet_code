class Solution {

    /**
     * @param String $formula
     * @return String
     */
    function countOfAtoms($formula) {
        $tokens = $this->tokenizer($formula);
    
		$parsed = $this->parse($tokens);
		
        $text = $this->text($parsed);
        
		return $text;
	}


function text($parsed) {	
	ksort($parsed);
	
	$text = "";
	foreach ($parsed as $k => $v) {
		if ($v < 2) $v = "";
		$text .= $k . $v;	
	}
	
	return $text;
}

function tokenizer($formula) {
	$tokens = array();
	
	$length = strlen($formula);
	$buff = '';
	for ($i = 0; $i < $length; $i++) {
		$char = $formula[$i];
		
		if ($char == '(' || $char == ')') {
		    $tokens[] = $char;	
		}  else {
			$next = '';
			if ($i+1 < $length && (ctype_lower($formula[$i+1]) || is_numeric($formula[$i]) && is_numeric($formula[$i+1]))) {
				$next = $formula[++$i];
			}
			
			$tokens[] = $char . $next;	
		}
	}
	echo "<pre>";
	var_dump($tokens);
	
	return $tokens;
}

function parse(&$tokens) {
	$values = array();
	while (($token = array_shift($tokens)) !== NULL) {
		if ($token == '(') {
			$new_vals = $this->parse($tokens);

			$operand = array_shift($tokens);
			if (!is_numeric($operand)) {
				throw new Exception("Operador no es numerico: {$operand}");
			}
			foreach ($new_vals as $k => $v) {
				$new_vals[$k] = $new_vals[$k] * intval($operand);
				$values[$k] = (isset($values[$k]) ? $values[$k] : 0) + $new_vals[$k];
			}
		} else if ($token == ')') {
			return $values;
		} else {
			// atomo encontrado
			$value = 1;
			$next = NULL;
			if (is_numeric($next = array_shift($tokens))) {
				$value = $next;
			} else {
				array_unshift($tokens, $next);	
			}
			
			if (!isset($values[$token])) 
				$values[$token] = $value;
			else 
				$values[$token] = ($values[$token]+$value);
		}
	}
	
	return $values;	
}
}
