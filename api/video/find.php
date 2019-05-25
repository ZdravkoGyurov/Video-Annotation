<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/javascript');
    
    function findVideo($video, $image, $subtitle, $name) {
        if(isset($_COOKIE['loggedUserEmail']) && !empty(isset($_COOKIE['loggedUserEmail']))) {
            $errors = array();

            Validator::validateVideoName($name, $errors);

            if(empty($errors)) {
                $video->findVideoByName($name);
                $subtitle->findSubtitleByVideoId($video->id);
                $allImages = $image->findAllImagesByVideoName($name);
                $numberOfImages = $allImages->rowCount();
                $imagesArr = array();
                $imagesArr['data'] = array();

                if($numberOfImages > 0) {
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
                    usort($imagesArr['data'], "compareImageArrays");
                }
                if(!isset($video->name)) {
                    $errors['noSuchVideoFoundError'] = 'No video with given id found!';
                    echo json_encode(array(
                        'errors' => $errors
                    ));
                } else {
                    echo json_encode(array(
                        'video' => $video,
                        'images' => $imagesArr,
                        'subtitle' => $subtitle
                    ), JSON_UNESCAPED_UNICODE);
                }
            } else {             
                echo json_encode(array(
                    'errors' => $errors
                ));
            }
        } else {
            $errors['unauthorizedUserError'] = 'You need to be logged in to see this video!';
            echo json_encode(array(
                'errors' => $errors
            ));
        }
    }

    function compareImageArrays($first, $second) {
        return $first['timestamp'] - $second['timestamp'];
    }