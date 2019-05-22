<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    function findAllUserVideos($user, $video, $userEmail) {
        if(isset($_COOKIE['loggedUserEmail']) && !empty(isset($_COOKIE['loggedUserEmail']))) {
            $errors = array();

            $user->findUserByEmail($_COOKIE['loggedUserEmail']);

            if($user->roleName == 'Admin' || $user->email == $userEmail) {
                Validator::validateEmail($_COOKIE['loggedUserEmail'], $errors);
                
                if(empty($errors)) {
                    $user->findUserByEmail($userEmail);

                    if(!isset($user->name)) {
                        $errors['noSuchUserFoundError'] = 'No user with given email found!';
                        echo json_encode(array(
                            'errors' => $errors
                        ));
                    } else {
                        $allVideos = $video->findAllVideosByUserId($user->id);
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
            $errors['noAdminError'] = 'You need to be logged in to see your videos!';
            echo json_encode(array(
                'errors' => $errors
            ));
        }
    }