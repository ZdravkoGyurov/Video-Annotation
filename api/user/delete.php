<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: '.
    'Access-Control-Allow-Headers, Content-Type, '.
    'Access-Control-Allow-Methods, Authorization, X-Requested-With');

    function deleteUser($user, $email) {
        if(isset($_COOKIE['loggedUserEmail']) && !empty(isset($_COOKIE['loggedUserEmail']))) {
            $errors = array();

            $user->findUserByEmail($_COOKIE['loggedUserEmail']);

            if($user->roleName == 'Admin') {
                // validate email

                if(empty($errors)) {
                    $user->findUserByEmail($email);

                    if($user->roleName != 'Admin') {
                        if(!isset($user->name)) {
                            $errors['noSuchUserFoundError'] = 'No user with given email found!';
                            echo json_encode(array(
                                'errors' => $errors
                            ));
                        } else if($user->deleteUser($email)) {
                            echo json_encode($user, JSON_UNESCAPED_UNICODE);
                        } else {
                            $errors['unableToDeleteUserError'] = 'User could not be deleted!';
                            echo json_encode(array(
                                'errors' => $errors
                            ));
                        }
                    } else {
                        $errors['adminError'] = 'Cannot delete admin user!';
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
            $errors['noAdminError'] = 'You need to be logged in as Admin to delete users!';
            echo json_encode(array(
                'errors' => $errors
            ));
        }
    }