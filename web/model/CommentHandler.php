<?php

require_once dirname(__FILE__) . '/DbHandler.php';
require_once dirname(__FILE__) . '/Comment.php';
require_once dirname(__FILE__) . '/User.php';
require_once dirname(__FILE__) . '/API.php';

class CommentHandler
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
        if($content = @file_get_contents($url)) {
            $header = get_headers($url);
            if($header[0] != 'HTTP/1.1 200 OK') return false;
            if($json = json_decode($content)) {
                return $json;
            }
        }
        Log::apiError('could not get content ' , $url);
        return false;
    }

    public function getComments($id) {

        $comments = array();
        $url = $this->_api->GetURL() . 'comments?snippetid=' . $id;  
        if($json = $this->getJson($url)) {
            $header = get_headers($url);
        	if($header[0] == 'HTTP/1.1 200 OK') {
                foreach($json as $j) {
                    $comments[] = new Comment($j->snippetId, $j->commentId, $j->userId, $j->comment, $j->comment_created_date, $j->name);
                }
                return $comments;
            }
        }
        return false;
    }

    /**
     * CommentHandler::addComment()
     *
     * @return true if successful
     * use it if you want to add a new commet för a snippet
     * @param snippetId, userId, commentText and apikey
     */
    public function addComment($snippetID, $userID, $comment) {
        $url = $this->_api->GetURL() . "comments";
        $query = array('snippetid' => $snippetID, 'userid' => $userID, 'comment' => $comment, 'apikey' => AuthHandler::getApiKey());
        
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

        $result = curl_exec($post);
        $httpCode = curl_getinfo($post, CURLINFO_HTTP_CODE);
        
        curl_close($post);
        
        if($httpCode == 200) return true;
        
        Log::apiError('could not create comment on snippet: ' . $snippetID, $url);
        return $result;
    }
    /**
     * CommentHandler::updateComment()
     *
     * @return true if successful
     * use it if you want to update a comment that exists in the database
     * @param commentId, userId, commentText and apikey
     */
    public function updateComment(Comment $comment) {
        
        $url = $this->_api->GetUrl() . 'comments';
        $query = array('commentid' => $comment->getCommentId(), 'userid' => $comment->getUserId(), 'comment' => $comment->getCommentText(), 'apikey' => AuthHandler::getApiKey());
        
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
        
        if(!$result) Log::apiError('could not update comment: ' . $comment->getCommentId(), $url);   
        
        return $result;
    }

    /**
     * CommentHandler::deleteComment()
     *
     * @return true if successful
     * use it if you want to delete a comment
     * @param an id of the comment to delete
     */
    public function deleteComment(Comment $comment)
    {

        $url = $this->_api->GetURL() . 'comments/' . $comment->getCommentId() . '/' . $comment->getUserId() . '/' . AuthHandler::getApiKey();
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        $result = curl_exec($ch);

        if(!$result) {
            Log::apiError('could not delete snippet:' . $comment->getID(), $url);
        }
        
        return $result;
    }

    /**
     * CommentHandler::getCommentToEditByCommentId()
     *
     * @return Comment object to edit
     * @param id of the comment you want to edit
     *
     */
    public function getCommentByID($id)
    {
        $comment = null;
        $url = $this->_api->GetURL() . "comments?commentid=" . $id;
        if($json = $this->getJson($url)) {
            foreach($json as $j)
            {
                $comment = new Comment($j->snippetId, $j->commentId, $j->userId, $j->comment, $j->comment_created_date);
            }
            return $comment;
        }
        return false;
    }

    /**
     * @return Array Comment
     * @param id of user
     *
     */
    public function getCommentsByUser($id)
    {
        $comments = null;
        $url = $this->_api->GetURL() . "api/comments?userid=" . $id;
        if($json = $this->getJson($url)) {
            foreach($json as $j)
            {
                $comments[] = new Comment($j->snippetid, $j->commentid, $j->userid, $j->comment);
            }
            return $comments;
        }
        return false;
    }

}