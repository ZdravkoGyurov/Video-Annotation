<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    
    function findByVideoName($video, $image, $videoName) {
        if(isset($_COOKIE['loggedUserEmail']) && !empty(isset($_COOKIE['loggedUserEmail']))) {
            $errors = array();

            // validate videoName

            if(empty($errors)) {
                $video->findVideoByName($videoName);

                if(!isset($video->name)) {
                    $errors['noSuchVideoFoundError'] = 'No video with given name found!';
                    echo json_encode(array(
                        'errors' => $errors
                    ));
                } else {
                    $allImages = $image->findAllImagesByVideoName($videoName);
                    $numberOfImages = $allImages->rowCount();

                    if($numberOfImages > 0) {
                        echo json_encode($allImages->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
                    } else {
                        $errors['noImageFoundError'] = 'No images found for this video!';
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
            $errors['noUserError'] = 'You need to be logged in to see images!';
            echo json_encode(array(
                'errors' => $errors
            ));
        }
    }
