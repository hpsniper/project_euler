<?php

require_once 'eulerConstants.php';

// Test the correctness of a given function and time the execution.
function testCorrectness($function, $expected, $args = NULL) {
    echo "\n##### BEGIN TEST $function: expected=$expected args='$args' ####";

    $start_time = microtime(true);
    $actual = $function($args);
    $end_time = microtime(true);
    $time = round( ($end_time - $start_time), 4);

    $correct = $actual === $expected;

    if(!$correct) {
        echo "\n $function ERROR:\nexpected";
        var_dump($expected);
        echo "\n actual";
        var_dump($actual);
    }

    if($time >= 60) {
        echo "\n\t~~~~ FAILURE: ~~~~ TIME EXCEEDED 1 MINUTE: ($time)\n";
    }

    echo "\n#### END TEST $function: total_time=$time seconds (".round( ($time / 60), 3).") minutes ####\n";
}

/*
 * Possible Universal Functions
 */

// Returns whether or not a given number is prime
function isPrimeP($n) {
    $sqrtOfN = sqrt($n);
    for($i=2;$i<=$sqrtOfN;$i++) {
        if(! ($n % $i) ) {
            return false;
        }
    }

    return true;
}

// Returns the largest prime factor of a given number OR STRING FALSE
function largestPrimeFactor($n) {
    $nDividedBy2 = (int)floor($n / 2);
    for($i=2;$i<$nDividedBy2;$i++) {
        $check = $n / $i;
        if( (!($n % $i)) && isPrimeP($check)) {
            return $check;
        }
    }

    return 'FALSE';
}

// Returns whether or not a given string is a palindrome
function isPalindrome($s) {
    return $s == strrev($s);
}

// Returns all factors of a number as a factor array
// Store factors we find in a table so we don't compute them again
// If we have the factors already computed, return them
// getFactors(50) => array(50=>50, 2=>2, 1=>1, 25=>25, 5=>5);
function getFactors($num, $rainbowtable = array()) {
    if($rainbowtable[$num]) {
        return $rainbowtable[$num];
    }
    if($num == 1) {
        $rainbowtable[1] = array(1=>1);
        return $rainbowtable[1];
    }
    if($num == 2) {
        $rainbowtable[2] = array(2=>2, 1=>1);
        return $rainbowtable[2];
    }

    $limit = $num / 2;
    $foundFactor = false;
    for($i=2;$i<=$limit;$i++) {
        if(! ($num % $i)) {
            $foundFactor = true;
            $j = $num / $i;
            $arrayJ = getFactors($j, $rainbowtable);
            $rainbowtable[$j] = $arrayJ;

            $arrayI = getFactors($i, $rainbowtable);
            $rainbowtable[$i] = $arrayI;

            $rainbowtable[$num] = array($num=>$num) + $arrayJ + $arrayI;
        }
    }

    return $foundFactor ? $rainbowtable[$num] : array($num=>$num, 1=>1);
}

/*
 * Problem Specific Functions
 */

function sumOfMultiples3And5BelowN($n) {
    $accumulator = 0;
    $n--;
    while($n > 0) {
        if( !($n % 3) || !($n % 5) ) {
            $accumulator += $n;
        }
        $n--;
    }
    return $accumulator;
}

function sumOfMultiples3And5Below1000() {
    return sumOfMultiples3And5BelowN(1000);
}

function evenFibonacciBelow4Million() {
    $accumulator = 0;
    $first = 1;
    $second = 1;
    while($second < 4000000) {
        $new = $first + $second;
        if(! ($new % 2) ) {
            $accumulator += $new;
        }

        $first = $second;
        $second = $new;
    }

    return $accumulator;
}

function largestPrimeFactorOfHugeNumber() {
    return largestPrimeFactor(600851475143);
}

function largestThreeDigitPalindromeProduct() {
    $largest = 0;
    for($i=999;$i>99;$i--) {
        for($j=999;$j>99;$j--) {
            $num = $i * $j;
            if($num < $largest) {
                break;
            }
            if(isPalindrome((string) $num)) {
                $largest = $num;
            }
        }
    }

    return $largest;
}

function smallestMultipleEvenlyDivisible() {
    $success = true;
    for($i=20;$i>0;$i=$i+20) {
        for($j=20;$j>0;$j--) {
            if($i % $j) {
                $success = false;
                break;
            }
        }

        if($success) {
            return $i;
        } else {
            $success = true;
        }
    }

    return 'ERROR';
}

function squareOfTheSumsMinusSumOfTheSquares($n) {
    $sumOfSquares = 0;
    $sumToBeSquared = 0;
    for($n;$n>0;$n--) {
        $sumToBeSquared += $n;
        $sumOfSquares += ($n * $n);
    }

    return ($sumToBeSquared * $sumToBeSquared) - $sumOfSquares;
}

function squareOfTheSumsMinusSumOfTheSquaresFor100() {
    return squareOfTheSumsMinusSumOfTheSquares(100);
}

function nthPrimeNumber($n) {
    $counter = 0;
    for($i=2;$counter<$n;$i++) {
        if(isPrimeP($i)) {
            $counter++;
            if($counter == $n) {
                return $i;
            }
        }
    }
}

