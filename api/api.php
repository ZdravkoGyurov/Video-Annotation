<?php
    include_once '../config/Database.php';
    include_once '../models/User.php';
    include_once '../models/Video.php';
    include_once '../models/Image.php';
    include_once '../models/Subtitle.php';

    $database = new Database();
    $connection = $database->getConnection();

    $uriSegments = explode('.php/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    if($uriSegments[1] == 'user') {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = new User($connection);
            // $user->createUser('peshomail', 'pesho', 'peshov', 'peshopass', '2');
            // $user->updateUser(55, 'peshomail', 'pesho', 'peshov', 'peshopass', '2');
            // $user->findUserById(55);
            // $user->findUserByEmail('peshomail');
            // $allUsers = $user->findAllUsers();
            // $user->deleteUser('peshomail');
            // echo json_encode($user);
            // echo json_encode($allUsers->fetchAll(PDO::FETCH_ASSOC));
        }
    } else if($uriSegments[1] == 'video') {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $video = new Video($connection);
            // $video->createVideo('new video path', 'new video name', 'new video type', 1);
            // $video->findVideoByName('new video name');
            // $allVideos = $video->findAllVideosByUserId(1);
            // $allVideos = $video->findAllVideos();
            // $video->deleteVideo('new video name');

            // echo json_encode($video);
            // echo json_encode($allVideos->fetchAll(PDO::FETCH_ASSOC));
        }
    } else if($uriSegments[1] == 'image') {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $image = new Image($connection);
            // $image->createImage('image path', 'image name', 'image type', 661, 'image annotation', 1);
            // $image->findImageByVideoNameAndTimestamp('video_name', 661);
            // $allImages = $image->findAllImagesByVideoName('video_name');
            // $image->deleteImage(1, 661);
            // echo json_encode($image);
            // echo json_encode($allImages->fetchAll(PDO::FETCH_ASSOC));
        }
    } else if($uriSegments[1] == 'subtitle') {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $subtitle = new Subtitle($connection);
            // $subtitle->createSubtitle('subtitle path', 'subtitle name', 'subtitle type', 1);
            // $subtitle->findSubtitleByVideoName('video_name');
            // $subtitle->deleteSubtitle(1);
            // echo json_encode($subtitle);
        }
    } else {
        echo 'Page not found.';
    }
