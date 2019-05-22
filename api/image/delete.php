<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: '.
    'Access-Control-Allow-Headers, Content-Type, '.
    'Access-Control-Allow-Methods, Authorization, X-Requested-With');

    function deleteImage($user, $video, $image) {
        $data = json_decode(file_get_contents('php://input'));

        if($data->videoId != '' && $data->timestamp != '') {
            if(isset($_COOKIE['loggedUserEmail']) && !empty(isset($_COOKIE['loggedUserEmail']))) {
                $errors = array();

                $user->findUserByEmail($_COOKIE['loggedUserEmail']);

                if($user->roleName == 'User') {
                    Validator::validateVideoId($data->videoId, $errors);
                    Validator::validateVideoTimestamp($data->timestamp, $errors);

                    if(empty($errors)) {
                        $video->findVideoById($data->videoId);
                        $image->findImageByVideoNameAndTimestamp($video->name, $data->timestamp);

                        if($user->id != $video->userId) {
                            $errors['unauthorizedUserError'] = 'You are unauthorized!';
                            echo json_encode(array(
                                'errors' => $errors
                            ));
                        } else {
                            if(!isset($video->name)) {
                                $errors['noSuchVideoFoundError'] = 'No video with given id found!';
                                echo json_encode(array(
                                    'errors' => $errors
                                ));
                            } else {
                                if(!isset($image->name)) {
                                    $errors['noSuchImageFoundError'] = 'No image with given video id and timestamp found!';
                                    echo json_encode(array(
                                        'errors' => $errors
                                    ));
                                } else if($image->deleteImage($data->videoId, $data->timestamp)) {
                                    echo json_encode($image, JSON_UNESCAPED_UNICODE);
                                } else {
                                    $errors['unableToDeleteImageError'] = 'Image could not be deleted!';
                                    echo json_encode(array(
                                        'errors' => $errors
                                    ));
                                }
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
                $errors['noUserError'] = 'You need to be logged in as User to delete images!';
                echo json_encode(array(
                    'errors' => $errors
                ));
            }
        } else {
            $errors['dataIncompleteError'] = 'Image could not be deleted! Data is incomplete!';
            echo json_encode(array(
                'errors' => $errors
            ));
        }
    }