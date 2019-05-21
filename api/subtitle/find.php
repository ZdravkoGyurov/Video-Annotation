<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    function findSubtitle($video, $subtitle, $videoName) {
        if(isset($_COOKIE['loggedUserEmail']) && !empty(isset($_COOKIE['loggedUserEmail']))) {
            $errors = array();

            Validator::validateVideoName($videoName, $errors);

            if(empty($errors)) {
                $video->findVideoByName($videoName);

                if(!isset($video->name)) {
                    $errors['noSuchVideoFoundError'] = 'No video with given name found!';
                    echo json_encode(array(
                        'errors' => $errors
                    ));
                } else {
                    $subtitle->findSubtitleByVideoId($video->id);
                    
                    if(!isset($subtitle->name)) {
                        $errors['noSuchSubtitleFoundError'] = 'No subtitle with given video name found!';
                        echo json_encode(array(
                            'errors' => $errors
                        ));
                    } else {
                        echo json_encode($subtitle, JSON_UNESCAPED_UNICODE);
                    }
                }
            } else {        
                echo json_encode(array(
                    'errors' => $errors
                ));
            }
        } else {
            $errors['noAdminError'] = 'You need to be logged in as User to see this subtitle!';
            echo json_encode(array(
                'errors' => $errors
            ));
        }
    }