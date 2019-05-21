<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    function findAllUsers($user) {
        if(isset($_COOKIE['loggedUserEmail']) && !empty(isset($_COOKIE['loggedUserEmail']))) {
            $errors = array();
            
            $user->findUserByEmail($_COOKIE['loggedUserEmail']);
            
            if($user->roleName == 'Admin') {
                $allUsers = $user->findAllusers();
                $numberOfUsers = $allUsers->rowCount();

                if($numberOfUsers > 0) {
                    $usersArr = array();
                    $usersArr['data'] = array();

                    while($row = $allUsers->fetch(PDO::FETCH_ASSOC)) {
                        $userItem = array(
                            'id' => $row['id'],
                            'email' => $row['email'],
                            'name' => $row['name'],
                            'surname' => $row['surname'],
                            'password' => $row['password'],
                            'roleId' => $row['role_id'],
                            'roleName' => $row['role_name']
                        );
                        array_push($usersArr['data'], $userItem);
                    }
                    echo json_encode($usersArr, JSON_UNESCAPED_UNICODE);
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