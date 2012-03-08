<?php

require_once 'DbHandler.php';
require_once 'Snippet.php';
require_once 'API.php';

class SnippetHandler
{

    private $_dbHandler;
    private $_api;

    public function __construct()
    {
        $this->_dbHandler = new DbHandler();
        $this->_api = new API();
    }

    /**
     * Check response and content
     * @param string URL, url to API
     * @return json content on succsess or FALSE failiure
     */
    private function getJson($url) {
        if($this->_api->checkApiUrl($url)) {
            if($content = file_get_contents($url)) {
                if($json = json_decode($content)) {
                    return $json;
                }
            }
        }
        return false;
    }

    /**
     *Get a snippet by id
     * @param int $aID id of a snippet
     * @return Snippet
     */
    public function getSnippetByID($id)
    {
        $snippet = null;
        $url = $this->_api->GetURL() . "snippets/?id=".$id;
        if($json = $this->getJson($url)) {
            foreach($json as $j)
            {
                $snippet = new Snippet($j->userid, $j->username, $j->code, $j->title, $j->description, $j->languageid, $j->date, "0000-00-00 00:00:00", $j->id);
            }
            return $snippet;
        }
        return false;
    }
    
    /**
        * Get all the snippets
        * @return array
    */
    public function getAllSnippets()
    {
        $url = $this->_api->GetURL() . "snippets?limit=20";

        //Check if page contain json and if http_respons is 200
        if($json = $this->getJson($url)) {
            $snippets = array();
            foreach ($json as $snippet) {
                $snippets[] = new Snippet($snippet->userid, $snippet->username, $snippet->code, $snippet->title, $snippet->description, $snippet->languageid, $snippet->date, "0000-00-00 00:00:00", $snippet->id);
            }
            return $snippets;
        }
        return false;
    }

    /**
     * @return Array Snippets
     * @param id of user
     *
     */
    public function getSnippetsByUser($id)
    {
        $snippetArr = array();
        $this->_dbHandler->__wakeup();
        if ($stmt = $this->_dbHandler->prepareStatement("SELECT * FROM snippet WHERE user_id = ?")) {
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->bind_result($id, $userID, $code, $title, $description, $languageID, $created, $updated);

            while ($stmt->fetch()) {
                $temtSnippet = new Snippet($userID, $code, $title, $description, $languageID, $created, $updated, $id);
                array_push($snippetArr, $temtSnippet);
            }
            $stmt->close();
        } else {
            $stmt->close();
            $this->_dbHandler->close();
            return false;
        }
        $this->_dbHandler->close();
        return $snippetArr;
    }

