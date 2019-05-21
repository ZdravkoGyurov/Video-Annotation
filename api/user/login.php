<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: '.
    'Access-Control-Allow-Headers, Content-Type, '.
    'Access-Control-Allow-Methods, Authorization, X-Requested-With');

    function login($user) {
        $data = json_decode(file_get_contents('php://input'));
        
        if($data->email != '' && $data->password != '') {
            $errors = array();

            Validator::validateEmail($data->email, $errors);
            Validator::validatePassword($data->password, $errors);

            if(empty($errors)) {
                $user->findUserByEmail($data->email);

                if(!isset($user->name)) {
                    $errors['invalidInfoError'] = 'Invalid login information!';
                    echo json_encode(array(
                        'errors' => $errors
                    ));
                } else if(password_verify($data->password, $user->password)) {
                    if(isset($_COOKIE['loggedUserEmail']) && !empty(isset($_COOKIE['loggedUserEmail']))) {
                        $errors['loggedInError'] = 'You are already logged in!';
                        echo json_encode(array(
                            'errors' => $errors
                        ));
                    } else {
                        setcookie('loggedUserEmail', $user->email, time() + 3600, '/');
                        $loggedUserName = $user->name. ' ' .$user->surname;
                        $loggedUserRole = $user->roleName;
                        setcookie('loggedUserName', $loggedUserName, time() + 3600, '/');
                        setcookie('loggedUserRole', $loggedUserRole, time() + 3600, '/');
    
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