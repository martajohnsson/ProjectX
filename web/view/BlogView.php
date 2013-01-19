<?php

class BlogView
{
    /**
     * Returns html for a single blogpost
     * @param $blogpost
     * @return string
     */
    public function singleView($blogpost)
    {
        $html = "
                <div class='row'>
                    <div class='span12'>
                        <div class='inner'>
                            <h1 class='blogpost-title'>" . $blogpost->getTitle() . "</h1>
                            <div class='blogpost-author muted'>
                                Posted by " . $blogpost->getAuthor() . " on " . $blogpost->getDate() .
                            "</div>
                            <div class='blog-content'>
                                <p>" . $blogpost->getContent() . "</p>
                            </div>
                        </div>
                    </div>
                </div>
                ";
                
        if (AuthHandler::isAdmin()) {
            $html .= "
                    <div class='row'>
                        <div class='span12'>
                            <div class='inner blogpost-edit'>
                                Admin options:
                                <a onclick=\"javascript: return confirm('Do you want to remove this blogpost?')\" href='?page=removeblogpost&blogpost=" . $blogpost->getId() . "'>Delete post</a> | 
                                <a href='?page=editblogpost&blogpost=" . $blogpost->getId() . "'>Edit post</a>
                            </div>
                        </div>
                    </div>";
        }
              
        return $html;
    }
    
    /**
     * Returns html for a list of all blogposts
     * @param $blogposts
     * @return string
     */
    public function listView($blogposts)
    {
        if (empty($blogposts)) {
            $html = '
            <div class="row">
                <div class="span12">
                    <div class="inner">
                        <h1>No blogcontent</h1>
                    </div>
                </div>
            </div>';
            
            return $html;
        }
        
        $html = '
        <div class="row">
            <div class="span12">
                <div class="inner">
                    <h1>Blog entries</h1>
                </div>
            </div>
        </div>';
        
        foreach($blogposts as $blogpost) {
            $html .= '
                <div class="row">
                    <div class="span12">
                        <div class="blogpost-list-item">
                            <h2 class="blogpost-title">' . $blogpost->getTitle() . '</h2>
                            <div class="blogpost-read-more">
                                <p>' . $blogpost->getReadMoreContent() .'<a href="?page=listblogposts&blogpost=' . $blogpost->getId() . '">Read more</a></p>
                            </div>
                            <p class="blogpost-author muted">Posted by ' . $blogpost->getAuthor() . ' on ' . $blogpost->getDate() . '</p>
                        </div>
                    </div>
                </div>
            ';
        }
            
            return $html;
    }
    
    /**
     * Creates html for adding a blogpost
     * @return string
     */
    public function addBlogpost()
    {
        $html = '
            <div class="row">
                <div class="span12">
                    <div class="inner">
                        <h1 class="blogpost-title">Add new blogpost</h1>
                        <form action="" method="post">
                            <input class="input-xlarge" type="text" name="blogpostTitle" placeholder="Title" />
                            <textarea name="blogContent" class="editor input-xxlarge" maxlength="4000" placeholder="Your content"></textarea>
                            <input class="btn" type="submit" name="addBlogpostButton" id="addBlogpostButton" value="Add Blogpost" />
                        </form>
                    </div>
                </div>
            </div>';
        return $html;
    }
    
    /**
     * Creates html for editing a blogpost
     * @param $blogpost
     * @return string
     */
    public function editBlogpost($blogpost)
    {
        $html = '
            <div class="row">
                <div class="span12">
                    <div class="inner">
                    
                            <h1 class="blogpost-title">Edit the blogpost "' . $blogpost->getTitle() . '"</h1>
                    
                            <form action="" method="post">
                                <input class="input-xlarge" type="text" name="editBlogpostTitle" placeholder="Title" value="' . $blogpost->getTitle() . '" />
                                <textarea name="editBlogContent" class="editor input-xxlarge" maxlength="4000" placeholder="Your content">' . $blogpost->getContent() . '</textarea>
                                <input class="btn" type="submit" name="editBlogpostButton" id="editBlogpostButton" value="Save Blogpost" />
                            </form>
                        
                    </div>
                </div>
            </div>
            ';
            
        return $html;    
    }
    
    public function triedToAddBlogpost()
    {
        if (isset($_POST['addBlogpostButton'])) {
            return true;
        }
        return false;
    }

    public function getBlogpostTitle()
    {
        if (isset($_POST['blogpostTitle'])) {
            return $_POST['blogpostTitle']; 
        }
        return false;
    }

    public function getBlogpostContent()
    {
        if (isset($_POST['blogContent'])) {
            return stripslashes($_POST['blogContent']); 
        }
        return false;
    }
    
    public function triedToEditBlogpost()
    {
        if (isset($_POST['editBlogpostButton'])) {
            return true;
        }
        return false;
    }

    public function getEditBlogpostTitle()
    {
        if (isset($_POST['editBlogpostTitle'])) {
            return $_POST['editBlogpostTitle']; 
        }
        return false;
    }
    
    public function getEditBlogpostContent()
    {
        if (isset($_POST['editBlogContent'])) {
            return stripslashes($_POST['editBlogContent']); 
        }
        return false;
    }
}