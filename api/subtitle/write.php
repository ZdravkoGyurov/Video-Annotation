<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: '.
    'Access-Control-Allow-Headers, Content-Type, '.
    'Access-Control-Allow-Methods, Authorization, X-Requested-With');

    function writeSubtitle($user, $video, $subtitle) {
        $data = json_decode(file_get_contents('php://input'));

        if($data->startTime != '' && $data->endTime != '' && $data->annotation != '' && $data->videoId != '' && $data->videoUserId != '') {
            if(isset($_COOKIE['loggedUserEmail']) && !empty(isset($_COOKIE['loggedUserEmail']))) {
                $errors = array();

                Validator::validateStartTime($data->startTime, $errors);
                Validator::validateEndTime($data->endTime, $errors);
                Validator::validateAnnotation($data->annotation, $errors);
                Validator::validateVideoId($data->videoId, $errors);
                Validator::validateUserId($data->videoUserId, $errors);

                $user->findUserByEmail($_COOKIE['loggedUserEmail']);
    
                if($user->roleName == 'User' && $user->id == $data->videoUserId) {
                    if(empty($errors)) {
                        $subtitle->findSubtitleByVideoId($data->videoId);

                        $filePath = $subtitle->path;
                        $currentContent = file_get_contents($filePath);
                        $currentContent .= "\n".$data->startTime." --> ".$data->endTime."\n".$data->annotation."\n";
                        file_put_contents($filePath, $currentContent);

                        echo json_encode($subtitle, JSON_UNESCAPED_UNICODE);
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
                $errors['noUserError'] = 'You need to be logged as User to upload images!';
                echo json_encode(array(
                    'errors' => $errors
                ));
            }
        } else {
            $errors['dataIncompleteError'] = 'Cannot find image! Data is incomplete!';
            echo json_encode(array(
                'errors' => $errors
            ));
        }
    }