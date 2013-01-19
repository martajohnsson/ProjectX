<?php
require_once dirname(__FILE__) . '/API.php';
require_once dirname(__FILE__) . '/Snippet.php';

$api = new API();
$snippets = null;
$html = "";

$query = $_GET['query'];
$url = $api->GetURL() . "search/" . $query . "*";

if ($json = json_decode(@file_get_contents($url))) {
    $header = get_headers($url);
    if($header[0] != 'HTTP/1.1 200 OK') {
        echo "<p>No results</p>";
        return;
    }
    foreach($json as $j)
    {
        $snippets[] = new Snippet($j->userid, $j->name, $j->code, $j->title, $j->description, $j->languageid, $j->date, $j->updated, $j->language, $j->id);
    }
    
    foreach ($snippets as $snippet) {
        $html .= '
            <div class="snippet-list-item">
                <div class="snippet-title">
                    <h2><a href="?page=listsnippets&snippet=' . $snippet->getID() . '">' . $snippet->getTitle() . '</a></h2>
                </div>
                <div class="snippet-description">
                    <p>' . $snippet->getDesc() . '</p>
                </div>
                <div class="snippet-author muted">
                    <p>Posted by: <i>' . $snippet->getAuthor() . '</i></p>
                </div>
            </div>
        ';
    }
    
    echo $html;
} else {
    echo "<p>No results</p>";
}