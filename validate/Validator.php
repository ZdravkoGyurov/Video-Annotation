<?php
    class Validator {
        public static function validateEmail($email, &$errors) {
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['emailError'] = 'Email is invalid';
            } else if(strlen($email) > 255) {
                $errors['emailError'] = 'Email is too long';
            }
        }

        public static function validateName($name, &$errors) {
            $pattern = '/^[A-Za-z\x{00C0}-\x{00FF}][A-Za-z\x{00C0}-\x{00FF}\'\-]+([\ A-Za-z\x{00C0}-\x{00FF}][A-Za-z\x{00C0}-\x{00FF}\'\-]+)*/u';
            if(!preg_match($pattern, $name)) {
                $errors['nameError'] = 'Name contains forbidden symblols';
            } else if(strlen($name) > 100) {
                $errors['nameError'] = 'Name is too long';
            }
        }
        
        public static function validateSurname($surname, &$errors) {
            $pattern = '/^[A-Za-z\x{00C0}-\x{00FF}][A-Za-z\x{00C0}-\x{00FF}\'\-]+([\ A-Za-z\x{00C0}-\x{00FF}][A-Za-z\x{00C0}-\x{00FF}\'\-]+)*/u';
            if(!preg_match($pattern, $surname)) {
                $errors['surnameError'] = 'Surname contains forbidden symblols';
            } else if(strlen($surname) > 100) {
                $errors['surnameError'] = 'Surname is too long';
            }
        }

        public static function validatePassword($password, &$errors) {
            if(strlen($password) <= 0) {
                $errors['passwordError'] = 'Password is invalid';
            }
        }

        public static function validatePasswordRepeat($passwordRepeat, &$errors) {
            if(strlen($passwordRepeat) <= 0) {
                $errors['passwordRepeatError'] = 'Password repeat is invalid';
            }
        }

        public static function validatePasswordMatch($password, $passwordRepeat, &$errors) {
            if($password != $passwordRepeat) {
                $errors['passwordMatchError'] = 'Passwords do not match';
            }
        }

        public static function validateVideoName($videoName, &$errors) {
            if(!ctype_alnum($videoName)) {
                $errors['videoNameError'] = 'Video can contain only letters and numbers';
            }
        }

        public static function validateVideoId($videoId, &$errors) {
            if(!is_numeric($videoId)) {
                $errors['videoIdError'] = 'Video id can contain only numbers';
            }
        }

        public static function validateVideoTimestamp($timestamp, &$errors) {
            if(!is_numeric($timestamp)) {
                $errors['videoTimestampError'] = 'Timestamp can contain only numbers';
            }
        }
    }