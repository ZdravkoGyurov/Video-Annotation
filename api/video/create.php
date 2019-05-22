<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: '.
    'Access-Control-Allow-Headers, Content-Type, '.
    'Access-Control-Allow-Methods, Authorization, X-Requested-With');
   
    function uploadVideo($user, $video) {
        if(isset($_COOKIE['loggedUserEmail']) && !empty(isset($_COOKIE['loggedUserEmail']))) {
            $errors = array();

            $user->findUserByEmail($_COOKIE['loggedUserEmail']);

            if($user->roleName == 'User') {
                Validator::validateVideoName(pathinfo($_FILES['uploaded-file']['name'], PATHINFO_FILENAME), $errors);

                if(empty($errors)) {
                    if(!empty($_FILES['uploaded-file']['name'])) {
                        $uploadedFile = '';
            
                        if(!empty($_FILES['uploaded-file']['type'])){
                            $fileName = $_FILES['uploaded-file']['name'];
                            $sourcePath = $_FILES['uploaded-file']['tmp_name'];
                            $targetPath = "..\\uploaded-videos\\".$fileName;
            
                            define("pathDB", substr(getcwd(), 0, -3).'uploaded-videos\\'.$fileName);
                            define("nameDB", $fileName);
                            define("typeDB", $_FILES['uploaded-file']['type']);
                            define("userIdDB", $user->id);

                            if($video->createVideo(pathDB, nameDB, typeDB, userIdDB)) {
                                if(move_uploaded_file($sourcePath, $targetPath)) {
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