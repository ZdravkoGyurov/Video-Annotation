<?php
    include_once '../config/Database.php';

    include_once '../validate/Validator.php';

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

    include_once 'image/findByVideoNameAndTimestamp.php';
    include_once 'image/findAllByVideoName.php';
    include_once 'image/delete.php';

    include_once 'video/find.php';
    include_once 'video/findAllByUser.php';
    include_once 'video/findAll.php';
    include_once 'video/delete.php';
    include_once 'video/create.php';

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
    } else if($uriSegments[1] == 'find-image') {
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            $video = new Video($connection);
            $image = new Image($connection);
            findByVideoNameAndTimestamp($video, $image);
        }
    } else if($uriSegments[1] == 'delete-image') {
        if($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            $user = new User($connection);
            $video = new Video($connection);
            $image = new Image($connection);
            deleteImage($user, $video, $image);
        }
    } else if($uriSegments[1] == 'upload-video') {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = new User($connection);
            $video = new Video($connection);
            uploadVideo($user, $video);
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
                $video = new Video($connection);
                $subtitle = new Subtitle($connection);
                findSubtitle($video, $subtitle, $segments[1]);
            }
        } else if($segments[0] == 'delete-subtitle') {
            if($_SERVER['REQUEST_METHOD'] == 'DELETE') {
                $user = new User($connection);
                $video = new Video($connection);
                $subtitle = new Subtitle($connection);
                deleteSubtitle($user, $video, $subtitle, $segments[1]);
            }
        } else if($segments[0] == 'find-video') {
            if($_SERVER['REQUEST_METHOD'] == 'GET') {
                $video = new Video($connection);
                findVideo($video, $segments[1]);
            }
        } else if($segments[0] == 'find-user-videos') {
            if($_SERVER['REQUEST_METHOD'] == 'GET') {
                $user = new User($connection);
                $video = new Video($connection);
                findAllUserVideos($user, $video, $segments[1]);
            }
        } else if($segments[0] == 'delete-video') {
            if($_SERVER['REQUEST_METHOD'] == 'DELETE') {
                $user = new User($connection);
                $video = new Video($connection);
                deleteVideo($user, $video, $segments[1]);
            }
        } else if($segments[0] == 'find-video-images') {
            if($_SERVER['REQUEST_METHOD'] == 'GET') {
                $video = new Video($connection);
                $image = new Image($connection);
                findByVideoName($video, $image, $segments[1]);
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
