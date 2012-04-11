<?php
require_once dirname(__FILE__) . '/../model/Functions.php';
require_once dirname(__FILE__) . '/simpletest/autorun.php';

/**
* Tests for Functions.php 
*/
class TestOfSnippetValidation extends UnitTestCase {

    private $_functions;

    public function __construct() {
        $this->_functions = new Functions();
    }

    function testvalMaxLenght() {
        $this->assertFalse($this->_functions->valMaxLenght("somesting", 6));
        $this->assertTrue($this->_functions->valMaxLenght("somesting", 11));
    }

    function testValUrl() {
        $this->assertFalse($this->_functions->valUrl("f.*.com"));
        $this->assertTrue($this->_functions->valUrl("http://www.url.com"));
    }

    function testValEmail() {
        $this->assertFalse($this->_functions->valEmail("mail@@mail.com"));
        $this->assertTrue($this->_functions->valUrl("mail@mail.com"));
    }
}
