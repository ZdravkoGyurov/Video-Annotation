<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: '.
    'Access-Control-Allow-Headers, Content-Type, '.
    'Access-Control-Allow-Methods, Authorization, X-Requested-With');

    function updatePassword($user) {
        $data = json_decode(file_get_contents('php://input'));

        if(isset($data->email) && isset($data->password) && isset($data->passwordRepeat)) {
            $errors = array();

            // validate email
            // validate password
            // validate passwordRepeat

            if(empty($errors)) {
                $user->findUserByEmail($data->email);

                if(isset($user->name)) {
                    define('id', $user->id);
                    define('email', $user->email);
                    define("name", $data->name);
                    define("surname", $data->surname);
                    define("password", password_hash($data->password, PASSWORD_DEFAULT));
                    define("roleId", 2);
    
                    if($user->updateUser(id, email, name, surname, password, roleId)) {
                        echo json_encode($user, JSON_UNESCAPED_UNICODE);
                    } else {
                        $errors['userExistsError'] = 'Updating password failed!';
                        echo json_encode(array(
                            'errors' => $errors
                        ));
                    }
                } else {
                    $errors['invalidInfoError'] = 'Invalid information!';
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
            $errors['dataIncompleteError'] = 'Updating password failed! Data is incomplete!';
            echo json_encode(array(
                'errors' => $errors
            ));
        }
    }