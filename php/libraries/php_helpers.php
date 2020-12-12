<?php

	function chained_interval() {
		global $time;

		$new_time = microtime($get_as_float=TRUE);
		$v = $new_time - $time;

		$time = $new_time;

		return $v;
	}

	function drop_column($arr,$col_names=null) {

		if (is_null($col_names)) {
			global $excluded_columns;
		} else {
			$excluded_columns = $col_names;
		}

		return array_map(function($x) use ($col_names,$excluded_columns) {
			return array_filter($x,function($z) use ($col_names,$excluded_columns) {
				return !(in_array($z, $excluded_columns));
			},ARRAY_FILTER_USE_KEY);
		}, $arr);
	}

	function convert_float_to_fraction($num) {
	    
	    if ($num == 0) return 0;

	    if ((mod_1($num) / $num < 0.02 || abs(mod_1($num) / $num - 1) < 0.02) && $num > 1) {
	        $num = round($num);
	    }
	    
	    $threshold = 0.008;
	    
	    // Catch whole number measures:
	    $errorPositive = mod_1($num);
	    $errorNegative = mod_1($num) - 1;
	    
	    if ($errorPositive < $threshold || abs($errorNegative) < $threshold) {
	        return convert_fraction_to_string(round($num),0,0);
	    }
	    
	    // Catch fractions with 1 as the numerator:
	    $decimal = round((mod_1($num)) * 100000) / 100000;
	    $remainder = round($num - $decimal);
	    
	    $reciprocal = 1 / $decimal;
	    $errorPositive = $reciprocal / round($reciprocal);
	    $errorNegative = $errorPositive - 1;
	    
	    if ($errorPositive < $threshold || abs($errorNegative) < $threshold) {
	        return convert_fraction_to_string($remainder,1,round($reciprocal));
	    }
	    
	    // Catch fractions with anything other than 1 as the numerator:
	    $counter = 2;
	    while ($counter <= 256) {
	            $reciprocal = (1 / $decimal) * $counter;
	            $errorPositive = mod_1($reciprocal);
	            $errorNegative = mod_1($reciprocal) - 1;
	            
	            if ($errorPositive < $threshold || abs($errorNegative) < $threshold) {
	                return convert_fraction_to_string($remainder,$counter,round($reciprocal));
	            }
	            
	            $counter += 1;
	    }
	    
	    return null;
	}

	function mod_1($n) {
		if ($n < 0) {
			return $n - ceil($n);
		} else {
			return $n - floor($n);
		}
	}

	function col_from_arr($col,$arr) {
		$total = array();
		if (count($arr) == 0) return [];

		foreach ($arr as $i) {
			array_push($total,$i[$col]);
		}
		return($total);
	}

	function array_unique_better($arr) {
		return array_values(array_unique($arr));
	}

	function convert_fraction_to_string($w,$n,$d,$c = null) {
	    if ($c == null) $c = true;
	    if ($c) {
	        $r = redenominate_fraction_string($w,$n,$d);
	        $w = $r[0]; $n = $r[1]; $d = $r[2];
	    }
	    return ($w == 0 ? "" : $w) . ($n == 0 ? "" : ($w == 0 ? "" : " ") . $n . "/" . $d);
	}

	function redenominate_fraction_string($w,$n,$d) {
	    
	    $usable_denominators = array(2,3,4,8);
	    if (!in_array($d,$usable_denominators) && $d != 0) {

	        $min_diff = 1;
	        $winner = [];
	        $actual_val = $n/$d;
	        $x;
	        for ($i=0; $i<count($usable_denominators); $i++) {
	            for ($j=1; $j<$usable_denominators[$i]; $j++) {
	                $x = abs($j/$usable_denominators[$i] - $actual_val);
	                if ($x < $min_diff) {
	                    $min_diff = $x;
	                    $winner = [$j,$usable_denominators[$i]];
	                }
	            }
	        }
	        return [$w,$winner[0],$winner[1]];
	    } else {
	        return [$w,$n,$d];
	    }
	}

	function arr_contains_el($arr,$el) {
		foreach ($arr as $i) {
			if ($i == $el) return TRUE;
		}
		return FALSE;
	}

	function clean_up_junk($s) {

        $s = preg_replace("/(?<![a-zA-Z])s(?![a-zA-Z])/","",$s);
            $s = trim($s);
            $s = preg_replace("/^,/","",$s);
            $s = trim($s);
            $s = preg_replace("/,$/","",$s);
            $s = trim($s);
            $s = preg_replace("/^s(?= )/","",$s);
            $s = trim($s);
        
        return $s;
	}

	function agrepl($p,$s,$m=0.1) {

		$s = explode(", ",$s)[0];
		$min_strlen = ceil(min(strlen($p),strlen($s)) - $m * strlen($p));

		for ($i=0; $i<strlen($s)-1; $i++) {
			for ($j=$i+1; $j<=strlen($s); $j++) {

				if ($j-$i < $min_strlen) continue;

				$str = substr($s,$i,$j);
				$lev = levenshtein($p,$str) / strlen($p);

				if ($lev <= $m) return [true,$str];
			}
		}

		return [false,""];
	}

	function a_contains_b($a,$b) {

		$a = array_values($a);
		if (count($a) == 0) return false;

		for ($i=0; $i<count($a); $i++) {
			if ($a[$i] == $b) return true;
		}

		return false;
	}

	function convert_string_to_float($s) {

        $s = explode(" ",$s);

        if (count($s) == 1) {
                if (preg_match("/\\//",$s[0])) {
                        $s = explode("/",$s[0]);
                        return(intval($s[0]) / intval($s[1]));
                } else {
                        return(intval($s[0]));
                }
        } else {
                $fractional_part = explode("/",$s[1]);
                return (intval($s[0]) + intval($fractional_part[0]) / intval($fractional_part[1]));
        }
    }
    
    function factorial($n) {
	    $total = 1;
	    for ($i=$n; $i>1; $i--) {
		    $total *= $i;
	    }
	    return $total;
    }
    
    
    

?>