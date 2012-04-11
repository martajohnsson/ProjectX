<?php
require_once dirname(__FILE__) . '/../model/Log.php';
require_once dirname(__FILE__) . '/SnippetController.php';
require_once dirname(__FILE__) . '/SearchController.php';
require_once dirname(__FILE__) . '/HeaderController.php';
require_once dirname(__FILE__) . '/AuthController.php';
require_once dirname(__FILE__) . '/ProfileController.php';
require_once dirname(__FILE__) . '/BlogController.php';
require_once dirname(__FILE__) . '/DownloadController.php';

class MasterController
{
    private $_snippetController;
    private $_searchController;
    private $_headerController;
    private $_authController;
    private $_profileController;
    private $_blogController;
	private $_downloadController;
    private $_html;

    public function __construct()
    {
        session_start();
        $this->_headerController = new HeaderController();
        $this->_html = '';
    }

    public function doControll()
    {
        if (isset($_GET['page'])) {
            if ($_GET['page'] == 'listsnippets') {
                $this->_snippetController = new SnippetController();
                $this->_html .= $this->_snippetController->doControll('list');
            } else if ($_GET['page'] == 'addsnippet') {
                $this->_snippetController = new SnippetController();
                $this->_html .= $this->_snippetController->doControll('add');
            } else if ($_GET['page'] == 'updatesnippet') {
                $this->_snippetController = new SnippetController();
                $this->_html .= $this->_snippetController->doControll('update');
            } else if ($_GET['page'] == 'removesnippet') {
                $this->_snippetController = new SnippetController();
                $this->_html .= $this->_snippetController->doControll('remove');
            } else if ($_GET['page'] == 'advsearch') {
                $this->_searchController = new SearchController();
                $this->_html .= $this->_searchController->doControll();
            } else if ($_GET['page'] == 'login') {
                $this->_authController = new AuthController();
                $this->_authController->checkAuthToken();
            } else if ($_GET['page'] == 'profile') {
                $this->_profileController = new ProfileController();
                $this->_html .= $this->_profileController->doControll();
            } else if ($_GET['page'] == 'listblogposts') {
                $this->_blogController = new BlogController();
                $this->_html .= $this->_blogController->doControll('list');
            }else if ($_GET['page'] == 'addblogpost') {
                $this->_blogController = new BlogController();
                $this->_html .= $this->_blogController->doControll('add');
            }else if ($_GET['page'] == 'editblogpost') {
                $this->_blogController = new BlogController();
                $this->_html .= $this->_blogController->doControll('edit');            
            }else if ($_GET['page'] == 'removeblogpost') {
                $this->_blogController = new BlogController();
                $this->_html .= $this->_blogController->doControll('remove');
			} else if ($_GET['page'] == 'downloads') {
                $this->_downloadController = new DownloadController();
                $this->_html .= $this->_downloadController->doControll();			     
            } 
        } else {
            $this->_searchController = new SearchController();
            $this->_html .= $this->_searchController->doControll();
        }
        if (!empty($_GET['logout']) && $_GET['logout'] == 'true') {
            AuthHandler::logout();
            header("Location: " . $_SERVER['PHP_SELF']);
        }

        return $this->_html;
	}
    public function doHeader()
    {
        $html = $this->_headerController->doControll();
        return $html;
    }

}
