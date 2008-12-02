<?php

// ========================================================================
// spelling
//    args:  string - a block of text
//    ret:   double [0.0 - 1.0]
//    about: Checks how well this person's spelling is. 1.0 means it's 
//           perfect. 0.0 means every word had a mispelling.
// ------------------------------------------------------------------------
public function spelling($text)
{
	$text = explode(' ', $text);
	
	foreach($text as word)
	{
		$q = 'SELECT id
		      FROM dictionary
			  WHERE word = "' . $word . '"';
		
		$q = $database->query($q);
		
		if(mysql_num_rows($q) > 0)
			$numSpelledCorrect++;
	}
	
	return $numSpelledCorrect / sizeof($text);
}


?>