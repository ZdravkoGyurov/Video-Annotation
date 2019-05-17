<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: '.
    'Access-Control-Allow-Headers, Content-Type, '.
    'Access-Control-Allow-Methods, Authorization, X-Requested-With');

    function deleteSubtitle($user, $subtitle, $videoId) {
        if(isset($_COOKIE['loggedUserEmail']) && !empty(isset($_COOKIE['loggedUserEmail']))) {
            $errors = array();

            $user->findUserByEmail($_COOKIE['loggedUserEmail']);

            if($user->roleName == 'User') {
                // validate videoId

                if(empty($errors)) {
                    $subtitle->findSubtitleByVideoId($videoId);
                    
                    if(!isset($subtitle->name)) {
                        $errors['noSuchSubtitleFoundError'] = 'No subtitle with given video id found!';
                        echo json_encode(array(
                            'errors' => $errors
                        ));
                    } else if($subtitle->deleteSubtitle($videoId)) {
                        echo json_encode($subtitle, JSON_UNESCAPED_UNICODE);
                    } else {
                        $errors['unableToDeleteSubtitleError'] = 'Subtitle could not be deleted!';
                        echo json_encode(array(
                            'errors' => $errors
                        ));
                    }
                } else {                
                    echo json_encode(array(
                        'errors' => $errors
                    ));
                }
            } else {
                $errors['unauthorizedUserError'] = 'You are unauthorized!';
                echo json_encode(array(
                    'errors' => $errors
                ));
            }
        } else {
            $errors['noAdminError'] = 'You need to be logged in as User to delete subtitles!';
            echo json_encode(array(
                'errors' => $errors
            ));
        }
    }