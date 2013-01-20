<?php
class ProfileView
{
    public function profile($avatar, $name, $data, $user)
    {
        $html = $this->doProfileMenu($data['isAdmin'], $data['isOwner'], $data['email']);
        $html .= "
                
                <div class='row'>   
                
                    <div class='span6'>
                        <div class='inner profile' id='profile-stats'>
                            <div class='page-header'>
                              <h2>Hi there <small>" . $name . "</small></h2>
                            </div>
                            <img  class='img-polaroid' src='" . $avatar . "' alt='User' /> 
                            <br /><br />
                            <p><span class='text-info'>Created snippets: </span>" . count($data['snippets']) . "</p>
                            <p><span class='text-info'>Commented snippets: </span>" . count($data['comments']) . "</p>
                            <p><span class='text-info'>Total likes: </span>" . count($data['likes']) . "</p>
                            <p><span class='text-info'>Total dislikes: </span>" . count($data['dislikes']) . "</p>
                            <p><span class='text-info'>User role: </span>" . $user->getRoleName() . "</p>
                            <p><span class='text-info'>Api-key: </span>" . $user->getApiKey() . "</p>
                            <p><span class='text-info'>UserID: </span>" . $user->getId() . "</p>
                        </div>
                    </div>
                    
                    <div class='span6'>
                        <div class='inner'>
                            <div class='tab-content' id='user-activity'>";
                                $html .= $data['content'];
                                $html .= "
                            </div>
                        </div>
                    </div>
                </div>";
        return $html;
    }
    public function doProfileMenu($isAdmin, $isOwner, $email)
    {
        $html = "
                <ul class='nav nav-tabs'>
                    <li><a href='".$_SERVER['PHP_SELF']."?page=profile&amp;p=created'>Created snippets</a></li>
                    <li><a href='".$_SERVER['PHP_SELF']."?page=profile&amp;p=commented'>Commented snippets</a></li>
                    <li><a href='".$_SERVER['PHP_SELF']."?page=profile&amp;p=liked'>Liked snippets</a></li>
    	            <li><a href='".$_SERVER['PHP_SELF']."?page=profile&amp;p=disliked'>Disliked snippets</a></li>";
                    if($isAdmin) {
                        $html .= "
                    <li><a href='" . $_SERVER['PHP_SELF']."?page=profile&amp;p=reported'>Reported snippets</a></li>";
                    }
                    if($isOwner || $isAdmin) {
                        $html .= "
                    <li><a href='" . $_SERVER['PHP_SELF']."?page=profile&amp;p=settings'>Settings</a></li>";
                    }
                    $html .= "
                </ul>";  
        return $html;
    }
    public function likedSnippets($likedSnippets)
    {
        $html = "<div class='page-header'>
                    <h2>Liked snippets</h2>
                </div>
                <ul>";
        if($likedSnippets) 
        {
            foreach ($likedSnippets as $snippet) 
            {
                $html .= "<li><a href='" . $_SERVER['PHP_SELF'] . "?page=listsnippets&amp;snippet=" . $snippet->getID() . "'>" . $snippet->getTitle() . "</a></li></ul>";
            }
        } 
        else {
            $html .= "<p>You have not liked any snippets.</p>";
        }
        return $html;
    }

    public function dislikedSnippets($dislikedSnippets){
        $html = 
        "<div class='page-header'>
            <h2>Disliked snippets</h2>
        </div>
        <ul>";
        if($dislikedSnippets) {
            foreach ($dislikedSnippets as $snippet) {
                $html .= "<li><a href='" . $_SERVER['PHP_SELF'] . "?page=listsnippets&amp;snippet=" . $snippet->getID() . "'>" . $snippet->getTitle() . "</a></li></ul>";
            }
        } else {
            $html .= "<p>You have not disliked any snippets.</p>";
        }
        return $html;
    }

    public function createdSnippets($createdSnippets){
        $html = "<div class='page-header'>
                    <h2>Created snippets</h2>
                </div>
                <ul>";
        if($createdSnippets) {
            foreach ($createdSnippets as $snippet) {
                $html .= "<li><a href='" . $_SERVER['PHP_SELF'] . "?page=listsnippets&amp;snippet=" . $snippet->getID() . "'>" . $snippet->getTitle() . "</a> - (" . $snippet->getLanguage() . ")</li></ul>";
            }
        }else {
            $html .= '<p>You have not created any snippets.</p>';
        }
        return $html;
    }

