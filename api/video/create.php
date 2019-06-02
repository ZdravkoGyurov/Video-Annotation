<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: '.
    'Access-Control-Allow-Headers, Content-Type, '.
    'Access-Control-Allow-Methods, Authorization, X-Requested-With');
   
    function uploadVideo($user, $video, $subtitle) {
        if(isset($_COOKIE['loggedUserEmail']) && !empty(isset($_COOKIE['loggedUserEmail']))) {
            $errors = array();

            $user->findUserByEmail($_COOKIE['loggedUserEmail']);

            if($user->roleName == 'User') {
                $fileNamePure = pathinfo($_FILES['uploaded-file']['name'], PATHINFO_FILENAME);
                Validator::validateVideoName($fileNamePure, $errors);

                if(empty($errors)) {
                    if(!empty($_FILES['uploaded-file']['name'])) {
                        $uploadedFile = '';
            
                        if(!empty($_FILES['uploaded-file']['type'])){
                            $fileNameWithType = $_FILES['uploaded-file']['name'];
                            $sourcePath = $_FILES['uploaded-file']['tmp_name'];
                            $targetPath = "..\\uploaded-videos\\".$fileNameWithType;
            
                            define("pathDB", substr(getcwd(), 0, -3).'uploaded-videos\\'.$fileNameWithType);
                            define("nameDB", $fileNamePure);
                            define("typeDB", $_FILES['uploaded-file']['type']);
                            define("userIdDB", $user->id);

                            if($video->createVideo(pathDB, nameDB, typeDB, userIdDB)) {
                                if(!file_exists(substr(getcwd(), 0, -3).'uploaded-videos')) {
                                    mkdir(substr(getcwd(), 0, -3).'uploaded-videos');
                                }
                                if(move_uploaded_file($sourcePath, $targetPath)) {
                                    mkdir(substr(getcwd(), 0, -3).'uploaded-videos\\'.$fileNamePure);
                                    $video->findVideoByName(nameDB);
                                    $subFilePath = substr(getcwd(), 0, -3).'uploaded-videos\\'.$fileNamePure.'\\'.$fileNamePure.'.vtt';
                                    $subtitle->createSubtitle($subFilePath, $fileNamePure, 'subtitles/vtt', $video->id);
                                    touch($subFilePath);
                                    $subFile = file_get_contents($subFilePath);
                                    $subFile .= "WEBVTT\n\n";
                                    file_put_contents($subFilePath, $subFile);

                                    echo json_encode($video, JSON_UNESCAPED_UNICODE);
                                } else {
                                    $video->deleteVideo(nameDB);
                                    $errors['uploadFailedError'] = 'Video failed to upload!';
                                    echo json_encode(array(
                                        'errors' => $errors
                                    ));
                                }
                            } else {
                                $errors['uploadFailedError'] = 'Video failed to upload!';
                                echo json_encode(array(
                                    'errors' => $errors
                                ));
                            }
                        } else {
                            $errors['fileTypeError'] = 'Video type is forbidden!';
                            echo json_encode(array(
                                'errors' => $errors
                            ));
                        }   
                    } else {
                        $errors['uploadFailedError'] = 'Video failed to upload!';
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
            $errors['noUserError'] = 'You need to be logged as User to upload videos!';
            echo json_encode(array(
                'errors' => $errors
            ));
        }
    }