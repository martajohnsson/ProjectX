<?php
require_once dirname(__FILE__) . '/../model/Functions.php';
require_once dirname(__FILE__) . '/../model/recaptcha/recaptchalib.php';

class SnippetView
{
	private $_publicKey = '6LcjpsoSAAAAAAjPNFHJLc-_hSeDGa1F7m_bdnkz';
	
    /**
     * return html code for a single snippet
     * @param Snippet a snippet Object
     * @return String
     */
    public function singleView($snippet, $isOwner)
    {
        $sh = new Functions();
        
        $html = "
        <div class='row'>
        <div class='span12'>
        <div class='inner'>
        
            <h1 class='snippet-title'>" . $snippet->getTitle() . "</h1>
    		<div class='snippet-description'>
    			<p>" . $snippet->getDesc() . "</p>	
    		</div>
    		<div class='snippet-code' id='snippet-text'>
    			<code id='code' class='snippet-text'>" . $sh->geshiHighlight($snippet->getCode(), $snippet->getLanguage()) . "</code>
    		</div>
    
            <div id='hidden'>".$snippet->getCode()."</div>
            
    		<div class='snippet-author'>
    			<span>Posted by " . $snippet->getAuthor()."</span></br>";
                if ($isOwner) {
        		    $html .= " <a onclick=\"javascript: return confirm('Do you want to remove this snippet?')\" href='?page=removesnippet&snippet=" . $snippet->getID() . "'>Delete</a> 
        		    <a href='?page=updatesnippet&snippet=" . $snippet->getID() . "'>Update</a>";
        	    }
                if(AuthHandler::isLoggedIn()){    
                    $html .= '<br /><a id="report" href="#">Report this snippet!</a>';
                    $html .= '<div id="report-wrap"><form action="#" method="POST" name="reportsnippet">
                                <textarea placeholder="What is wrong with the snippet?" name="report-message"></textarea>
                                <input type="submit" name="send-report" value="Report!" />
                            </form></div>';
                }     
    		
    		$html .= "</span>
    	          </div>
        </div>
        </div>
        </div>";      
        
        return $html;
    }
    
    public function mailView()
    {
        $html = '<div class="mail">
            		<form id="formmail" action="" method ="POST">
            			<label>Your mail :</label>
            			<input type="text" name="mail" id="mailAddress" />
            			<input type="submit" id="sendByMail" name="sendByMail" value="send mail" />
            		</form>
                    <div id="response">
                    </div>
        	</div>';

        return $html;
    }    


    /**
     * Transform an array of snippets to html-code
     * @param array $aSnippets is an array of snippets
     * @return string
     */
    public function listView($snippets)
    {
        $html = '<h1>Snippets</h1>';

        foreach ($snippets as $snippet) {
            $html .= '
                <div" class="snippet-list-item">
                    <div class="snippet-title">
                        <p><a href="?page=listsnippets&snippet=' . $snippet->getID() . '">' . $snippet->getTitle() . '</a></p>
                    </div>
                    <div class="snippet-author">
                        <p>' . $snippet->getDesc() . '</p>
                    </div>
                </div>
            ';
        }
        
        return $html;
    }

    public function createSnippet($languages)
    {
    	
        $html = '
        <script type="text/javascript">
			var RecaptchaOptions = {
    		theme : "clean"
 		};
 		</script>
        
        <div class="inner">
        
        <h1 class="snippet-title">Add a new snippet</h1>
         
                <form  class="form-horizontal" action="" method="post">
                <div class="control-group">
                    <input class="input-xlarge" type="text" name="snippetTitle" placeholder="Title" value="' . $_SESSION['title'] . '" />
                </div>
                <div class="control-group">
                    <input class="input-xlarge" type="text" name="snippetDescription" placeholder="Description" value="' . $_SESSION['desc'] . '" />
                </div>
                
                <div class="control-group">
                    <select class="input-xlarge" name="snippetLanguage">
                        <option >Choose language</option>';
                            foreach ($languages as $language) {
                                    $html .= '
                                    <option value="' . $language->getLangId() . '">' . $language->getLanguage() . '</option>';
                                }
                                $html .= '
                    </select>
                </div>
                
                <div class="control-group">
                    <textarea class="input-xxlarge" rows="10" name="createSnippetCodeInput" maxlength="1500" placeholder="Your snippet">' . $_SESSION['code'] . '</textarea>
                </div>'
                    .recaptcha_get_html($this->_publicKey) .
                    '<input class="btn" type="submit" name="createSnippetSaveButton" id="createSnippetSaveButton" value="Create snippet" />
                </form>
       
            
        </div>    
        ';
        return $html;
    }

