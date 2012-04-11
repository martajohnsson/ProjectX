<?php
require_once dirname(__FILE__) . '/SnippetHandler.php';
require_once dirname(__FILE__) . '/UserHandler.php';

class Snippet
{
    private $_id;
    private $_authorID;
    private $_author;
    private $_code;
    private $_title;
    private $_desc;
    private $_languageID;
    private $_created;
    private $_updated;
    private $_language;

    public function __construct($authorID, $author, $code, $title, $desc, $languageID, $created, $updated, $language, $id = null)
    {
        if ($id != null) {
            $this->_id = $id;
        }
        $this->_authorID = $authorID;
        $this->_author = $author;
        $this->_code = $code;
        $this->_title = $title;
        $this->_desc = $desc;
        $this->_languageID = $languageID;
        $this->_created = $created;
        $this->_updated = $updated;
        $this->_language = $language;

        //Get name of language and author
        //$this->getLangName();
        //$this->getAuthorName();
    }

    /**
     * Get name of Author from UserHandler
     */
    private function getAuthorName()
    {
        if (isset($this->_authorID)) {
            $uh = new UserHandler();
            $this->_author = $uh->getUsernameByID($this->_authorID);
        }
    }

    /**
     * Gets the name of a language by ID from SnippetHandler
     */
    private function getLangName()
    {
        if (isset($this->_languageID)) {
            $sh = new SnippetHandler();

            $lang = $sh->getLanguageByID($this->_languageID);
            $this->_language = $lang->getLanguage();
        }
    }

    /**
     * @return int ID of the snippet
     */
    public function getID()
    {
        return $this->_id;
    }

    /**
     * @return int The author id of the snippet
     */
    public function getAuthorId()
    {
        return $this->_authorID;
    }

    /**
     * @return int The author id of the snippet
     */
    public function getAuthor()
    {
        return $this->_author;
    }

    /**
     * @return String The code snippet
     */
    public function getCode()
    {
        return $this->_code;
    }

    /**
     * @return String title of the snippet
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * @return String description of the snippet
     */
    public function getDesc()
    {
        return $this->_desc;
    }

    /**
     * @return String language of the snippet
     */
    public function getLanguage()
    {
        return $this->_language;
    }

    /**
     * @return int id of language
     */
    public function getLanguageID()
    {
        return $this->_languageID;

    }

    public function getCreatedDate()
    {
        return $this->_created;
    }

    public function getUpdatedDate()
    {
        return $this->_updated;
    }
    
    public function setTitle($title)
    {
        $this->_title = $title;
    }
    
    public function setCode($code)
    {
        $this->_code = $code;
    }
    
    public function setDesc($desc)
    {
        $this->_desc = $desc;
    }
    
    public function setUpdatedDate($updated)
    {
        $this->_updated = $updated;
    }

}