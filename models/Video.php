<?php
    class Video {
        private $connection;
        private $table = "video";

        public $id;
        public $path;
        public $name;
        public $type;
        public $userId;

        public function __construct($connection) {
            $this->connection = $connection;
        }

        public function createVideo($path, $name, $type, $userId) {
            $query = "INSERT INTO ". $this->table ." SET path=:path, name=:name, type=:type, user_id=:user_id";

            $statement = $this->connection->prepare($query);

            $this->path = htmlspecialchars(strip_tags($path));
            $this->name = htmlspecialchars(strip_tags($name));
            $this->type = htmlspecialchars(strip_tags($type));
            $this->userId = htmlspecialchars(strip_tags($userId));
            
            $statement->bindParam(":path", $this->path);
            $statement->bindParam(":name", $this->name);
            $statement->bindParam(":type", $this->type);
            $statement->bindParam(":user_id", $this->userId);
            
            try {
                $statement->execute();
                return true;
            } catch(PDOException $e) {
                return false;
            }
        }
        
        public function findVideoById($id) {
            $query = "SELECT id, path, name, type, user_id 
                        FROM ". $this->table ." 
                        WHERE id=:id";

            $statement = $this->connection->prepare($query);

            $this->id = htmlspecialchars(strip_tags($id));

            $statement->bindParam(":id", $this->id);

            $statement->execute();

            $row = $statement->fetch(PDO::FETCH_ASSOC);

            $this->id = $row['id'];
            $this->path = $row['path'];
            $this->name = $row['name'];
            $this->type = $row['type'];
            $this->userId = $row['user_id'];

            return $this;
        }

        public function findVideoByName($name) {
            $query = "SELECT id, path, name, type, user_id 
                        FROM ". $this->table ." 
                        WHERE name=:name";

            $statement = $this->connection->prepare($query);

            $this->name = htmlspecialchars(strip_tags($name));

            $statement->bindParam(":name", $this->name);

            $statement->execute();

            $row = $statement->fetch(PDO::FETCH_ASSOC);

            $this->id = $row['id'];
            $this->path = $row['path'];
            $this->name = $row['name'];
            $this->type = $row['type'];
            $this->userId = $row['user_id'];

            return $this;
        }

        public function findAllVideosByUserId($userId) {
            $query = "SELECT id, path, name, type, user_id 
                        FROM ". $this->table ." 
                        WHERE user_id=:user_id";

            $statement = $this->connection->prepare($query);

            $this->userId = htmlspecialchars(strip_tags($userId));

            $statement->bindParam(":user_id", $this->userId);

            $statement->execute();

            return $statement;
        }

        public function findAllVideos() {
            $query = "SELECT v.id, v.path, v.name, v.type, v.user_id
                        FROM ". $this->table ." v";

            $statement = $this->connection->prepare($query);

            $statement->execute();

            return $statement;
        }

        public function deleteVideo($name) {
            $query = "DELETE FROM ". $this->table ." WHERE name=:name";

            $statement = $this->connection->prepare($query);

            $this->name = htmlspecialchars(strip_tags($name));

            $statement->bindParam(":name", $this->name);
            
            try {
                $statement->execute();
                return true;
            } catch(PDOException $e) {
                return false;
            }
        }
    }