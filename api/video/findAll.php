<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    function findAllVideos($video) {
        if(isset($_COOKIE['loggedUserEmail']) && !empty(isset($_COOKIE['loggedUserEmail']))) {
            $errors = array();

            $allVideos = $video->findAllVideos();
            $numberOfVideos = $allVideos->rowCount();

            if($numberOfVideos > 0) {
                $videosArr = array();
                $videosArr['data'] = array();

                while($row = $allVideos->fetch(PDO::FETCH_ASSOC)) {
                    $videoItem = array(
                        'id' => $row['id'],
                        'path' => $row['path'],
                        'name' => $row['name'],
                        'type' => $row['type'],
                        'userId' => $row['user_id']
                    );
                    array_push($videosArr['data'], $videoItem);
                }
                echo json_encode($videosArr, JSON_UNESCAPED_UNICODE);
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