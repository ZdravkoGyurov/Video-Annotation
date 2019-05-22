<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    
    function findByVideoName($video, $image, $videoName) {
        if(isset($_COOKIE['loggedUserEmail']) && !empty(isset($_COOKIE['loggedUserEmail']))) {
            $errors = array();

            Validator::validateVideoName($videoName, $errors);

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
                        $imagesArr = array();
                        $imagesArr['data'] = array();

                        while($row = $allImages->fetch(PDO::FETCH_ASSOC)) {
                            $imageItem = array(
                                'id' => $row['id'],
                                'path' => $row['path'],
                                'name' => $row['name'],
                                'type' => $row['type'],
                                'timestamp' => $row['timestamp'],
                                'annotation' => $row['annotation'],
                                'videoId' => $row['video_id'],
                                'videoName' => $row['video_name']
                            );
                            array_push($imagesArr['data'], $imageItem);
                        }
                        echo json_encode($imagesArr, JSON_UNESCAPED_UNICODE);
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
