<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    function findByVideoNameAndTimestamp($video, $image) {
        $data = json_decode(file_get_contents('php://input'));

        if($data->videoName != '' && $data->timestamp != '') {
            if(isset($_COOKIE['loggedUserEmail']) && !empty(isset($_COOKIE['loggedUserEmail']))) {
                $errors = array();

                Validator::validateVideoName($data->videoName, $errors);
                Validator::validateVideoTimestamp($data->timestamp, $errors);

                if(empty($errors)) {
                    $video->findVideoByName($data->videoName);
                    
                    if(!isset($video->name)) {
                        $errors['noSuchVideoFoundError'] = 'No video with given name found!';
                        echo json_encode(array(
                            'errors' => $errors
                        ));
                    } else {
                        $image->findImageByVideoNameAndTimestamp($data->videoName, $data->timestamp);
                        
                        if(!isset($image->name)) {
                            $errors['noSuchImageFoundError'] = 'No image with given video name and timestamp found!';
                            echo json_encode(array(
                                'errors' => $errors
                            ));
                        } else {
                            echo json_encode($image, JSON_UNESCAPED_UNICODE);
                        }
                    }
                } else {                
                    echo json_encode(array(
                        'errors' => $errors
                    ));
                }
            } else {
                $errors['noUserError'] = 'You need to be logged in as User to see the image!';
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