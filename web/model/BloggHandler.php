<?php
require_once 'DbHandler.php';

class BloggHandler {

    private $_dbHandler;

    public function __construct() {
        $this->_dbHandler = new DbHandler();
    }

    public function getAllPosts() {
        $posts = array();
        $this->_dbHandler->__wakeup();
        if($stmt = $this->_dbHandler->prepareStatement("SELECT * FROM blogg")) {
            $stmt->execute();
            $stmt->bind_result($id, $title, $content, $date, $authorID);

            $i = 0;
            while($stmt->fetch()) {
                $posts[$i]['id'] = $id;
                $posts[$i]['title'] = $title;
                $posts[$i]['content'] = $content;
                $posts[$i]['date'] = $date;
                $posts[$i]['authorID'] = $authorID;
                $i++;
            }
        } else {
            $this->_dbHandler->close();
            return false;
        }

        $stmt->close();
        $this->_dbHandler->close();

        return $posts;
    }

    public function getPostByID($id) {
        $post = array();
        $this->_dbHandler->__wakeup();
        if($stmt = $this->_dbHandler->prepareStatement("SELECT * FROM blogg WHERE id = ?")) {
            $stmt->bind_param('i',$id);
            $stmt->execute();
            $stmt->bind_result($id, $title, $content, $date, $authorID);
            $stmt->store_result();            
            $stmt->fetch();
            
            $post['id'] = $id;
            $post['title'] = $title;
            $post['content'] = $content;
            $post['date'] = $date;
            $post['authorID'] = $authorID;

        } else {
            $this->_dbHandler->close();
            return false;
        }

        $stmt->close();
        $this->_dbHandler->close();

        return $post;
    }

    public function deletePost($id) {
        $result = 0;
        $this->_dbHandler->__wakeup();
        if($stmt = $this->_dbHandler->prepareStatement("DELETE FROM blogg WHERE id = ?")) {
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->store_result();
            $result = $stmt->affected_rows;
        } else {
            $this->_dbHandler->close();
            return false;
        }
        $stmt->close();
        $this->_dbHandler->close();

        return $result;
    }

    public function addPost($title, $content, $authorID) {
        $this->_dbHandler->__wakeup();
        $id = 0;
        if($stmt = $this->_dbHandler->prepareStatement("INSERT INTO blogg (title, content, user_id) VALUES(?,?,?)")) {
            $stmt->bind_param('ssi', $title, $content, $authorID);
            $stmt->execute();
            $stmt->store_result();
            $id = $stmt->insert_id;
        } else {
            $this->_dbHandler->close();
            return false;
        }
        $stmt->close();
        $this->_dbHandler->close();

        return $id;
    }

    public function updatePost($id, $title, $content) {
        $this->_dbHandler->__wakeup();
        $result = -1;
        if($stmt = $this->_dbHandler->prepareStatement("UPDATE blogg SET title = ?, content = ? WHERE id = ?")) {
            $stmt->bind_param('ssi', $title, $content, $id);
            $stmt->execute();
            $stmt->store_result();
            if($stmt->affected_rows == -1) return false;
        } else {
            $this->_dbHandler->close();
            return false;
        }
        $stmt->close();
        $this->_dbHandler->close();
        return true;
    }

}