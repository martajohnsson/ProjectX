<?php

require_once dirname(__file__) . '/DbHandler.php';
require_once dirname(__FILE__) . '/Snippet.php';
require_once dirname(__FILE__) . '/API.php';
require_once dirname(__FILE__) . '/AuthHandler.php';
require_once dirname(__FILE__) . '/Language.php';

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
        $header = get_headers($url);
        if($header[0] == 'HTTP/1.1 200 OK') {
            if($content = @file_get_contents($url)) {
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
        $url = $this->_api->GetURL() . "snippets/?id=" . $id;
        if($json = $this->getJson($url)) {
            foreach($json as $j)
            {
                $snippet = new Snippet($j->userid, $j->name, $j->code, $j->title, $j->description, $j->languageid, $j->date, $j->updated, $j->language, $j->id);
            }
            
            return $snippet;
        }
        return false;
    }

    /**
     * @return Array Snippets
     * @param id of user
     *
     */
    public function getSnippetsByUser($userid)
    {
        $snippets = null;
        $url = $this->_api->GetURL() . "snippets/?userid=" . $userid;
        if($json = $this->getJson($url)) {
            foreach($json as $j)
            {
                $snippets[] = new Snippet($j->userid, $j->name, $j->code, $j->title, $j->description, $j->languageid, $j->date, $j->updated, $j->language, $j->id);
            }
            return $snippets;
        }
        return false;
    }

    /**
     * @param int ID of user
     * @return array snippet objects
     */
    public function getRatedSnippetsByUser($id, $rating)
    {
        $snippets = null;
        $url = $this->_api->GetURL() . "ratings?userid=" . $id . "&rating=" . $rating;
        if($json = $this->getJson($url)) {
            foreach($json as $j)
            {
                $snippets[] = new Snippet(null, null, null, $j->title, null, null, null, null, null, $j->snippetId);
            }
            return $snippets;
        }
        return false;
    }

    /**
     * @param int ID of user
     * @return Array
     */
    public function getCommentedSnippetByUser($id)
    {
        $snippets = null;
        $url = $this->_api->GetURL() . "comments?userid=" . $id;
        if($json = $this->getJson($url)) {
            foreach($json as $j)
            {
                $snippets[] = new Snippet(null, null, null, $j->title, null, null, null, null, null, $j->snippetId);
            }
            return $snippets;
        }
        return false;
    }
    
    /**
     * Creates a snippet via the API
     * @param Snippet $snippet
     * @return bool
     */
    public function createSnippet(Snippet $snippet)
    {
        
        $url = $this->_api->GetURL() . "snippets";
        $query = array('userid' => $snippet->getAuthorId(), 'code' => $snippet->getCode(), 'desc' => $snippet->getDesc(), 'title' => $snippet->getTitle(), 'languageid' => $snippet->getLanguageID(), 'apikey' => AuthHandler::getApiKey());
        
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
        
        if(!isset($result->id)) {
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
    public function updateSnippet(Snippet $snippet)
    {
        $url = $this->_api->GetURL() . "snippets";
        $query = array('id' => $snippet->getID(), 'userid' => $snippet->getAuthorId(), 'code' => $snippet->getCode(), 'desc' => $snippet->getDesc(), 'title' => $snippet->getTitle(), 'languageid' => $snippet->getLanguageID(), 'apikey' => AuthHandler::getApiKey());
        
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
        $url = $this->_api->GetURL() . 'snippets/' . $snippet->getID() . '/' . $snippet->getAuthorId() . '/' . AuthHandler::getApiKey();
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        $result = curl_exec($ch);

        if(!$result) {
            Log::apiError('could not delete snippet:' . $snippet->getID(), $url);
        }
        
        return $result;
    }

    /**
     * returns array with namne and id of language
     * @param int id of language
     * @return Array
     */
    public function getLanguageByID($id)
    {
        $language = null;
        $url = $this->_api->GetURL() . "languages?languageid=" . $id;
        if($json = $this->getJson($url)) {
            foreach($json as $j)
            {
                $language = new Language($j->languageId, $j->language);
            }
            return $language;
        }
        return false;
    }

    /**
     * returns array with namne and id of all languages
     *@return Array
     */
    public function getLanguages()
    {
        $languages = null;
        $url = $this->_api->GetURL() . "languages";
        if($json = $this->getJson($url)) {
            foreach($json as $j)
            {
                $languages[] = new Language($j->languageId, $j->language);
            }
            return $languages;
        }
        return false;
    }

    /**
     * returns array with total votes, likes and dislikes
     * @param int id of snippet
     * @return Array
     */
    public function getSnippetRating($id)
    {
        $rating = null;
        $url = $this->_api->GetURL() . "snippets?id=" . $id;
        if($json = $this->getJson($url)) {
            foreach($json as $j)
            {
                $rating['total'] = $j->thumbsup + $j->thumbsdown;
                $rating['likes'] = $j->thumbsup;
                $rating['dislikes'] = $j->thumbsdown;
            }
            return $rating;
        }
        return false;
    }

    /**
     * Report a snippet
     * @param int snipppetId
     * @param int userId
     * @param string message
     * @return true on success
     */
    public function reportSnippet($snippetId, $userId, $message)
    {
        $this->_dbHandler->__wakeup();
        $result = false;
        if($stmt = $this->_dbHandler->prepareStatement("INSERT INTO reported_snippet (message, user_id, snippet_id) VALUES (?,?,?)")){
            $stmt->bind_param('sii', $message, $userId, $snippetId);
            $stmt->execute();
            $stmt->store_result();
            if($stmt->affected_rows == 1) {
                $result = true;
            }
        } 

        $this->_dbHandler->close();
        $stmt->close();
        return $result;
    }

    /**
     * Get all reports of reported snippets
     */
    public function getReportedSnippets()
    {
        $this->_dbHandler->__wakeup();
        $reports = array();
        if($stmt = $this->_dbHandler->prepareStatement("SELECT r.id, r.message, r.snippet_id, u.name, u.id, u.username
                                                        FROM reported_snippet as r
                                                        inner join user as u
                                                        on r.user_id = u.id")) {
            $stmt->execute();
            $stmt->bind_result($id, $message, $snippetId, $userName, $userId, $email);
            
            $i = 0;
            while($stmt->fetch()) {
                $reports[$i]['id'] = $id;
                $reports[$i]['message'] = $message;
                $reports[$i]['userid'] = $userId;
                $reports[$i]['username'] = $userName;
                $reports[$i]['snippetid'] = $snippetId;
                $reports[$i]['email'] = $email;
                $i++;
            }
            $stmt->close();
        }
        $this->_dbHandler->close();

        return $reports;
    }

    public function deleteReport($id)
    {   
        $result = false;
        $this->_dbHandler->__wakeup();
        if($stmt = $this->_dbHandler->prepareStatement("DELETE FROM reported_snippet WHERE id = ?")) {
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->store_result();

            if($stmt->affected_rows == 1){
                $result = true;
            }
            $stmt->close();
        }
        $this->_dbHandler->close();
        return $result;
    }
    
    public function SetDate()
    {
        return date("Y-m-d H:i:s");
    }
}