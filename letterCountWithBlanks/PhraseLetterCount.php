<?php

$lcp_obj = new PhraseLetterCount();

echo $lcp_obj->phraseLetterCount('This flat-screen TV contains exactly', array('e')) . "\n";

echo $lcp_obj->phraseLetterCount('This flat-screen TV contains exactly', array('e', 'n')) . "\n";

echo $lcp_obj->phraseLetterCount('This flat-screen TV contains exactly', array('e', 'n', 's')) . "\n";

echo $lcp_obj->phraseLetterCount('This flat-screen TV contains exactly', array('e', 'n', 's', 't')) . "\n";

echo $lcp_obj->phraseLetterCount('This flat-screen TV contains exactly', array('e', 'n', 's', 't'), true) . "\n";

class PhraseLetterCount
{

    const max_tries = 50;
    
    // Will be better off using http://pear.php.net/package/Numbers_Words/redirected in the future
    protected $numbers_as_words = array(0 => 'zero', 1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four',
        5 => 'five', 6 => 'six', 7 => 'seven', 8 => 'eight', 9 => 'nine', 10 => 'ten', 11 => 'eleven',
        12 => 'twelve', 13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen', 16 => 'sixteen', 17 => 'seventeen',
        18 => 'eighteen', 19 => 'nineteen', 20 => 'twenty');
    
    protected $original_phrase;
    protected $phrase;
    protected $solution_phrase;
    protected $is_case_sensitive;
    
    protected $letter_array;
    protected $letter_count_array;
    
    function phraseLetterCount($phrase, $letter_array, $is_case_sensitive = false) 
    {
        $this->original_phrase = $phrase;
        $this->letter_array = $letter_array;
        $this->is_case_sensitive = $is_case_sensitive;
        
        $this->createPhraseWithBlanks();
        
        $solved = false;
        $tries = 0;
        
        while (!$solved && $tries < self::max_tries) {
            $tries++;
            $current_solved_phrase = $this->solution_phrase;
            
            $this->fillInBlanks();
            
            if (strcmp($current_solved_phrase, $this->solution_phrase) === 0) {
                $solved = true;
            }
        }
        
        if (!$solved) {
            $this->addFailedMessage();
        }
        
        return $this->solution_phrase;        
    }
    
    function createPhraseWithBlanks()
    {
        $this->phrase = $this->original_phrase;
        
        $letter_count = count($this->letter_array);
        
        for ($i = 0; $i < $letter_count; $i++) {
            $this->phrase .= (($i >= 1 && $i == ($letter_count - 1)) ? ' and' : '') . " %{$i} {$this->letter_array[$i]}'s";
        }
        
        $this->solution_phrase = $this->phrase;
    }
    
    function fillInBlanks()
    {
        $count = 0;
        
        if ($this->is_case_sensitive) {
            $current_solution_phrase = strtolower($this->solution_phrase);
        } else {
            $current_solution_phrase = $this->solution_phrase;
        }
        
        $this->solution_phrase = $this->phrase;
        foreach ($this->letter_array AS $letter) {
            $this->solution_phrase = str_replace("%{$count}", $this->numbers_as_words[substr_count($current_solution_phrase, $letter)], $this->solution_phrase);
            $count++;
        }
    }
    
    function addFailedMessage()
    {
        $this->phrase = 'We were unable to solve "' . $this->phrase . '"';
    }
} 