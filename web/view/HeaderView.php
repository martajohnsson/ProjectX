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
                            <a href='index.php'>Home</a> /
                        </li>
                        <li>
                            <a href='?page=listsnippets'>Snippets</a> /
                        </li>
                        <li>
                            <a href='?page=addsnippet'>Add snippet</a> /
                        </li>
                        <li>
                            <a href='#'>News</a> /
                        </li>
                        <li>
                            <a href='#'>Downloads</a> /
                        </li>
                        <li>
                            <a href='#'>About</a>
                        </li>
                        <li class ='right'>
                            <a href='?logout=true'>Logga ut</a>
                        </li>
                        <li class='right'>
                            <img id='topAvatar' src='$userPic' alt='as' />
                        </li>
                        <li class='right'>
                            Hello,<a href='?page=profile&user=$email'>$name</a>
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
                            <a href='index.php'>Home</a> /
                        </li>
                        <li>
                            <a href='?page=listsnippets'>Snippets</a> /
                        </li>
                        <li>
                        <a href='?page=addsnippet'>Add snippet</a> /
                        </li>
                        <li>
                            <a href='#'>News</a> /
                        </li>
                        <li>
                            <a href='#'>Downloads</a> /
                        </li>
                        <li>
                            <a href='#'>About</a> /
                        </li>
                        <li>
                            <a href='#'>Register</a>
                        </li>
                        <li class='right'>
                            <a class='janrainEngage' href='#'>Logga in</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>";

        return $html;
    }

}