    public function updateSnippet($snippet)
    {
        $html = '<h1>Update the snippet "' . $snippet->getTitle() . '"</h1>
            <div id="createSnippetContainer">
                <form action="" method="post">
                    <input type="text" name="updateSnippetTitle" placeholder="Title" value="' . $snippet->getTitle() . '" />
                    <input type="text" name="updateSnippetDescription" placeholder="Description" value="' . $snippet->getDesc() . '"  />
                    <textarea name="updateSnippetCodeInput" maxlength="1500" placeholder="Your snippet">' . $snippet->getCode() . '</textarea>
                    <input type="submit" name="updateSnippetUpdateButton" id="updateSnippetUpdateButton" value="Update snippet" />
                </form>
            </div>';
            
        return $html;
    }
    

    /**
     * Creates HTML for voting with jquery ajax
     * @param int $snippet_id, array $rating with total, likes and dislikes
     * @return string
     */
    public function rateSnippet($snippet_id, $user_id, $rating) {
        $html = '<div id="rating">
                    <button name="like" type="button" id="like"><img src="content/image/like.png" title="Like!" /></button>
                    <button name="dislike" type="button" id="dislike"><img src="content/image/dislike.png" title="Dislike!" /></button>
                
                    <div id="ratingbars">
                        <div id="likes" style="width: ' . ($rating['total'] != 0 ? floor($rating['likes'] / $rating['total'] * 100) : 0) . '%"></div>
                        <div id="dislikes" style="width: ' . ($rating['total'] != 0 ? floor($rating['dislikes'] / $rating['total'] * 100) : 0) . '%"></div>
                    </div>
                    <p id="test">' . $rating['likes'] . ' likes, ' . $rating['dislikes'] . ' dislikes</p>
                    <div id="message"></div>
                </div>';
        $html .= "<script>
                    var likes = " . $rating['likes'] . ";
                    var dislikes = " . $rating['dislikes'] . ";
                    var total = " . $rating['total'] . ";

                    $('#like').click(function(){
                         $.ajax({ type: 'POST',
                            url: 'model/RateSnippet.php',
                            data: {
                                'snippet_id': " . $snippet_id . ",
                                'user_id': " . $user_id .",
                                'rating': 1,
                                'api': '" . AuthHandler::getApiKey() . "'
                            },
                            dataType: 'html',
                            success: function(data) {
                                if (data === '1') {
                                    $('#test').html((likes + 1) + ' likes, ' + dislikes + ' dislikes');
                                    $('#likes').css('width', ((total + 1) != 0 ? Math.round(((likes + 1) / (total + 1)) * 100) : 0) + '%');
                                    $('#dislikes').css('width', ((total + 1) != 0 ? Math.round(((dislikes) / (total + 1)) * 100) : 0) + '%');
                                    $('#message').html('<p>Thank you for voting!</p>');
                                } else if (data === '0') {
                                    $('#message').html('<p>You have already voted on this snippet</p>');
                                }
                            },
                            error: function() {
                                alert('wat');
                            }
                        });
                    });
                    $('#dislike').click(function(){
                        $.ajax({ type: 'POST',
                            url: 'model/RateSnippet.php',
                            data: {
                                'snippet_id': " . $snippet_id . ",
                                'user_id': " . $user_id .",
                                'rating': 0,
                                'api': '" . AuthHandler::getApiKey() . "'
                            },
                            dataType: 'html',
                            success: function(data) {
                                if (data === '1') {
                                    $('#test').html(likes + ' likes, ' + (dislikes + 1) + ' dislikes');
                                    $('#dislikes').css('width', ((total + 1) != 0 ? Math.round(((dislikes + 1) / (total + 1)) * 100) : 0) + '%');
                                    $('#likes').css('width', ((total + 1) != 0 ? Math.round(((likes) / (total + 1)) * 100) : 0) + '%');
                                    $('#message').html('<p>Thank you for voting!</p>');
                                } else if (data === '0') {
                                    $('#message').html('<p>You have already voted on this snippet</p>');
                                }
                            }
                        });
                    });
                </script>";
                
        return $html;
    }
    
