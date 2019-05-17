<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    function findSubtitle($user, $subtitle, $videoId) {
        if(isset($_COOKIE['loggedUserEmail']) && !empty(isset($_COOKIE['loggedUserEmail']))) {
            $errors = array();

            $user->findUserByEmail($_COOKIE['loggedUserEmail']);

            if($user->roleName == 'User') {
                $subtitle->findSubtitleByVideoId($videoId);
                
                if(!isset($subtitle->name)) {
                    $errors['noSuchSubtitleFoundError'] = 'No subtitle with given video id found!';
                    echo json_encode(array(
                        'errors' => $errors
                    ));
                } else {
                    echo json_encode($subtitle, JSON_UNESCAPED_UNICODE);
                }
            } else {
                $errors['unauthorizedUserError'] = 'You are unauthorized!';
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