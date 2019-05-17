<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    function logout($user) {
        if(isset($_COOKIE['loggedUserEmail']) && !empty(isset($_COOKIE['loggedUserEmail']))) {
            $errors = array();

            $user->findUserByEmail($_COOKIE['loggedUserEmail']);

            if($user->roleName == 'Admin' || $user->roleName == 'User') {
                setcookie('loggedUserEmail', '', time() - 3600, '/');
                setcookie('loggedUserInfo', '', time() - 3600, '/');

                echo json_encode($user, JSON_UNESCAPED_UNICODE);
            } else {
                $errors['unauthorizedUserError'] = 'Cannot log out! User is unauthorized!';
                echo json_encode(array(
                    'errors' => $errors
                ));
            }
        } else {
            $errors['loginError'] = 'No user is logged in!';
            echo json_encode(array(
                'errors' => $errors
            ));
        }
    }