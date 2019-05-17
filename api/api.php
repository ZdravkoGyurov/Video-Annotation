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
            findAll($user);
        }
    } else if(strpos($uriSegments[1], '/')) {
        $segments = explode('/', $uriSegments[1]);
        if($segments[0] == 'delete-user') {
            if($_SERVER['REQUEST_METHOD'] == 'DELETE') {
                $user = new User($connection);
                deleteUser($user, $segments[1]);
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
