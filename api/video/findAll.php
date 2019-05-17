<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    function findAllVideos($video) {
        if(isset($_COOKIE['loggedUserEmail']) && !empty(isset($_COOKIE['loggedUserEmail']))) {
            $errors = array();

            $allVideos = $video->findAllVideos();
            $numberOfVideos = $allVideos->rowCount();

            if($numberOfVideos > 0) {
                echo json_encode($allVideos->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
            } else {
                $errors['noVideoFoundError'] = 'No videos found!';
                echo json_encode(array(
                    'errors' => $errors
                ));
            }
        } else {
            $errors['unauthorizedUserError'] = 'You need to be logged in to see all videos!';
            echo json_encode(array(
                'errors' => $errors
            ));
        }
    }