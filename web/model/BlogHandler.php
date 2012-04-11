<?php

require_once dirname(__file__) . '/Blogpost.php';
require_once dirname(__file__) . '/DbHandler.php';

class BlogHandler
{
    private $_dbHandler;
    
    public function __construct()
    {
        $this->_dbHandler = new DbHandler();    
    }
    
    /**
     * Returns all blogposts
     * @return array
     */
    public function getAllBlogposts()
    {
        $blogposts = array();
        $sqlQuery = "SELECT blogg.id, blogg.title, blogg.content, blogg.date, user.name, user.id  
                        FROM blogg
                        INNER JOIN user
                        ON user.id = blogg.user_id
                        ORDER by blogg.id DESC
        ";
        
        if ($stmt = $this->_dbHandler->PrepareStatement($sqlQuery)) {
            $stmt->execute();
            $stmt->bind_result($blogId, $blogTitle, $blogContent, $date_posted, $username, $userId);
            
            while ($stmt->fetch()) {
                $blogpost = new Blogpost($blogId, $blogTitle, $blogContent, $date_posted, $userId);
                
                array_push($blogposts, $blogpost);
            }
            
            $stmt->close();    
        }
        
        return $blogposts;   
    }
    
    /**
     * Adds a blogpost
     * @param $title, $content, $userId
     * @return boolean
     */
    public function addBlogpost($title, $content, $userId)
    {
        if (empty($title)) {
            $title = "No title";
        }
        
        $sqlQuery = "INSERT INTO blogg (title, content, user_id) VALUES (?, ?, ?)";
        
        if ($stmt = $this->_dbHandler->PrepareStatement($sqlQuery)) {
            $stmt->bind_param("ssi", $title, $content, $userId);
            $stmt->execute();
            $stmt->close();
            
            return true;
        }else {
            return false;
        }    
    }
    
    /**
     * Edits a blogpost
     * @param $blogId, $title, $content
     * @return boolean
     */
    public function editBlogpost($blogId, $title, $content)
    {
        $sqlQuery = "UPDATE blogg SET blogg.title = ?, blogg.content = ? WHERE id = ?";
        
        if ($stmt = $this->_dbHandler->PrepareStatement($sqlQuery)) {
            $stmt->bind_param("ssi", $title, $content, $blogId);
            
            if ($stmt->execute()) {
                $stmt->close();
                return true;    
            }
        }
        return false;      
    }
    
    /**
     * Deletes a blogpost
     * @param $id
     * @return boolean
     */
    public function deleteBlogpost($id)
    {
        $sqlQuery = "DELETE FROM blogg WHERE id = ?";
        
        if ($stmt = $this->_dbHandler->PrepareStatement($sqlQuery)) {
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                $stmt->close();
                return true;    
            }
        }
        return false;      
    }
    
    /**
     * Returns a blogpost
     * @param $id
     * @return Blogpost
     */
    public function getBlogpostById($id)
    {
        $blogpost = null;
        
        $sqlQuery = "SELECT blogg.id, blogg.title, blogg.content, blogg.date, blogg.user_id
                        FROM blogg
                        INNER JOIN user 
                        ON user.id = blogg.user_id
                        WHERE blogg.id = ?
                    ";
                        
        if ($stmt = $this->_dbHandler->PrepareStatement($sqlQuery)) {
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->bind_result($id, $title, $content, $date_posted, $userId);
            
            if ($stmt->fetch()) {
                $blogpost = new Blogpost($id, $title, $content, $date_posted, $userId);
            }
            $stmt->close();
            
            return $blogpost;    
        }
    }    
}