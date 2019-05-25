<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: '.
    'Access-Control-Allow-Headers, Content-Type, '.
    'Access-Control-Allow-Methods, Authorization, X-Requested-With');

    function uploadImage($user, $video, $image) {
        if(isset($_COOKIE['loggedUserEmail']) && !empty(isset($_COOKIE['loggedUserEmail']))) {
            $errors = array();

            $user->findUserByEmail($_COOKIE['loggedUserEmail']);
            $videoUserId = $_POST['videouserid'];

            if($user->roleName == 'User' && $user->id == $videoUserId) {
                $annotation = $_POST['annotation'];
                $imgData = $_POST['imagedata'];
                $timestamp = $_POST['timestamp'];
                $videoId = $_POST['videoid'];
                $videoName = $_POST['videoname'];

                Validator::validateVideoName($videoName, $errors);

                if(empty($errors)) {
                    $imageName = $videoName.'_'.$timestamp;
                    $targetPath = '..\\uploaded-videos\\'.$videoName.'\\'.$imageName.'.png';
                    define("pathDB", substr(getcwd(), 0, -3).'uploaded-videos\\'.$videoName.'\\'.$imageName.'.png');
                    define("nameDB", $imageName);
                    define("typeDB", 'image/png');
                    define("timestampDB", $timestamp);
                    define("annotationDB", $annotation);
                    define("videoIdDB", $videoId);

                    if($image->createImage(pathDB, nameDB, typeDB, timestampDB, annotationDB, videoIdDB)) {
                        $imgData = str_replace('data:image/png;base64,', '', $imgData);
                        $imgData = str_replace(' ', '+', $imgData);
                        $decodedImage = base64_decode($imgData);

                        if(file_put_contents($targetPath, $decodedImage)) {
                            echo json_encode($image, JSON_UNESCAPED_UNICODE);
                        } else {
                            $image->deleteImage($videoId, $timestamp);
                            $errors['uploadFailedError'] = 'Image failed to upload!';
                            echo json_encode(array(
                                'errors' => $errors
                            ));
                        }
                    } else {
                        $errors['uploadFailedError'] = 'Image failed to upload!';
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
            $errors['noUserError'] = 'You need to be logged as User to upload images!';
            echo json_encode(array(
                'errors' => $errors
            ));
        }
    }