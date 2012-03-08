<?php
require_once __DIR__ . '/../model/BloggHandler.php';
require_once __DIR__ . '/../view/BloggView.php';

class BloggController {

    private $_bloggHandler;
    private $_bloggView;

    public function __construct() {
        $this->_bloggHandler = new BloggHandler();
        $this->_bloggView = new BloggView();
    }

    public function doControll() {
        $html = '';
            
        if(AuthHandler::isAdmin() && isset($_GET['action'])) {
            $action = $_GET['action'];
            
            if($action == 'add') {
                $html = $this->_bloggView->addPost();
                if($this->_bloggView->isAddPost()) {
                    $content = $this->_bloggView->getContent();
                    $title = $this->_bloggView->getTitle();
                    if($id = $this->_bloggHandler->addPost($title, $content, AuthHandler::getUser()->getId())) {
                        header('location: /news?post='.$id);
                    }
                }
            } else if($action == 'update' && isset($_GET['post'])){
                $post = $this->_bloggHandler->getPostByID($_GET['post']);
                $html .= $this->_bloggView->updatePost($post);
                $id = $_GET['post'];
                if($this->_bloggView->isUpdate()){
                    $content = $this->_bloggView->getContent();
                    $title = $this->_bloggView->getTitle();
                    if($this->_bloggHandler->updatePost($id, $title, $content)) {
                        header('location: /news?post='.$id);
                    }
                }
            } else if($action = 'delete' && isset($_GET['post'])) {
                if($this->_bloggHandler->deletePost($_GET['post'])) {
                    header('location: /news');
                } else {
                    echo "något gick sneeet";
                }
            }

        } else if(isset($_GET['post'])) {
            $post = $this->_bloggHandler->getPostByID($_GET['post']);
            $html = $this->_bloggView->singlePost($post);
        }
        else {
            $posts = $this->_bloggHandler->getAllPosts();
            $html = $this->_bloggView->allPosts($posts);

        }

        return $html;
    }
}