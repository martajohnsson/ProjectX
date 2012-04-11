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
        $html = "<div class='topbar-wrapper'>
            <div class='topbar'>
                <div class='topbar-inner'>
                    <ul class='nav'>
                        <li>
                            <a href='index.php'>Home</a> &#149;
                        </li>
                        <li>
                            <a href='?page=addsnippet'>Add snippet</a> &#149;
                        </li>
                        <li>
                            <a href='?page=listblogposts'>Blog</a> &#149;
                        </li>";
                        
                        if (Authhandler::isAdmin()) {
                            $html .= "<li>
                                <a href='?page=addblogpost'>Add blogpost</a> &#149;
                            </li>";    
                        }
                        
                        $html .= "<li>
                            <a href='?page=downloads'>Downloads</a> &#149;
                        </li>
                        <li>
                            <a id='about' href='#'>Learn more</a>
                        </li>
                        <li class='right' id='logout-topbar'>
                            <a href='?logout=true'>Sign out</a>
                        </li>

                        <li class='right'>
                            <a href='?page=profile'>" . $name . "</a>
                        </li>
                        <li class='right'>
                            <img id='topAvatar' src='" . $userPic . "' alt='as' />
                        </li>
                    </ul>
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
        $html = "<div class='topbar-wrapper'>
            <div class='topbar'>
                <div class='topbar-inner'>
                    <ul class='nav'>
                        <li>
                            <a href='index.php'>Home</a>  &#149;
                        </li>
                        <li>
                            <a href='?page=listblogposts'>Blog</a> &#149;
                        </li>
                        <li>
                            <a href='?page=downloads'>Downloads</a> &#149;
                        </li>
                        <li>
                            <a id='about' href='#'>Learn more</a>
                        </li>
                        <li class='right'>
                            <a class='janrainEngage' href='#'>Sign in</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>";

        return $html;
    }
}