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
            } else if(mb_strlen($name) > 100) {
                $errors['nameError'] = 'Name is too long';
            }
        }
        
        public static function validateSurname($surname, &$errors) {
            $pattern = '/^[A-Za-z\x{00C0}-\x{00FF}][A-Za-z\x{00C0}-\x{00FF}\'\-]+([\ A-Za-z\x{00C0}-\x{00FF}][A-Za-z\x{00C0}-\x{00FF}\'\-]+)*/u';
            if(!preg_match($pattern, $surname)) {
                $errors['surnameError'] = 'Surname contains forbidden symblols';
            } else if(mb_strlen($surname) > 100) {
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
    }