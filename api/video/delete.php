<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: '.
    'Access-Control-Allow-Headers, Content-Type, '.
    'Access-Control-Allow-Methods, Authorization, X-Requested-With');

    function deleteVideo($user, $video, $name) {
        if(isset($_COOKIE['loggedUserEmail']) && !empty(isset($_COOKIE['loggedUserEmail']))) {
            $errors = array();

            $user->findUserByEmail($_COOKIE['loggedUserEmail']);

            if($user->roleName == 'Admin') {
                // validate name

                if(empty($errors)) {
                    $video->findVideoByName($name);

                    if(!isset($video->name)) {
                        $errors['noSuchVideoFoundError'] = 'No video with given name found!';
                        echo json_encode(array(
                            'errors' => $errors
                        ));
                    } else if($video->deleteVideo($video->name)) {
                        echo json_encode($video, JSON_UNESCAPED_UNICODE);
                    } else {
                        $errors['unableToDeleteVideoError'] = 'Video could not be deleted!';
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
            $errors['noAdminError'] = 'You need to be logged in as Admin to delete videos!';
            echo json_encode(array(
                'errors' => $errors
            ));
        }
    }