    /**
     * @param int ID of user
     * @return array snippet objects
     */
    public function getRatedSnippetsByUser($id, $rating)
    {
        $snippetArr = array();
        $this->_dbHandler->__wakeup();
        if ($stmt = $this->_dbHandler->prepareStatement("SELECT snippet.id, snippet.title, snippet.description, snippet.language
                                                        FROM snippet
                                                        INNER JOIN rating
                                                        ON rating.snippetId = snippet.id
                                                        WHERE rating.rating = ?
                                                        AND rating.userId = ?")) {
            $stmt->bind_param('ii', $rating, $id);
            $stmt->execute();
            $stmt->bind_result($id, $title, $description, $lang);
            while ($stmt->fetch()) {
                $snippet = new Snippet(null, null, $title, $description, $lang, null, null, $id);
                array_push($snippetArr, $snippet);
            }
            $stmt->close();
        }
        $this->_dbHandler->close();
        return $snippetArr;
    }

    /**
     * @param int ID of user
     * @return Array
     */
    public function getCommentedSnippetByUser($id)
    {
        $snippetArr = array();
        $this->_dbHandler->__wakeup();
        if ($stmt = $this->_dbHandler->prepareStatement("SELECT snippet.id, snippet.title, snippet.description,comment.comment
                                                        FROM snippet
                                                        INNER JOIN comment
                                                        ON comment.snippet_id = snippet.id
                                                        WHERE comment.user_id = ?")) {
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->bind_result($id, $title, $description,$comment);
            $i = 0;
            while ($stmt->fetch()) {
                $snippetArr[$i]['id'] = $id;
                $snippetArr[$i]['title'] = $title;
                $snippetArr[$i]['description'] = $description;
                $snippetArr[$i]['comment'] = $comment;
                $i++;
            }
            $stmt->close();
        }
        $this->_dbHandler->close();
        return $snippetArr;
    }
    
    /**
     * Creates a snippet via the API
     * @param Snippet $snippet
     * @return bool
     */
    public function createSnippet(Snippet $snippet)
    {
        $author = $snippet->getAuthorId();
        $code = $snippet->getCode();
        $title = $snippet->getTitle();
        $desc = $snippet->getDesc();
        $language = $snippet->getLanguageID();
        $created = $snippet->getCreatedDate();
        
        $url = $this->_api->GetURL() . "snippets";
        $query = array('userid' => $snippet->getAuthorId(), 'code' => $snippet->getCode(), 'desc' => $snippet->getDesc(), 'title' => $snippet->getTitle(), 'languageid' => $snippet->getLanguageID(), 'apikey' => '5435gdfhghdghdf');
        
        $fields = '';
        foreach ($query as $key => $value) {
            $fields .= $key . '=' . $value . '&';
        }
        rtrim($fields, '&');
        
        $post = curl_init();

        curl_setopt($post, CURLOPT_URL, $url);
        curl_setopt($post, CURLOPT_POST, count($query));
        curl_setopt($post, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);

        $result = json_decode(curl_exec($post));

        curl_close($post);
        
        if(!$result) {
            Log::apiError('could not create snippet', $url);
            return false;
        }

        return $result->id;
    }

    /**
     * Updates a snippet via the API
     * @param string $snippetName, string $snippetCode, string $snippetDesc, int $snippetID, date $updated
     * @return bool
     */
    public function updateSnippet($snippetName, $snippetCode, $snippetDesc, $snippetID, $updated)
    {
        $url = $this->_api->GetURL() . "snippets";
        $query = array('id' => $snippetID, 'userid' => 2, 'code' => $snippetCode, 'desc' => $snippetDesc, 'title' => $snippetName, 'languageid' => '2', 'apikey' => '5435gdfhghdghdf');
        
        $fields = '';
        foreach ($query as $key => $value) {
            $fields .= $key . '=' . $value . '&';
        }
        rtrim($fields, '&');
        
        $post = curl_init();
        
        curl_setopt($post, CURLOPT_URL, $url);
        curl_setopt($post, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($post, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($post);

        curl_close($post);
        if(!$post) {
            Log::apiError('could not update snippe:'. $snippetID, $url);
        }

        return $result;
    }

    public function deleteSnippet(Snippet $snippet)
    {
        $url = $this->_api->GetURL() . 'snippets/' . $snippet->getID() . '/2/5435gdfhghdghdf';
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        $result = curl_exec($ch);

        if(!$result) {
            Log::apiError('could not delete snippet:'.$snippet->getID(), $url);
        }
        
        return $result;
    }

    /**
     * returns a defined number of snippets
     * @param int number of snippets to return
     * @return array with snippets
     */
    public function getNrOfSnippets($nr)
    {
        if ($nr <= 0) {
            $nr = 1;
        }
        $snippets = array();

        $this->_dbHandler->__wakeup();
        if ($stmt = $this->_dbHandler->PrepareStatement("SELECT * FROM snippet LIMIT ?")) {
            $stmt->bind_param("i", $nr);
            $stmt->execute();

            $stmt->bind_result($id, $code, $author, $title, $description, $language, $created, $updated);
            while ($stmt->fetch()) {
                $snippet = new Snippet($author, $code, $title, $description, $language, $created, $updated, $id);
                array_push($snippets, $snippet);
            }

            $stmt->close();
        } else {
            return null;
        }

        $this->_dbHandler->close();

        return $snippets;
    }

    /**
     * returns array with namne and id of language
     * @param int id of language
     * @return Array
     */
    public function getLanguageByID($id)
    {
        $language = array();
        $this->_dbHandler->__wakeup();
        if ($stmt = $this->_dbHandler->PrepareStatement("SELECT * FROM snippet_language WHERE id = ?")) {

            $stmt->bind_param("i", $id);
            $stmt->execute();

            $stmt->bind_result($id, $name);
            while ($stmt->fetch()) {
                $language = array('id' => $id, 'name' => $name);
            }

            $stmt->close();

        } else {
            return null;
        }
        $this->_dbHandler->Close();
        return $language;

    }

    /**
     * returns array with namne and id of all languages
     *@return Array
     */
    public function getLanguages()
    {
        $languages = array();
        $this->_dbHandler->__wakeup();
        if ($stmt = $this->_dbHandler->PrepareStatement("SELECT * FROM snippet_language")) {
            $stmt->execute();

            $stmt->bind_result($id, $name);
            while ($stmt->fetch()) {
                $language = array('id' => $id, 'name' => $name);
                array_push($languages, $language);
            }

            $stmt->close();

        } else {
            return null;
        }
        $this->_dbHandler->Close();
        return $languages;
    }

    /**
     * returns array with total votes, likes and dislikes
     * @param int id of snippet
     * @return Array
     */
    public function getSnippetRating($id)
    {
        $rating = array();

        $this->_dbHandler->__wakeup();
        if ($stmt = $this->_dbHandler->PrepareStatement("SELECT COUNT(IF(`rating` = 1, 1, null)) AS Likes, COUNT(IF(`rating` = 0, 1, null)) AS Dislikes FROM `rating` WHERE `snippetId` = ?")) {
            $stmt->bind_param("i", $id);
            $stmt->execute();

            $stmt->bind_result($likes, $dislikes);
            if ($stmt->fetch()) {
                $rating['total'] = $likes + $dislikes;
                $rating['likes'] = $likes;
                $rating['dislikes'] = $dislikes;
            }

            $stmt->close();

        }

        $this->_dbHandler->Close();

        return $rating;
    }

    public function searchByTitle($title)
    {
        $titles = array();
        $this->_dbHandler->__wakeup();
        if ($stmt = $this->_dbHandler->PrepareStatement("SELECT id, author ,code, title, description, language, created, updated FROM (snippet) WHERE MATCH title AGAINST (?) ")) {
            $stmt->bind_param("s", $title);
            $stmt->execute();

            $stmt->bind_result($id, $author,$code,$title,$description,$language, $created, $updated);
            while ($stmt->fetch()) {
                $answer = new Snippet($author, $code, $title, $description, $language, $id, $created, $updated);
                array_push($titles, $answer);
            }

            $stmt->close();

        } else {
            return null;
        }
        $this->_dbHandler->Close();
        return $titles;
    }

    public function searchByDescription($description)
    {
        $titles = array();
        $this->_dbHandler->__wakeup();
        if ($stmt = $this->_dbHandler->PrepareStatement("SELECT id, author,code,title,description,language, created, updated FROM (snippet) WHERE MATCH description AGAINST (?) ")) {
            $stmt->bind_param("s", $description);
            $stmt->execute();

            $stmt->bind_result($id, $author,$code,$title,$description,$language, $created, $updated);
            while ($stmt->fetch()) {
                $answer = new Snippet($author, $code, $title, $description, $language, $id, $created, $updated);
                array_push($titles, $answer);
            }

            $stmt->close();

        } else {
            return null;
        }
        $this->_dbHandler->Close();
        return $titles;
    }

    public function searchByCode($code)
    {
        $titles = array();
        $this->_dbHandler->__wakeup();
        if ($stmt = $this->_dbHandler->PrepareStatement("SELECT id, author,code,title,description,language, created, updated FROM (snippet) WHERE MATCH code AGAINST (?) ")) {
            $stmt->bind_param("s", $code);
            $stmt->execute();
            $stmt->bind_result($id, $author,$code,$title,$description,$language, $created, $updated);
            
            while ($stmt->fetch()) {
                $answer = new Snippet($author, $code, $title, $description, $language, $id, $created, $updated);
                array_push($titles, $answer);
            }

            $stmt->close();

        } else {
            return null;
        }
        $this->_dbHandler->Close();
        return $titles;
    }

    public function fullSearch($q)
    {
        $titles = array();
        $this->_dbHandler->__wakeup();
                
        $sqlQuery = "   SELECT snippet.id, snippet.author, snippet.code, snippet.title, snippet.description, snippet.language, snippet.created, snippet.updated
                        FROM snippet
                        WHERE MATCH code, title, description
                        AGAINST (? IN BOOLEAN MODE)";
        

        if ($stmt = $this->_dbHandler->PrepareStatement($sqlQuery)) {
            $stmt->bind_param("s", $q);
            $stmt->execute();

            $stmt->bind_result($id, $author, $code, $title, $description, $language, $created, $updated);
            while ($stmt->fetch()) {
                $answer = new Snippet($author, $code, $title, $description, $language, $id, $created, $updated);
                array_push($titles, $answer);
            }

            $stmt->close();

        } else {
            return null;
        }
        $this->_dbHandler->Close();
        return $titles;
    }

    public function fullSearchWithLang($q, $lang)
    {
        $titles = array();
        $this->_dbHandler->__wakeup();
        
        $sqlQuery = "   SELECT snippet.id, snippet.author, snippet.code, snippet.title, snippet.description, snippet.language, snippet.created, snippet.updated
                        FROM snippet
                        LEFT JOIN snippet_language ON snippet.language = snippet_language.id
                        WHERE MATCH code, title, description
                        AGAINST (? IN BOOLEAN MODE)
                        AND snippet_language.id = ?";

        if ($stmt = $this->_dbHandler->PrepareStatement($sqlQuery)) {
            $stmt->bind_param("si", $q, $lang);
            $stmt->execute();
            $stmt->bind_result($id, $author,$code,$title,$description,$language, $created, $updated);

            while ($stmt->fetch()) {
                $answer = new Snippet($author, $code, $title, $description, $language, $id, $created, $updated);
                array_push($titles, $answer);
            }

            $stmt->close();

        } else {
            return null;
        }
        $this->_dbHandler->Close();
        return $titles;
    }
    
    public function SetDate()
    {
        return date("Y-m-d H:i:s");
    }

}
