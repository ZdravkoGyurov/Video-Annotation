<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    function findAll($user) {
        $allUsers = $user->findAllusers();
        $numberOfUsers = $allUsers->rowCount();

        if(isset($_COOKIE['loggedUserEmail']) && !empty(isset($_COOKIE['loggedUserEmail']))) {
            $errors = array();

            $user->findUserByEmail($_COOKIE['loggedUserEmail']);

            if($user->roleName == 'Admin') {
                if($numberOfUsers > 0) {
                    echo json_encode($allUsers->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
                } else {
                    $errors['noUserFoundError'] = 'No users found!';
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
            $errors['noAdminError'] = 'You need to be logged in as Admin to see all registered users!';
            echo json_encode(array(
                'errors' => $errors
            ));
        }
    }