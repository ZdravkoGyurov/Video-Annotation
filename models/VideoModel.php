<?php
    class VideoModel {
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

        public function createVideo() {
            $query = "INSERT INTO ". $this->table ." SET path=:path, name=:name, type=:type, user_id=:user_id";

            $statement = $this->connection->prepare($query);

            $this->path = htmlspecialchars(strip_tags($this->path));
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->type = htmlspecialchars(strip_tags($this->type));
            $this->userId = htmlspecialchars(strip_tags($this->userId));
            
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

        public function findVideoByName() {

        }

        public function findAllVideosByUser() {

        }

        public function findAllVideos() {

        }

        public function deleteVideo() {

        }
    }