<?php

// TITLE:   DetailStats
// TYPE:    class with automatic instantiation
// AUTHOR:  Ryan Lin, Andrew Spencer
// CREATED: April 14, 2009
// ABOUT:   prints out an XML file showing detailed stats of a website
// USAGE:   detailStats.php?websiteName=1 or 
//          detailStats.php?websiteName=threadless
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

header('Content-Type: text/xml');

require_once('../database/Database.php');
require_once('../constants.php');
require_once('../Url.php');

class DetailStats
{

// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// FIELDS
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

	private $database; // connection to a specific website's database
	private $startPage;  // the start page for this website

// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// PUBLIC METHODS
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

	// ========================================================================
	// DetailStats
	//    args:  int [website ID] or string [name of website]
	//    ret:   none
	//    about: prints out an XML file showing detailed stats of a website
	// ------------------------------------------------------------------------
	public function DetailStats($website)
	{
		$this->database = new Database($website);

		$arr = $this->database->fetch('SELECT startPage FROM regexes');
		$this->startPage = $arr['startPage'];
		
		echo '<detailStats>';
		
		echo '<general>';
		$this->general();
		echo '</general>';
		
		echo '<keywords>';
		$this->keywords();
		echo '</keywords>';
		
		echo '</detailStats>';
	}

// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// PRIVATE METHODS
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

	// ========================================================================
	// keywords
	//    args:  none
	//    ret:   void
	//    about: Prints out general stats about the website
	// ------------------------------------------------------------------------
	private function general()
	{
		// total number of users
		$q = 'SELECT COUNT(*) FROM users';
		$q = $this->database->fetch($q);
		echo '<numberUsers>' . $q[0] . '</numberUsers>';
		
		// posts per day
		$q = 'SELECT ((MAX(time) - MIN(time)) / ' . SECONDS_IN_DAY . ') FROM posts';
		$q = $this->database->fetch($q);
		echo '<postsPerDay>' . $q[0] . '</postsPerDay>';
		
		// number of posts in the last 24 hours
		$q = 'SELECT COUNT(*) 
			FROM posts
			WHERE time > ' . (time() - SECONDS_IN_DAY);
		$q = $this->database->fetch($q);
		echo '<postsToday>' . $q[0] . '</postsToday>';
		
		// number of posts we have scraped
		$q = 'SELECT COUNT(*) FROM posts';
		$q = $this->database->fetch($q);
		echo '<analyzedPosts>' . $q[0] . '</analyzedPosts>';
		
		// number of threads we have scraped
		$q = 'SELECT COUNT(*) FROM threads';
		$q = $this->database->fetch($q);
		echo '<analyzedThreads>' . $q[0] . '</analyzedThreads>';
		
		// people with the most posts
		$q = 'SELECT url, name, posts AS rating 
			FROM users 
			ORDER BY rating DESC
			LIMIT 3';
		$this->usersGroup('chatterboxes', $q);
		
		// recently joined people
		$q = 'SELECT url, name, created AS rating 
			FROM users 
			ORDER BY rating DESC
			LIMIT 3';
		$this->usersGroup('newbies', $q);
		
		// people that joined the site a long time ago (that the crawler knows of)
		$q = 'SELECT url, name, created AS rating 
			FROM users 
			ORDER BY rating ASC
			LIMIT 3';
		$this->usersGroup('veterans', $q);
		
		// threads with the most posts
		$q = 'SELECT title, url, posts AS rating 
			FROM threads 
			ORDER BY rating DESC
			LIMIT 3';
		$this->threadsGroup('livelyThreads', $q);	
	}

