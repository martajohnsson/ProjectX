<?php
require_once dirname(__FILE__) . '/../model/recaptcha/recaptchalib.php';

class CommentView
{
	private $_publicKey = '6LcjpsoSAAAAAAjPNFHJLc-_hSeDGa1F7m_bdnkz';
    /**
     * CommentView::doCommentForm()
     * html form for adding a new comment
     * @return String
     */
    public function doCommentForm()
    {
        $form = "
        	<script type='text/javascript'>
			var RecaptchaOptions = {
    		theme : 'clean'
 			};
 			</script>
        	<div id='comment'>
                    <h3>Post a comment</h3>
                    <form action='#' method='POST'>
                        <textarea name='commentText' maxlength='1500' placeholder='Your comment'>" . $_SESSION['comment'] . "</textarea>"
                        . recaptcha_get_html($this->_publicKey) .                     
                        "<input type='submit' name='submitComment' value='Post comment'/>
                    </form>
                </div>";
        return $form;
    }

    /**
     * CommentView::showAllCommentsForSnippet()
     * html that shows all comments taht was added for a snippet
     * @return String
     * @param array of the Comment object
     */
    public function showAllCommentsForSnippet($comments, $userId = null)
    {
        $message = "";
        if (!empty($comments)) {
            for ($i = 0; $i < count($comments); $i++) {
                $message .= "<div class='comments'>";
                $message .= "<p class='snippet-author'>" . $comments[$i]->getUsername() . "</p>";
                $message .= "<p class='date'>" . $comments[$i]->getCommentDate() . "</p>";
                $message .= "<p class='text'>" . $comments[$i]->getCommentText() . "</p>";
                if($comments[$i]->getUserId() == $userId && $userId != null) {
                    $message .= "<a onclick=\"javascript: return confirm('Do you want to remove this comment?')\" href='index.php?page=listsnippets&snippet=" . $comments[$i]->getSnippetId() . "&deleteComment=" . $comments[$i]->getCommentId() . "'>Delete</a> ";
                    $message .= "<a onclick=\"javascript: return confirm('Do you want to edit this comment?')\" href='index.php?page=listsnippets&snippet=" . $comments[$i]->getSnippetId() . "&editComment=" . $comments[$i]->getCommentId() . "'>Update</a>";
                }
                
                $message .= "</div>";
            }
        } else {
            $message .= "<p>There are no comments for this snippet.</p>";
        }

        return $message;
    }

    /**
     * CommentView::editComment()
     * html taht allows to edit a comment text
     * @param Comment object
     * @return String
     */
    public function editComment($comment)
    {
        if ($comment) {
            $form = "<div id='comment'>
                        <h3>Update your comment</h3>
                        <form action='#' method='POST'>
                            <textarea name='commentText' maxlength='1500' placeholder='Your comment'>" . $comment->getCommentText() . "</textarea>
                            <input type='submit' name='updateComment' value='Update comment'/>
                        </form>
                    </div>";
        } else {
            $form = "<p>The comment you tried to edit does not exist.</p>";
        }
        return $form;
    }

    /**
     * CommentView::triedToSubmitComment()
     *
     * @return true if user is trying to add a new comment
     */
    public function triedToSubmitComment()
    {
        if (isset($_POST['submitComment'])) {
            return true;
        }
        return false;
    }

    /**
     * CommentView::getCommentText()
     *
     * @return String that is the text of the comment
     */
    public function getCommentText()
    {
        if (isset($_POST['commentText'])) {
            return trim($_POST['commentText']);
        }
        return false;
    }

    /**
     * CommentView::getAuthorId()
     *
     * @return int, id of the User
     */
    public function getAuthorId()
    {
        if (isset($_POST['commentAuthor'])) {
            return trim($_POST['commentAuthor']);
        }
        return false;
    }
    
  	public function getRecaptchaChallenge()
    {
        if (isset($_POST["recaptcha_challenge_field"])) {
            return $_POST["recaptcha_challenge_field"];
        }
        return false;
    }
	
  	public function getRecaptchaResponse()
    {
        if (isset($_POST["recaptcha_response_field"])) {
            return $_POST["recaptcha_response_field"];
        }
        return false;
    }

    /**
     * CommentView::triesToRemoveComment()
     *
     * @return true if user is trying to delete a comment
     */
    public function triesToRemoveComment()
    {
        if (isset($_GET["deleteComment"])) {
            return true;
        }
        return false;
    }

    /**
     * CommentView::whichCommentToDelete()
     *
     * @return int, id of the comment that is going to be deleted
     */
    public function whichCommentToDelete()
    {
        if (isset($_GET["deleteComment"])) {
            return urldecode($_GET["deleteComment"]);
        }
        return false;
    }

    /**
     * CommentView::triesToEditComment()
     *
     * @return true if the user is trying to edit a comment
     */
    public function triesToEditComment()
    {
        if (isset($_GET["editComment"])) {
            return true;
        }
        return false;
    }

    /**
     * CommentView::whichCommentToEdit()
     *
     * @return int, id of the comment that user wants to edit
     */
    public function whichCommentToEdit()
    {
        if (isset($_GET["editComment"])) {
            return urldecode($_GET["editComment"]);
        }
        return false;
    }

    /**
     * CommentView::triesToUpdateComment()
     *
     * @return true if user wants to update comment
     */
    public function triesToUpdateComment()
    {
        if (isset($_POST["updateComment"])) {
            return true;
        }
        return false;
    }

    /**
     * CommentView::whichSnippetToComment()
     *
     * @return int, id of the snippet for which user wants to add a new comment
     */
    public function whichSnippetToComment()
    {
        if (isset($_GET["snippet"])) {
            return urldecode($_GET["snippet"]);
        }
        return false;
    }

}