<?php
    class UserModel {
        private $connection;
        private $table = 'user';

        public $id;
        public $email;
        public $name;
        public $surname;
        public $password;
        public $roleId;
        public $roleName;

        public function __construct($connection) {
            $this->connection = $connection;
        }

        public function createUser() {
            $query = "INSERT INTO ". $this->table ." SET email=:email, name=:name, surname=:surname, password=:password, role_id=:role_id";

            $statement = $this->connection->prepare($query);

            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->surname = htmlspecialchars(strip_tags($this->surname));
            $this->password = htmlspecialchars(strip_tags($this->password));
            $this->roleId = htmlspecialchars(strip_tags($this->roleId));
            $this->roleName = htmlspecialchars(strip_tags($this->roleName));
                                    
            $statement->bindParam(":email", $this->email);
            $statement->bindParam(":name", $this->name);
            $statement->bindParam(":surname", $this->surname);
            $statement->bindParam(":password", $this->password);
            $statement->bindParam(":role_id", $this->roleId);
            
            try {
                $statement->execute();
                return true;
            } catch(PDOException $e) {
                return false;
            }
        }

        public function updateUser() {
            $query = "UPDATE ". $this->table ." 
                        SET id=:id, email=:email, name=:name, surname=:surname, password=:password, role_id=:role_id
                        WHERE email=:email";

            $statement = $this->connection->prepare($query);

            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->surname = htmlspecialchars(strip_tags($this->surname));
            $this->password = htmlspecialchars(strip_tags($this->password));
            $this->roleId = htmlspecialchars(strip_tags($this->roleId));
            $this->roleName = htmlspecialchars(strip_tags($this->roleName));
                                    
            $statement->bindParam(":id", $this->id);
            $statement->bindParam(":email", $this->email);
            $statement->bindParam(":name", $this->name);
            $statement->bindParam(":surname", $this->surname);
            $statement->bindParam(":password", $this->password);
            $statement->bindParam(":role_id", $this->roleId);
            
            try {
                $statement->execute();
                return true;
            } catch(PDOException $e) {
                return false;
            }
        }

        public function findUserByEmail() {
            $query = 'SELECT u.id, u.email, u.name, u.surname, u.password, u.role_id, r.name as role_name
            FROM role r JOIN ' . $this->table . ' u ON r.id = u.role_id WHERE u.email =:email';

            $statement = $this->connection->prepare($query);

            $statement->bindParam(":email", $this->email);

            $statement->execute();

            $row = $statement->fetch(PDO::FETCH_ASSOC);

            $this->id = $row['id'];
            $this->email = $row['email'];
            $this->name = $row['name'];
            $this->surname = $row['surname'];
            $this->password = $row['password'];
            $this->roleId = $row['role_id'];
            $this->roleName = $row['role_name'];
            
            return $this;
        }

        public function findAllUsers() {
            $query = 'SELECT u.id, u.email, u.name, u.surname, u.password, u.role_id, r.name as role_name
            FROM role r JOIN ' . $this->table . ' u ON r.id = u.role_id';

            $statement = $this->connection->prepare($query);

            $statement->execute();

            return $statement;
        }

        public function removeUser() {
            $query = "DELETE FROM ". $this->table ." WHERE email=:email";

            $statement = $this->connection->prepare($query);

            $this->email = htmlspecialchars(strip_tags($this->email));

            $statement->bindParam(":email", $this->email);

            try {
                $statement->execute();
                return true;
            } catch(PDOException $e) {
                return false;
            }
        }
    }