	// ========================================================================
	// keywords
	//    args:  none
	//    ret:   void
	//    about: Prints out various groupings of threads for each keyword
	// ------------------------------------------------------------------------
	private function keywords()
	{
		$q = 'SELECT id, word FROM keywords ORDER BY word ASC';
		$keywordsQuery = $this->database->query($q);
		
		while($keywordRow = mysql_fetch_array($keywordsQuery))
		{
			echo '<keyword label="' . $keywordRow['word'] . '">';
		
			// threads that overall talk positively about the keyword
			$q = 'SELECT t.url, t.title, s.goodness AS rating
				FROM threads AS t, threadstats AS ts, stats AS s
				WHERE t.id = ts.thread
				AND ts.keyword = ' . $keywordRow['id'] . '
				AND ts.stat = s.id
				AND s.goodness > 0
				ORDER BY rating DESC
				LIMIT 3';
			
			$this->threadsGroup('loveThreads', $q);
			
			// threads that overall talk negatively about the keyword
			$q = 'SELECT t.url, t.title, s.goodness AS rating
				FROM threads AS t, threadstats AS ts, stats AS s
				WHERE t.id = ts.thread
				AND ts.keyword = ' . $keywordRow['id'] . '
				AND ts.stat = s.id
				AND s.goodness < 0
				ORDER BY rating ASC
				LIMIT 3';
			
			$this->threadsGroup('hateThreads', $q);
			
			// threads that mention the keyword a lot
			$q = 'SELECT t.url, t.title, s.count AS rating
				FROM threads AS t, threadstats AS ts, stats AS s
				WHERE t.id = ts.thread
				AND ts.keyword = ' . $keywordRow['id'] . '
				AND ts.stat = s.id
				ORDER BY rating DESC
				LIMIT 3';
			
			$this->threadsGroup('hotThreads', $q);
			
			// people who talk most positively about the keyword
			$q = 'SELECT u.name, u.url, s.goodness AS rating
				FROM users AS u, userstats AS us, stats AS s
				WHERE u.id = us.user
				AND us.keyword = ' . $keywordRow['id'] . '
				AND us.stat = s.id
				AND s.goodness > 0
				ORDER BY rating DESC
				LIMIT 3';
			
			$this->usersGroup('assKissers', $q);
			
			// people who talk most negatively about the keyword
			$q = 'SELECT u.name, u.url, s.goodness AS rating
				FROM users AS u, userstats AS us, stats AS s
				WHERE u.id = us.user
				AND us.keyword = ' . $keywordRow['id'] . '
				AND us.stat = s.id
				AND s.goodness < 0
				ORDER BY rating ASC
				LIMIT 3';
			
			$this->usersGroup('trashTalkers', $q);
			
			// people who talk a lot about the keyword
			$q = 'SELECT u.name, u.url, s.count AS rating
				FROM users AS u, userstats AS us, stats AS s
				WHERE u.id = us.user
				AND us.keyword = ' . $keywordRow['id'] . '
				AND us.stat = s.id
				ORDER BY rating DESC
				LIMIT 3';
			
			$this->usersGroup('chatterboxes', $q);
			
			// people who talk with good prose about the keyword
			$q = 'SELECT u.name, u.url, s.englishProficiency AS rating
				FROM users AS u, userstats AS us, stats AS s
				WHERE u.id = us.user
				AND us.keyword = ' . $keywordRow['id'] . '
				AND us.stat = s.id
				ORDER BY rating DESC
				LIMIT 3';
			
			$this->usersGroup('sophisticatedOrators', $q);
			
			echo '</keyword>';
		}
	}

	// ========================================================================
	// threadsGroup
	//    args:  string - what type of threads are these? Hate threads? Love 
	//                    threads? etc...
	//           string - MySQL query for all threads of the aforementioned 
	//                    type
	//    ret:   void
	//    about: Prints out a group of threads.
	// ------------------------------------------------------------------------
	private function threadsGroup($type, $query)
	{
		echo '<' . $type . ' label="' . $type . '">';
		
		$threadsQuery = $this->database->query($query);
		
		while($threadRow = mysql_fetch_array($threadsQuery))
			$this->threadNode($threadRow);
		
		echo '</' . $type . '>';
	}
	
	// ========================================================================
	// threadNode
	//    args:  array with the following keys:
	//              * url
	//              * title
	//              * rating
	//    ret:   void
	//    about: Prints out a thread node with data about this thread.
	// ------------------------------------------------------------------------
	private function threadNode($threadData)
	{
		$url = URL::translateURLBasedOnCurrent($this->xml($threadData['url']),$this->startPage);
		$title = $this->xml($threadData['title']);
		$rating = $this->xml($threadData['rating']);
		echo '<thread url="' . $url . '" label="' . $title . '" rating="' . $rating . '"/>';
	}
	
	// ========================================================================
	// usersGroup
	//    args:  string - what type of users are these? Haters? Lovers? etc...
	//           string - MySQL query for all users of the aforementioned 
	//                    type
	//    ret:   void
	//    about: Prints out a group of users.
	// ------------------------------------------------------------------------
	private function usersGroup($type, $query)
	{
		echo '<' . $type . ' label ="' . $type . '">';
		
		$usersQuery = $this->database->query($query);
		
		while($userRow = mysql_fetch_array($usersQuery))
			$this->userNode($userRow);
		
		echo '</' . $type . '>';
	}
	
	// ========================================================================
	// userNode
	//    args:  array with the following keys:
	//              * url
	//              * name
	//              * rating
	//    ret:   void
	//    about: Prints out a user node with data about this user.
	// ------------------------------------------------------------------------
	private function userNode($userData)
	{
		$url = URL::translateURLBasedOnCurrent($this->xml($userData['url']),$this->startPage);
		$title = $this->xml($userData['name']);
		$rating = $this->xml($userData['rating']);
		echo '<user url="' . $url . '" label="' . $title . '" rating="' . $rating . '"/>';
	}

	// ========================================================================
	// xml
	//    args:  XML node data - string
	//    ret:   "Cleaned" parameter data
	//    about: Escapes all characters in the parameter string that might cause
	//        an XML parser to barf.
	// ------------------------------------------------------------------------
	private function xml($data) {
		$newData = str_replace('&', '', $data);
		$newData = str_replace('<', '\<', $newData);
		return $newData;
	}

}

if(isset($_GET['websiteName']))
	$detailStats = new DetailStats($_GET['websiteName']);
else
	die('USAGE: detailStats.php?websiteName=1 or detailStats.php?websiteName=threadless');

?>