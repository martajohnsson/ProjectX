<?php

class BloggView {

    public function allPosts($posts) {
        $html = '';
        if($posts) {
            foreach ($posts as $post) {
                $html .= $this->singlePost($post);
            }
        } else {
            $html = "<h1>No posts yet</h1>";
        }

        return $html;
    }

    public function singlePost($post) {
        $html = "<div class='post'>
            <h1><a href='?post=".$post['id']."'>".$post['title']."</a></h1>
            <p>".$post['content']."</p>
            <div class='post-footer'>
            <p>posted by: ".$post['authorID'].", ".$post['date']."</p>
            </div>
            <div class='blogg-actions'>
                <p>
                    <a href='?post=".$post['id']."&action=update'>Update post</a>
                    <a href='?post=".$post['id']."&action=delete'>Delete post</a>
                </p>
            </div>
        </div>";
        return $html;
    }

    public function addPost() {
        $html = "<form action='/news?action=add' method='POST'>
            <input type='text' name='title' placeholder='Title'/>
            <textarea placeholder='your blogg text' name='content'></textarea>
            <input type='submit' value='add post' name='add' />
        </form>";
        return $html;
    }

    public function updatePost($post) {
        $html = "<form action='/news?action=update&post=".$post['id']."' method='POST'>
            <input type='text' name='title' value='".$post['title']."'/>
            <textarea name='content'>".$post['content']."</textarea>
            <input type='submit' value='update post' name='update' />
        </form>";
        return $html;
    }

    public function isAddPost() {
        if(isset($_POST['add'])) {
            return true;
        }
        return false;
    }

    public function isUpdate() {
        if(isset($_POST['update'])) {
            return true;
        }
        return false;
    }

    public function getTitle() {
        if(isset($_POST['title'])) {
            return $_POST['title'];
        }
        return false;
    }

    public function getContent() {
        if(isset($_POST['content'])) {
            return $_POST['content'];
        }
        return false;
    }

}