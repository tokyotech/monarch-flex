<?php

// TITLE:  linguisticTest.php
// TYPE:   excutable
// AUTHOR: Ryan Lin
// DATE:   12/04/2008
// ABOUT:  Testing the functions in Linguistics.php
// ================================================================================

/*
error_reporting(E_ALL);
ini_set('display_errors','1');
*/

require_once('Linguistics.php');

$linguistics = new Linguistics();

$a = 'Adobe is bad. It sucks. I hate it.';
$b = 'Adobe is not bad. It does not suck. I don\'t hate it';
$g = 'Adobe is good. It is great. I love it.';
$h = 'Adobe is very good. It is so great. I love it.';
$o = 'Adobe sucks. I love Adobe.';
$c = 'My spelleeng iss horibel. I am nott a trustwoorthy persun';
$d = 'My spelling is good. My posts can be trusted';
$e = 'I am bad at " punctuation ) and (cannot) be "trusted"';
$f = 'I am good at "punctuation" and (can) "be" trusted.';
$i = 'i rush when typing... often i don\'t capitalize!! don\'t trust me?!?! I only capitalize one sentence';
$j = 'I am a careful writer! I take my time to capitalize. Trust me!';
$k = 'With the economy deteriorating rapidly, the nation’s employers shed 533,000 jobs in November, the 11th consecutive monthly decline, the government reported Friday morning, and the unemployment rate rose to 6.7 percent.';
$l = "I played the cards I was given, thank God I'm still livin
Pack my nine til it's time to go to prison
As I'm bailin down the block that I come from, still gotta pack a gun
Case some young motherfuckers wanna play dumb
I guess I live life forever jugglin
But I'll be hustlin til the early mornin cause I'm strugglin
Like drinkin liquor make the money come quicker
Gettin pages from my bitch it's time to dick her
I ain't in love with her, I just wanna be the one to hit her
Drop off and let the next nigga get her
That's the way it goes, it's time to shake a hoe, make the dough
Break a hoe when it's time to make some mo'
I keep my finger on the trigger of my Glock
Ridin down the block lickin shots at the punk-ass *cops*
And spittin game through my mobile phone
The type of shit to get them hoes to bone
My Definition of a Thug Nigga";
$m = 'old ur breath and copy this and then paste it to another vid. if u dont let ur breath go ur a gd kisser. (i managed) ';
$n = 'do you like sci-fi? watch Firefly.
do you like westerns? watch Firefly.
do you like space? watch Firefly.
do you like well-written dialog? watch Firefly.
do you like awesome characters? watch Firefly.

ARE YOU GETTING THIS?';

echo '<h1>linguistics test</h1>';
echo '<hr><h2>trash talking</h3>';

printf('<i>%s</i><br /><br /><b>goodness of "adobe":</b> %f', $a, $linguistics->goodness('adobe', $a));
echo '<hr><h2>negated trash talking</h3>';
printf('<i>%s</i><br /><br /><b>goodness of "adobe":</b> %f', $b, $linguistics->goodness('adobe', $b));
echo '<hr><h2>ass kissing</h3>';
printf('<i>%s</i><br /><br /><b>goodness of "adobe":</b> %f', $g, $linguistics->goodness('adobe', $g));
echo '<hr><h2>severe ass kissing</h3>';
printf('<i>%s</i><br /><br /><b>goodness of "adobe":</b> %f', $h, $linguistics->goodness('adobe', $h));
echo '<hr><h2>split personality</h3>';
printf('<i>%s</i><br /><br /><b>goodness of "adobe":</b> %f', $o, $linguistics->goodness('adobe', $o));
echo '<hr><h2>misspelling</h3>';
printf('<i>%s</i><br /><br /><b>spelling:</b> %f', $c, $linguistics->spelling($c));
echo '<hr><h2>correct spelling</h3>';
printf('<i>%s</i><br /><br /><b>spelling:</b> %f', $d, $linguistics->spelling($d));
echo '<hr><h2>bad punctuation</h3>';
printf('<i>%s</i><br /><br /><b>punctuation:</b> %f', $e, $linguistics->punctuation($e));
echo '<hr><h2>good punctuation</h3>';
printf('<i>%s</i><br /><br /><b>punctuation:</b> %f', $f, $linguistics->punctuation($f));
echo '<hr><h2>bad capitalization</h3>';
printf('<i>%s</i><br /><br /><b>capitalization:</b> %f', $i, $linguistics->capitalization($i));
echo '<hr><h2>good capitalization</h3>';
printf('<i>%s</i><br /><br /><b>capitalization:</b> %f', $j, $linguistics->capitalization($j));
echo '<hr><h2>new york times</h3>';
printf('<i>%s</i><br /><br /><b>english proficiency:</b> %f', $k, $linguistics->englishProficiency($j));
echo '<hr><h2>tupac lyrics</h3>';
printf('<i>%s</i><br /><br /><b>english proficiency:</b> %f<br />', $l, $linguistics->englishProficiency($l));
printf('<b>capitalization:</b> %f<br />', $linguistics->capitalization($l));
printf('<b>punctuation:</b> %f<br />', $linguistics->punctuation($l));
printf('<b>spelling:</b> %f<br/>', $linguistics->spelling($l));
printf('<b>goodness of "hoe":</b> %f<br/>', $linguistics->goodness('hoe', $l));
printf('<b>goodness of "god":</b> %f', $linguistics->goodness('god', $l));
echo '<hr><h2>idiot from youtube</h3>';
printf('<i>%s</i><br /><br /><b>english proficiency:</b> %f<br />', $m, $linguistics->englishProficiency($m));
printf('<b>capitalization:</b> %f<br />', $linguistics->capitalization($m));
printf('<b>punctuation:</b> %f<br />', $linguistics->punctuation($m));
printf('<b>spelling:</b> %f', $linguistics->spelling($m));
echo '<hr><h2>idiot from threadless</h3>';
printf('<i>%s</i><br /><br /><b>english proficiency:</b> %f<br />', $n, $linguistics->englishProficiency($n));
printf('<b>capitalization:</b> %f<br />', $linguistics->capitalization($n));
printf('<b>punctuation:</b> %f<br />', $linguistics->punctuation($n));
printf('<b>spelling:</b> %f<br />', $linguistics->spelling($n));
printf('<b>goodness of "firefly":</b> %f', $linguistics->goodness('firefly', $n));

?>