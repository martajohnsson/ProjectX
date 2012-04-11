<?php
require_once dirname(__FILE__) . '/simpletest/autorun.php';
require_once dirname(__FILE__) . '/../model/SnippetHandler.php';
//require_once dirname(__FILE__) . '/../model/Snippet.php';

class SnippetHandlerTest extends unitTestcase
{

    private $_snippetHandler;
    private $_snippetId;

    private $_id;
    private $_author;
    private $_authorName;
    private $_code;
    private $_title;
    private $_desc;
    private $_language;
    private $_snippet;

    function __construct()
    {
        $this->_snippetHandler = new SnippetHandler();

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

    public function testIfSnippetCaBeInsertedInDatabase() {

        //$this->_snippetId = $this->_snippetHandler->createSnippet($snippet);
        //$this->assertTrue($this->_snippetId);
    }

    public function testIFSnippetCanBeDeleted() {
        //$result = $this->_snippetHandler->deleteSnippet($this->_snippetId);
        //$this->assertTrue($result);
    }

}