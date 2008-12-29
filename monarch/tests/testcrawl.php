<?php
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors','1');

require_once '../StructuredCrawl.php';
require_once '../ForumPostProcessor.php';
require_once '../ForumThreadProcessor.php';

$THREAD_LEVEL = 0;
$POST_LEVEL = 1;
$NUM_LEVELS = 2;
$MAX_PAGES_TO_CRAWL = 10;

$p_processor = new ForumPostProcessor('threadless');
$t_processor = new ForumThreadProcessor('threadless');

$crawl = new StructuredCrawl($NUM_LEVELS, $MAX_PAGES_TO_CRAWL);
  
$crawl->addURLType('/class="forum_title" href="([^"]+)"/',1,0);
$crawl->addURLType('/class="pagea " href="([^"]+)"/',0,0);
$crawl->addURLType('/class="pagea" href="([^"]+)"/',1,1);

$crawl->addCallback($p_processor,$POST_LEVEL);
$crawl->addCallback($t_processor,$THREAD_LEVEL);
$crawl->beginCrawlFromPage('http://threadless.com/blogs/blogs');
?>