    public function commentedSnippets($commentedSnippets){
        $html = "<div class='page-header'>
                    <h2>Commented snippets</h2>
                </div>
                <ul>";
        if($commentedSnippets) {
            foreach ($commentedSnippets as $snippet) {
                $html .= "<li><a href='" . $_SERVER['PHP_SELF'] . "?page=listsnippets&snippet=" . $snippet->getID() . "'>" . $snippet->getTitle() . "</a></li></ul>";
            }
        } else {
            $html .= "<p>You have not commented on any snippets.</p>";
        }
        return $html;
    }
    
    public function settings($apiKey, $roles, $currentRole, $userInfo){
        $username = $this->getUser();
        $html = '<div class="page-header">
                    <h2>Settings</h2>
                </div>';
        $html .= "<div id='setting-wrapper'>";
        $html .= "<h4>This is your api-key <img class='info' data-info='Use the api-key to verify yourself in the desktop app' src='content/image/info.png' alt='info'/></h4>";
        $html .= '<span>' . $apiKey . ' - </span>';
        $html .= "<a href='" . $_SERVER['PHP_SELF'] . "?page=profile&amp;username=" . $username . "&amp;p=settings&amp;api_key=generate'>Generate new</a>";
        $html .= '<h4>This is your user role - change it if you want..</h4>';
        if($roles != null) {
            $html .= "<p><form action='#' method='POST' >
                        <select class='input-medium' name='role'>";
                            foreach ($roles as $k => $value) {
                                if($k == $currentRole) {
                                    $html .= "<option selected='selected' value='" . $k . "'>" . $value . "</option>";
                                } else {
                                    $html .= "<option value='" . $k . "'>" . $value . "</option>";
                                }
                            }
            $html .= "</select></br>
                        <input class='btn' type='submit' value='save changes' name='changerole' />
                    </form></p>";
        }
        /*$html .= '<h4>Delete email addresses connected to your account <img class="info" data-info="You cant delete the email address you are logged on to" src="content/image/info.png" alt="info"/>';
            if($addresses) {
                $html .= '<ul>';
                foreach ($addresses as $value) {
                    $html .= '<li>';
                    $html .= $value['provider'];
                    $html .= '</li>';
                }
                $html .= '</ul>';
            }*/
        $html .= '<h4>Delete your account <img class="info" data-info="your snippets and comments will not be deleted" src="content/image/info.png" alt="info"/>';
        $html .= '<br>'.$userInfo[0]['email'].'<br>';
        $html .= '<a href="?page=profile&p=settings&delete='.$userInfo[0]['user_id'].'"> Delete </a>';
        $html .= '</div>';

        return $html;
    }

    public function reportedSnippets($reports) {
        $html = '<div class="page-header">
                    <h2>Reported snippets</h2>
                </div>';
                
        if($reports){
            foreach ($reports as $report) {
                $html .= "<div class='reported-snippet'>";
                $html .= "<div class='reported-delete'>
                            <a href='" . $_SERVER['PHP_SELF'] . "?page=profile&p=reported&id=" . $report['id'] . "' >
                                <img src='content/image/del.png' />
                            </a>
                        </div>";
    
                $html .="<h4>".$report['username']." have reported a snippet</h4>";
    
                $html .="<div class='reported-gravatar'>
                            <img src='".$report['gravatar']."' alt='gravatar' />
                        </div>";
    
                $html .="<div class='reported-message'>
                            <p>".$report['message']."</p>
                        </div>";
    
                $html .="<div class='clear'></div>";
                $html .="<div class='reported-link'> 
                            <a href='" . $_SERVER['PHP_SELF'] . "?page=listsnippets&snippet=" . $report['snippetid'] . "' >
                                <img src='content/image/go.png' />
                            </a>
                        </div>";
                $html .="<div class='clear'></div>";
                $html .= "</div>";
            }    
        }else{
            $html .= '<p>There is no reported snippets.</p>';
        }
        return $html;
    }

    public function isUpdateProfile()
    {
        if (isset($_POST['update'])) {
            return true;
        } else {
            return false;
        }
    }
    
    public function getPage()
    {
        if(isset($_GET['p'])) {
            return $_GET['p'];
        }
        return false;
    }

    public function doSearch()
    {
        if(isset($_POST['q'])) {
            return $_POST['q'];
        }
        return false;
    }

    public function isChangeUserRole() {
        if(isset($_POST['changerole'])) {
            return $_POST['role'];
        }
        return false;
    }

    public function getUser() {
        if(isset($_GET['username'])) {
            return $_GET['username'];
        }
        return false;
    }

    public function deleteAccount()
    {
        if(isset($_GET['delete'])) {
            return $_GET['delete'];
        }
        return false;
    }
}