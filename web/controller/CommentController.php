<?php

require_once dirname(__file__) . '/../model/AuthHandler.php';
require_once dirname(__FILE__) . '/../model/recaptcha/recaptchalib.php';

class CommentController
{
    private $_dbHandler = NULL;
	private $_commentView;
	private $_privateKey;
	private $_recaptchaAnswer;

    public function __construct()
    {
        $this->_dbHandler = new DbHandler();
		$this->_commentView = new CommentView();
		$this->_privateKey = '6LcjpsoSAAAAAH7uTWckrCZL87jizsHpUQuP-dRy';
    }

    public function doControll()
    {
        $html = "";
        $commentHandler = new CommentHandler($this->_dbHandler);
		$_SESSION['comment'] = "";

        if (AuthHandler::isLoggedIn()) {
            //add Comment
            if ($this->_commentView->triedToSubmitComment()) {
            	$this->_recaptchaAnswer = recaptcha_check_answer($this->_privateKey, $_SERVER["REMOTE_ADDR"], $this->_commentView->getRecaptchaChallenge(), $this->_commentView->getRecaptchaResponse());
                if($this->_recaptchaAnswer->is_valid) {
	                $text = $this->_commentView->getCommentText();
	                $author = AuthHandler::getUser()->getId();
	                $id = $this->_commentView->whichSnippetToComment();
	                
                    $result = $commentHandler->addComment($id, $author, $text);
				} else {
					$_SESSION['comment'] = $this->_commentView->getCommentText();
					$html .= "<p>The reCAPTCHA answer given is not correct</p>";
				}
            }
            //Delete Comments
            if ($this->_commentView->triesToRemoveComment()) {
                $comment = $commentHandler->getCommentByID($this->_commentView->whichCommentToDelete());
                if ($comment != null) {
                    if ($comment->getUserId() == AuthHandler::getUser()->getId()) {
                        $commentHandler->deleteComment($comment);
                    }
                }
            }

            //Edit Comment
            if ($this->_commentView->triesToUpdateComment()) {
                $comment = $commentHandler->getCommentByID($this->_commentView->whichCommentToEdit());
                if ($comment != null) {
                    if ($comment->getUserId() == AuthHandler::getUser()->getId()) {
                        $comment->setCommentText($this->_commentView->getCommentText());
                        $commentHandler->updateComment($comment);
                    }
                }
            }
            //Get content of comment and present it in the form
            if ($this->_commentView->triesToEditComment()) {
                $html .= $this->_commentView->editComment($commentHandler->getCommentByID($this->_commentView->WhichCommentToEdit()));
            } else {
                $html .= $this->_commentView->doCommentForm();
            }


            $id = AuthHandler::getUser()->getId();
            $comments = $commentHandler->getComments($this->_commentView->whichSnippetToComment());
            $html .= $this->_commentView->showAllCommentsForSnippet($comments, $id);
            
        } else {
            //Show comments for a snippet
            $comments = $commentHandler->getComments($this->_commentView->whichSnippetToComment());
            $html .= $this->_commentView->showAllCommentsForSnippet($comments);
        }
        return $html;
    }

}
