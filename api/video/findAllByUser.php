<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    function findAllUserVideos($user, $video, $userId) {
        if(isset($_COOKIE['loggedUserEmail']) && !empty(isset($_COOKIE['loggedUserEmail']))) {
            $errors = array();

            $user->findUserByEmail($_COOKIE['loggedUserEmail']);

            if($user->roleName == 'User') {
                // validate userId
                
                if(empty($errors)) {
                    $allVideos = $video->findAllVideosByUserId($userId);
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
            $errors['noAdminError'] = 'You need to be logged in as User to see your videos!';
            echo json_encode(array(
                'errors' => $errors
            ));
        }
    }