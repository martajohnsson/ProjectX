<?php

require_once dirname(__FILE__) . '/../model/SnippetHandler.php';
require_once dirname(__FILE__) . '/../view/SnippetView.php';
require_once dirname(__FILE__) . '/CommentController.php';
require_once dirname(__FILE__) . '/../view/CommentView.php';
require_once dirname(__FILE__) . '/../model/AuthHandler.php';
require_once dirname(__FILE__) . '/../model/recaptcha/recaptchalib.php';

class SnippetController
{
    private $_dbHandler;
    private $_snippetHandler;
    private $_snippetView;
    private $_html;
    private $_commentController;
    private $_pagingHandler;
	private $_privateKey;
	private $_recaptchaAnswer;

    public function __construct()
    {
        $this->_snippetHandler = new SnippetHandler();
        $this->_snippetView = new SnippetView();
        $this->_html = '';
		$this->_privateKey = '6LcjpsoSAAAAAH7uTWckrCZL87jizsHpUQuP-dRy';
		$this->_commentController = new CommentController();
    }

    public function doControll($page)
    {
        if ($page == 'list') {
            // Show single snippet
            if (isset($_GET['snippet'])) {
                //Check if snippet exist
                if($snippet = $this->_snippetHandler->getSnippetByID($_GET['snippet'])) {
                    //Check if user is admin or owner of snippet
                    $isOwner = AuthHandler::isOwnerByID($snippet->getAuthorId());
                    $this->_html .= $this->_snippetView->singleView($snippet, $isOwner);
                    if(AuthHandler::isLoggedIn()) {
                        $this->_html .= $this->_snippetView->rateSnippet($_GET['snippet'], AuthHandler::getUser()->getId(), $this->_snippetHandler->getSnippetRating($_GET['snippet']));  
                    }
                    $this->_html .= $this->_commentController->doControll();
                } else {
                    return false;
                }
            }
            if(AuthHandler::isLoggedIn()) {
                if(isset($_POST['send-report'])) {
                    $userId = -1;
                    $message = $this->_snippetView->getReportMessage();
                    $snippetId = $_GET['snippet'];
                    if(AuthHandler::isLoggedIn()) {
                        $userId = AuthHandler::getUser()->getId();
                    } 
                    $this->_snippetHandler->reportSnippet($snippetId, $userId, $message);   
                }
            }
        } else if ($page == 'add') {
            if (AuthHandler::isLoggedIn()) {
                $this->_html = null;
                $_SESSION['title'] = "";
                $_SESSION['desc'] = "";
                $_SESSION['code'] = "";
                $this->_html .= $this->_snippetView->createSnippet($this->_snippetHandler->getLanguages());
    
                if ($this->_snippetView->triedToCreateSnippet()) {
                	$this->_recaptchaAnswer = recaptcha_check_answer ($this->_privateKey, $_SERVER["REMOTE_ADDR"], $this->_snippetView->getRecaptchaChallenge(), $this->_snippetView->getRecaptchaResponse());
                	if($this->_recaptchaAnswer->is_valid) {
	                    $authID = AuthHandler::getUser()->getID();
	                    $authName = AuthHandler::getUser()->getName();
	                    $snippet = new Snippet($authID, $authName, $this->_snippetView->getCreateSnippetCode(), $this->_snippetView->getSnippetTitle(), $this->_snippetView->getSnippetDescription(), $this->_snippetView->getSnippetLanguage(), $this->_snippetHandler->SetDate(), $this->_snippetHandler->SetDate(), "Ett språk");
	                    $id = $this->_snippetHandler->createSnippet($snippet);
	                    if($id != false){
	                        header("Location: " . $_SERVER['PHP_SELF'] . "?page=listsnippets&snippet=" . $id);
	                        exit();
	                    }
	                    $this->_html .= "<p>Error, your snippet was not created. Please try again! ". $id ."</p>";
					} else {
					    $_SESSION['title'] = $this->_snippetView->getSnippetTitle();
                        $_SESSION['desc'] = $this->_snippetView->getSnippetDescription();
                        $_SESSION['code'] = $this->_snippetView->getCreateSnippetCode();
                        $this->_html = $this->_snippetView->createSnippet($this->_snippetHandler->getLanguages());
						$this->_html .= "<p>The reCAPTCHA answer given is not correct</p>";
					}
                }
            } else {
                $this->_html = "<p>You must sign in to add a snippet.</p>";
            }
        } else if ($page == 'update') {
            $this->_html = null;
            $snippet = $this->_snippetHandler->getSnippetByID($_GET['snippet']);
            $this->_html .= $this->_snippetView->updateSnippet($snippet);
            
            if ($this->_snippetView->triedToUpdateSnippet()) {
                $snippet->setTitle($this->_snippetView->getUpdateSnippetName());
                $snippet->setCode($this->_snippetView->getUpdateSnippetCode());
                $snippet->setDesc($this->_snippetView->getUpdateSnippetDesc());
                $snippet->setUpdatedDate($this->_snippetHandler->SetDate());
                
                $this->_snippetHandler->updateSnippet($snippet);
                header("Location: " . $_SERVER['PHP_SELF'] . "?page=listsnippets&snippet=" . $_GET['snippet']);
                exit();
            }
        } else if ($page == 'remove') {
            $this->_snippetHandler->deleteSnippet($this->_snippetHandler->getSnippetByID($_GET['snippet']));
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
        
        if($this->_snippetView->wantsToSendByMail()){
            $this->_html .= $this->_snippetView->mailView();
        }

        return $this->_html;
    }

}