    public function triedToCreateSnippet()
    {
        if (isset($_POST['createSnippetSaveButton'])) {
            return true;
        } else {
            return false;
        }
    }

    public function getCreateSnippetName()
    {
        $snippetName = $_POST['createSnippetNameInput'];
        if ($snippetName == null) {
            return null;
        } else {
            return $snippetName;
        }
        return false;
    }

    public function getSnippetTitle()
    {
        $snippetName = $_POST['snippetTitle'];
        if ($snippetName == null) {
            return null;
        } else {
            return $snippetName;
        }
        return false;
    }

    public function getSnippetDescription()
    {
        $snippetName = $_POST['snippetDescription'];
        if ($snippetName == null) {
            return null;
        } else {
            return $snippetName;
        }
        return false;
    }

    public function getSnippetLanguage()
    {
        $snippetName = $_POST['snippetLanguage'];
        if ($snippetName == null) {
            return null;
        } else {
            return $snippetName;
        }
        return false;
    }

    public function getCreateSnippetCode()
    {
        $snippetCode = $_POST['createSnippetCodeInput'];
        if ($snippetCode == null) {
            return null;
        } else {
            return $snippetCode;
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

    public function triedToUpdateSnippet()
    {
        if (isset($_POST['updateSnippetUpdateButton'])) {
            return true;
        } else {
            return false;
        }
    }

    public function getSnippetIDLink()
    {
        $snippetID = $_GET['chsnippet'];
        if ($snippetID == null) {
            return null;
        } else {
            return $snippetID;
        }
        return false;
    }

    public function getUpdateSnippetName()
    {
        $snippetName = $_POST['updateSnippetTitle'];
        if ($snippetName == null) {
            return null;
        } else {
            return $snippetName;
        }
    }

    public function getUpdateSnippetCode()
    {
        $snippetCode = $_POST['updateSnippetCodeInput'];
        if ($snippetCode == null) {
            return null;
        } else {
            return $snippetCode;
        }
        return false;
    }
    
    public function getUpdateSnippetDesc() {
        $snippetDesc = $_POST['updateSnippetDescription'];
        if ($snippetDesc == null) {
            return null;
        } else {
            return $snippetDesc;
        }
        return false;
    }
    
    public function getUpdateSnippetID()
    {
        $snippetID = $_POST['updateSnippetID'];
        if ($snippetID == null) {
            return null;
        } else {
            return $snippetID;
        }
    }

    public function triedToDeleteSnippet()
    {
        if (isset($_POST['deleteSnippetButton'])) {
            return true;
        } else {
            return false;
        }
    }

    public function getSnippetID()
    {
        $snippetID = $_POST['snippetID'];
        if ($snippetID == null) {
            return null;
        } else {
            return $snippetID;
        }
        return false;
    }

    public function triedToGotoCreateView()
    {
        if (isset($_POST['gotoCreateSnippetViewButton'])) {
            return true;
        } else {
            return false;
        }
    }
    
    public function sendByMail()
    {
        if (isset($_POST['sendByMail'])) {
            return true;
        } else {
            return false;
        }
    }      
    
    public function wantsToSendByMail()
    {
        if (isset($_POST['sendSnippetByMail'])) {
            return true;
        } else {
            return false;
        }
    }  

    public function getReportMessage() {
        if (isset($_POST['report-message'])) {
            return $_POST['report-message'];
        } else {
            return false;
        }
    }

}