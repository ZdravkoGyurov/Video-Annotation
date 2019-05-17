<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: '.
    'Access-Control-Allow-Headers, Content-Type, '.
    'Access-Control-Allow-Methods, Authorization, X-Requested-With');

    function login($user) {
        $data = json_decode(file_get_contents('php://input'));
        
        if(isset($data->email) && isset($data->password)) {
            $errors = array();

            // validate email
            // validate password

            if(empty($errors)) {
                $user->findUserByEmail($data->email);

                if(!isset($user->name)) {
                    $errors['invalidInfoError'] = 'Invalid login information!';
                    echo json_encode(array(
                        'errors' => $errors
                    ));
                } else if(password_verify($data->password, $user->password)) {
                    if(isset($_COOKIE['loginInfo']) && !empty(isset($_COOKIE['loginInfo']))) {
                        $errors['loggedInError'] = 'You are already logged in!';
                        echo json_encode(array(
                            'errors' => $errors
                        ));
                    } else {
                        setcookie('loginInfo', $user->email, time() + 3600, '/', NULL, TRUE, TRUE);
                        $loginInfoToDisplay = $user->name . ' ' . $user->surname . '(' . $user->type . ')';
                        setrawcookie('loginInfoToDisplay', $loginInfoToDisplay, time() + 3600, '/', NULL, TRUE, TRUE);
    
                        echo json_encode($user, JSON_UNESCAPED_UNICODE);
                    }
                } else {
                    $errors['loginError'] = 'Invalid login information!';
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
            $errors['missingCredentialsError'] = 'You need to enter email and password!';
            echo json_encode(array(
                'errors' => $errors
            ));
        }
    }