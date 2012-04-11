<?php

require_once dirname(__FILE__) . '/UserHandler.php';

class BlogPost
{
    private $_blogId;
    private $_title;
    private $_content;
    private $_date_posted;
    private $_userId;
    private $_author;
    private $_read_more;
    
    public function __construct($blogId, $title, $content, $date_posted, $userId)
    {
        $this->_blogId = $blogId;
        $this->_title = $title;
        $this->_content = $content;
        $this->_userId = $userId;
        
        //Alter the date to current form
        $this->_date_posted = substr($date_posted, 0, -9);
        
        //Get the name of the author
        $this->getAuthorName();
        
        //Extracts text for readmore
        $this->_read_more = substr($this->_content, 0, 500);
    }
    
    /**
    * Get name of Author from UserHandler
    */
    private function getAuthorName()
    {
        if (isset($this->_userId)) {
            $uh = new UserHandler();
            $this->_author = $uh->getUsernameByID($this->_userId);
        }
    }
    
    /**
     * @return int ID of the blogpost
     */
    public function getId()
    {
        return $this->_blogId;
    }
    
    /**
     * @return string Title of the blogpost
     */
    public function getTitle()
    {
        return $this->_title;    
    }        
    
    /**
     * @return string Content of the blogpost
     */
    public function getContent()
    {
        return $this->_content;
    }
    
    /**
     * @return string Date the blogpost was added
     */
    public function getDate()
    { 
        return $this->_date_posted;
    }
    
    /**
    * @return string The name of the author of the blogpost
    */
    public function getAuthor()
    {
        return $this->_author;
    }
    
    /**
    * @return string Read more content
    */
    public function getReadMoreContent()
    {
        return $this->_read_more;    
    }
}