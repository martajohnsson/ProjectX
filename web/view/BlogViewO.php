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
        $html = "<h2 class='blogpost-title'>" . $blogpost->getTitle() . "</h2>
                <div class='blogpost-author'>
                    Posted by " . $blogpost->getAuthor() . " on " . $blogpost->getDate() .
                "</div>
                <div class='blog-content'>
                    <p>" . $blogpost->getContent() . "</p>
                </div>";
                
        if (AuthHandler::isAdmin()) {
            $html .= "<div class='blogpost-edit'>
                        Admin options:
                        <a onclick=\"javascript: return confirm('Do you want to remove this blogpost?')\" href='?page=removeblogpost&blogpost=" . $blogpost->getId() . "'>Delete post</a> | 
                        <a href='?page=editblogpost&blogpost=" . $blogpost->getId() . "'>Edit post</a>
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
            $html = '<h1>No blogcontent</h1>';
            
            return $html;
        }
        
        $html = '<h1>Blog entries</h1>';
        
        foreach($blogposts as $blogpost) {
            $html .= '
                <div class="blogpost-list-item">
                    <div class="blogpost-title">
                        <p>' . $blogpost->getTitle() . '</p>
                    </div>
                    <div class="blogpost-read-more">
                        <p>' . $blogpost->getReadMoreContent() .'<a href="?page=listblogposts&blogpost=' . $blogpost->getId() . '">Read more</a></p>
                    </div>
                    <div class="blogpost-author">
                        <p>Posted by ' . $blogpost->getAuthor() . ' on ' . $blogpost->getDate() . '</p>
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
        $html = '<h1>Add new blogpost</h1>
            <div id="createBlogpostContainer">
                <form action="" method="post">
                    <input type="text" name="blogpostTitle" placeholder="Title" />
                    <textarea name="blogContent" class="editor" maxlength="4000" placeholder="Your content"></textarea>
                    <input type="submit" name="addBlogpostButton" id="addBlogpostButton" value="Add Blogpost" />
                </form>
            </div>
            ';
            
        return $html;
    }
    
    /**
     * Creates html for editing a blogpost
     * @param $blogpost
     * @return string
     */
    public function editBlogpost($blogpost)
    {
        $html = '<h1>Edit the blogpost "' . $blogpost->getTitle() . '"</h1>
            <div id="createBlogpostContainer">
                <form action="" method="post">
                    <input type="text" name="editBlogpostTitle" placeholder="Title" value="' . $blogpost->getTitle() . '" />
                    <textarea name="editBlogContent" class="editor" maxlength="4000" placeholder="Your content">' . $blogpost->getContent() . '</textarea>
                    <input type="submit" name="editBlogpostButton" id="editBlogpostButton" value="Save Blogpost" />
                </form>
            </div>';
            
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