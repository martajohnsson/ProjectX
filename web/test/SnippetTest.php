<?php
require_once dirname(__FILE__) . '/simpletest/autorun.php';
//require_once dirname(__FILE__) . '/../model/Snippet.php';

class SnippetTest extends UnitTestCase
{

    private $_id;
    private $_author;
    private $_authorName;
    private $_code;
    private $_title;
    private $_desc;
    private $_language;
    private $_snippet;

    public function __construct()
    {
        $this->_author = '18';
        $this->_authorName = 'testName';
        $this->_code = 'testCode';
        $this->_title = 'testTitle';
        $this->_desc = 'testDesc';
        $this->_language = '1';
        $this->_id = 0;
        $date = "0000-00-00 00:00:00";
        $this->_snippet = new Snippet($this->_author, $this->_authorName, $this->_code, $this->_title, $this->_desc, $this->_language, $date, $date, 'php', $this->_id);
    }

    public function testGetID()
    {
        $this->assertEqual($this->_id, $this->_snippet->getID());
    }

    public function testGetAuthor()
    {
        $this->assertEqual($this->_author, $this->_snippet->getAuthorId());
    }

    public function testGetCode()
    {
        $this->assertEqual($this->_code, $this->_snippet->getCode());
    }

    public function testGetTitle()
    {
        $this->assertEqual($this->_title, $this->_snippet->getTitle());
    }

    public function testGetDesc()
    {
        $this->assertEqual($this->_desc, $this->_snippet->getDesc());
    }

    public function testGetLanguage()
    {
        $this->assertEqual($this->_language, $this->_snippet->getLanguageID());
    }

}