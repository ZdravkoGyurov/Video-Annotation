<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: '.
    'Access-Control-Allow-Headers, Content-Type, '.
    'Access-Control-Allow-Methods, Authorization, X-Requested-With');

    function updatePassword($user) {
        $data = json_decode(file_get_contents('php://input'));

        if($data->email != '' && $data->password != '' && $data->passwordRepeat != '') {
            $errors = array();

            Validator::validateEmail($data->email, $errors);
            Validator::validatePassword($data->password, $errors);
            Validator::validatePasswordRepeat($data->passwordRepeat, $errors);
            Validator::validatePasswordMatch($data->passwordRepeat, $errors);

            if(empty($errors)) {
                $user->findUserByEmail($data->email);

                if(isset($user->name)) {
                    define('id', $user->id);
                    define('email', $user->email);
                    define("name", $user->name);
                    define("surname", $user->surname);
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