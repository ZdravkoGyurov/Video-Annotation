<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: '.
    'Access-Control-Allow-Headers, Content-Type, '.
    'Access-Control-Allow-Methods, Authorization, X-Requested-With');

    function register($user) {
        $data = json_decode(file_get_contents('php://input'));

        if($data->email != '' && $data->name != '' && $data->surname != '' && $data->password != '' && $data->passwordRepeat != '') {
            $errors = array();
            
            Validator::validateEmail($data->email, $errors);
            Validator::validateName($data->name, $errors);
            Validator::validateSurname($data->surname, $errors);
            Validator::validatePassword($data->password, $errors);
            Validator::validatePasswordRepeat($data->passwordRepeat, $errors);
            Validator::validatePasswordMatch($data->password, $data->passwordRepeat, $errors);

            if(empty($errors)) {
                define("email", $data->email);
                define("name", $data->name);
                define("surname", $data->surname);
                define("password", password_hash($data->password, PASSWORD_DEFAULT));
                define("roleId", 2);

                if($user->createUser(email, name, surname, password, roleId)) {
                    echo json_encode($user, JSON_UNESCAPED_UNICODE);
                } else {
                    $errors['userExistsError'] = 'That email address has already been used!';
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
            $errors['dataIncompleteError'] = 'Register failed! Data is incomplete!';
            echo json_encode(array(
                'errors' => $errors
            ));
        }
    }