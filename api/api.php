<?php
    include_once '../config/Database.php';

    include_once '../models/User.php';
    include_once '../models/Video.php';
    include_once '../models/Image.php';
    include_once '../models/Subtitle.php';

    include_once 'user/create.php';
    include_once 'user/login.php';
    include_once 'user/logout.php';
    include_once 'user/update.php';
    include_once 'user/delete.php';
    include_once 'user/findAll.php';
    
    include_once 'subtitle/find.php';
    include_once 'subtitle/delete.php';

    include_once 'video/findAll.php';
    include_once 'video/delete.php';

    $database = new Database();
    $connection = $database->getConnection();

    $uriSegments = explode('.php/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    $errors = array();

    if($uriSegments[1] == 'register') {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = new User($connection);
            register($user);
        }
    } else if($uriSegments[1] == 'login') {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = new User($connection);
            login($user);
        }
    } else if($uriSegments[1] == 'logout') {
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            $user = new User($connection);
            logout($user);
        }
    } else if($uriSegments[1] == 'update-password') {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = new User($connection);
            updatePassword($user);
        }
    } else if($uriSegments[1] == 'all-users') {
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            $user = new User($connection);
            findAllUsers($user);
        }
    } else if($uriSegments[1] == 'all-videos') {
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            $video = new Video($connection);
            findAllVideos($video);
        }
    } else if(strpos($uriSegments[1], '/')) {
        $segments = explode('/', $uriSegments[1]);
        if($segments[0] == 'delete-user') {
            if($_SERVER['REQUEST_METHOD'] == 'DELETE') {
                $user = new User($connection);
                deleteUser($user, $segments[1]);
            }
        } else if($segments[0] == 'find-subtitle') {
            if($_SERVER['REQUEST_METHOD'] == 'GET') {
                $user = new User($connection);
                $subtitle = new Subtitle($connection);
                findSubtitle($user, $subtitle, $segments[1]);
            }
        } else if($segments[0] == 'delete-subtitle') {
            if($_SERVER['REQUEST_METHOD'] == 'DELETE') {
                $user = new User($connection);
                $subtitle = new Subtitle($connection);
                deleteSubtitle($user, $subtitle, $segments[1]);
            }
        } else if($segments[0] == 'delete-video') {
            if($_SERVER['REQUEST_METHOD'] == 'DELETE') {
                $user = new User($connection);
                $video = new Video($connection);
                deleteVideo($user, $video, $segments[1]);
            }
        } else {
            $errors['pageNotFoundError'] = 'Page not found!';
            echo json_encode(array(
                'errors' => $errors
            ));
        }
    } else {
        $errors['pageNotFoundError'] = 'Page not found!';
        echo json_encode(array(
            'errors' => $errors
        ));
    }
