<?php

class HeaderView
{
    /**
     * @param $name string Name of user
     * @param $userPic string url of user avatar
     * @return string View of header with logged in layout
     */
    public function inloggedHeader($name, $userPic, $email)
    {
        $html = "<div class='navbar navbar-inverse navbar-fixed-top'>
                    <div class='navbar-inner'>
                        <div class='container'>
                            <a class='btn btn-navbar' data-toggle='collapse' data-target='.nav-collapse'>
                            <span class='icon-bar'></span>
                            <span class='icon-bar'></span>
                            <span class='icon-bar'></span>
                            </a>
          
                            <div class='nav-collapse collapse'>
                                <ul class='nav'>
                                    <li>
                                        <a href='index.php'>Home</a>
                                    </li>
                                    <li>
                                        <a href='?page=addsnippet'>Add snippet</a>
                                    </li>
                                    <li>
                                        <a href='?page=listblogposts'>Blog</a>
                                    </li>";
                                    
                                    if (Authhandler::isAdmin()) {
                                    $html .= 
                                    "<li>
                                        <a href='?page=addblogpost'>Add blogpost</a>
                                    </li>";    
                                    }
                                    
                                    $html .= 
                                    "<li>
                                        <a href='?page=downloads'>Downloads</a>
                                    </li>
                                    <li>
                                        <a id='about' href='#'>Learn more</a>
                                    </li>
                                </ul>
                                <ul class='nav nav-list'>
                                    <li class='divider'></li>
                                </ul>
                                <ul class='nav navbar-text pull-right'>
                                    <li>
                                        <img class='avatar-pic-small' src='" . $userPic . "' alt='as' />
                                    </li>
                                    <li>
                                        <a href='?page=profile'>" . $name . "</a>
                                    </li>
                                    <li>
                                        <a href='?logout=true'>Sign out</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>";

        return $html;
    }

    /**
     * @return string View of header when user not logged in
     */
    public function notLoggedInHeader()
    {
        $html = "<div class='navbar navbar-inverse navbar-fixed-top'>
                    <div class='navbar-inner'>
                        <div class='container'>
                            <a class='btn btn-navbar' data-toggle='collapse' data-target='.nav-collapse'>
                            <span class='icon-bar'></span>
                            <span class='icon-bar'></span>
                            <span class='icon-bar'></span>
                            </a>
          
                            <div class='nav-collapse collapse'>
                                <ul class='nav'>
                                    <li>
                                        <a href='index.php'>Home</a>
                                    </li>
                                    <li>
                                        <a href='?page=listblogposts'>Blog</a>
                                    </li>
                                    <li>
                                        <a href='?page=downloads'>Downloads</a>
                                    </li>
                                    <li>
                                        <a id='about' href='#'>Learn more</a>
                                    </li>
                                </ul>
                                
                                <ul class='nav navbar-text pull-right'>
                                    <li>
                                        <a class='janrainEngage' href='#'>Sign in</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>";

        return $html;
    }
}