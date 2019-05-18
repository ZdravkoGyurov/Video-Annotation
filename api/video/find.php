<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    
    function findVideo($video, $name) {
        if(isset($_COOKIE['loggedUserEmail']) && !empty(isset($_COOKIE['loggedUserEmail']))) {
            $errors = array();

            // validate name

            if(empty($errors)) {
                $video->findVideoByName($name);

                if(!isset($video->name)) {
                    $errors['noSuchVideoFoundError'] = 'No video with given id found!';
                    echo json_encode(array(
                        'errors' => $errors
                    ));
                } else {
                    echo json_encode($video, JSON_UNESCAPED_UNICODE);
                }
            } else {             
                echo json_encode(array(
                    'errors' => $errors
                ));
            }
        } else {
            $errors['unauthorizedUserError'] = 'You need to be logged in to see this video!';
            echo json_encode(array(
                'errors' => $errors
            ));
        }
    }