function TenThousandFirstPrimeNumber() {
    return nthPrimeNumber(10001);
}

function largestProductOf5numbersInN($n) {
    $start = 0;
    $end = 4;
    $array = str_split((string)$n);
    if(count($array) < 5) {
        return 'FALSE';
    }
    $largest = 0;
    $first = true;
    while($end <= count($array) - 1) {
        $largest = max($largest, array_product(array_slice($array, $start, 5)));

        $start++;
        $end++;
    }

    return $largest;
}

function pythagoreanTriplet() {
    for($b = 2;$b < 500;$b++) {
        for($a=1; $a < $b; $a++) {
            $c = sqrt(pow($a, 2) + pow($b, 2));
            if( ($a + $b + $c) == 1000) {
                return $a * $b * $c;
            }
        }
    }
}

function summationOfPrimesBelowN($n) {
    $sum = 0;
    for($i=$n;$i>1;$i--) {
        if(isPrimeP($i)) {
            $sum += $i;
        }
    }

    return $sum;
}

function summationOfPrimesBelow2million() {
    return summationOfPrimesBelowN(2000000);
}

function largestProductOfFourInGrid($grid) {
    $largest = 0;
    for($i=0;$i<count($grid);$i++) {
        $row = $grid[$i];
        for($j=0;$j<count($grid);$j++) {
            if($j <= 16) {
                $leftRight = array_product(array_slice($row, $j, 4));
                $largest = max($largest, $leftRight);
            }
            if($i <= 16) {
                $upDown = $grid[$i][$j] * $grid[$i+1][$j] * $grid[$i+2][$j] * $grid[$i+3][$j];
                $largest = max($largest, $upDown);
            }
            if($i <= 16 && $j <= 16) {
                $tLeft2bRight = $grid[$i][$j] * $grid[$i+1][$j+1] * $grid[$i+2][$j+2] * $grid[$i+3][$j+3];
                $largest = max($largest, $tLeft2bRight);
            }
            if($i >= 3 && $j <= 16) {
                $bLeft2tRight = $grid[$i][$j] * $grid[$i-1][$j+1] * $grid[$i-2][$j+2] * $grid[$i-3][$j+3];
                $largest = max($largest, $bLeft2tRight);
            }
        }
    }

    return $largest;
}

function countFactors($num, $rainbowtable) {
    $factors = getFactors($num, $rainbowtable);
    return count($factors);
}

function firstTraingularNumberWithFiveHundredDivisors() {
    $rainbowtable = array();
    $mostFactors = array('num' => 28, 'numFactors' => 6);
    $num = 28;
    for($i=8;$i<10000;$i++) {
        $num = $num + $i;
        $numFactors = countFactors($num, $rainbowtable);
        if($mostFactors['numFactors'] < $numFactors) {
            $mostFactors['num'] = $num;
            $mostFactors['numFactors'] = $numFactors;
        }

        if($numFactors > 500) {
            return $mostFactors;
        }
    }

    return "\nERROR i=$i factors=".$mostFactors['numFactors'].' number='.$mostFactors['num'];
}

function firstTenDigitsOfSummedGiantGrid($g) {
    $carry = 0;
    $digitArray = array();
    for($i=49;$i>=0;$i--) {
        for($j=0;$j<100;$j++) {
            $num = $g[$j][$i];
            if($j==0) {
                $num += $carry;
                $carry = 0;
            }
            $carry += $num;
        }

        if($i != 0) {
            $digit = $carry % 10;
            $carry = floor($carry / 10);
            array_unshift($digitArray, $digit);
            if(count($digitArray) > 10) {
                array_pop($digitArray);
            }
        } else {
            $str = str_split($carry);
            for($i=count($str) - 1;$i >= 0; $i--) {
                $digit = (int) $str[$i];
                array_unshift($digitArray, $digit);
                if(count($digitArray) > 10) {
                    array_pop($digitArray);
                }
            }
        }
    }

    return $digitArray;
}


echo "####################################################################################\n";
echo "#                                BGINNING TEST SUITE                               #\n";
echo "####################################################################################\n";

testCorrectness('sumOfMultiples3And5Below1000', 233168);                                        //  1
testCorrectness('evenFibonacciBelow4Million', 4613732);                                         //  2
testCorrectness('largestPrimeFactorOfHugeNumber', 6857);                                        //  3
testCorrectness('largestThreeDigitPalindromeProduct', 906609);                                  //  4
testCorrectness('smallestMultipleEvenlyDivisible', 232792560);                                  //  5
testCorrectness('squareOfTheSumsMinusSumOfTheSquaresFor100', 25164150);                         //  6
testCorrectness('TenThousandFirstPrimeNumber', 104743);                                         //  7
testCorrectness('largestProductOf5numbersInN', 40824, $giantNumber8);                           //  8
testCorrectness('pythagoreanTriplet', (float) 31875000);                                        //  9
testCorrectness('summationOfPrimesBelow2million', 142913828922);                                // 10
testCorrectness('largestProductOfFourInGrid', 70600674, $grid11);                               // 11
testCorrectness('firstTenDigitsOfSummedGiantGrid', array(5,5,3,7,3,7,6,2,3,0), $fiftyDigs13);   // 13
