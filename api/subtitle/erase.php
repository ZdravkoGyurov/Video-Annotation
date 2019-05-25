<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: '.
    'Access-Control-Allow-Headers, Content-Type, '.
    'Access-Control-Allow-Methods, Authorization, X-Requested-With');

    function eraseSubtitle($user, $video, $subtitle) {
        $data = json_decode(file_get_contents('php://input'));

        if($data->subtitleText != '' && $data->videoId != '' && $data->videoUserId != '') {
            if(isset($_COOKIE['loggedUserEmail']) && !empty(isset($_COOKIE['loggedUserEmail']))) {
                $errors = array();

                // validate fields
                $user->findUserByEmail($_COOKIE['loggedUserEmail']);

                if($user->roleName == 'User' && $user->id == $data->videoUserId) {
                    if(empty($errors)) {
                        $subtitle->findSubtitleByVideoId($data->videoId);

                        $filePath = $subtitle->path;
                        $file = file_get_contents($filePath);
                        $lines = explode("\n", $file);
                        $exclude = array();
                        $skipNextLine = 0;

                        for($i = 0; $i < count($lines) - 1; $i++) {
                            if($skipNextLine != 0) {
                                $skipNextLine--;
                                continue;
                            }
                            if(strpos($lines[$i + 1], $data->subtitleText) !== false) {
                                $skipNextLine = 2;
                                continue;
                            }
                            $exclude[] = $lines[$i];
                        }

                        $newFileContent = implode("\n", $exclude)."\n";

                        while(strpos($newFileContent, "\n\n\n")) {
                            $newFileContent = str_replace("\n\n\n", "\n\n", $newFileContent);
                        }

                        file_put_contents($filePath, $newFileContent);

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