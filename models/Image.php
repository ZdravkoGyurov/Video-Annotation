<?php
    class Image {
        private $connection;
        private $table = 'image';

        public $id;
        public $path;
        public $name;
        public $type;
        public $timestamp;
        public $annotation;
        public $videoId;
        public $videoName;
        
        public function __construct($connection) {
            $this->connection = $connection;
        }

        public function createImage($path, $name, $type, $timestamp, $annotation, $videoId) {
            $query = "INSERT INTO ". $this->table ." SET path=:path, name=:name, type=:type, timestamp=:timestamp, annotation=:annotation, video_id=:video_id";

            $statement = $this->connection->prepare($query);

            $this->path = htmlspecialchars(strip_tags($path));
            $this->name = htmlspecialchars(strip_tags($name));
            $this->type = htmlspecialchars(strip_tags($type));
            $this->timestamp = htmlspecialchars(strip_tags($timestamp));
            $this->annotation = htmlspecialchars(strip_tags($annotation));
            $this->videoId = htmlspecialchars(strip_tags($videoId));

            $statement->bindParam(":path", $this->path);
            $statement->bindParam(":name", $this->name);
            $statement->bindParam(":type", $this->type);
            $statement->bindParam(":timestamp", $this->timestamp);
            $statement->bindParam(":annotation", $this->annotation);
            $statement->bindParam(":video_id", $this->videoId);

            try {
                $statement->execute();
                return true;
            } catch(PDOException $e) {
                return false;
            }
        }

        public function findImageByVideoNameAndTimestamp($videoName, $timestamp) {
            $query = "SELECT i.id, i.path, i.name, i.type, i.timestamp, i.annotation, i.video_id, v.name as video_name 
                        FROM video v JOIN ". $this->table ." i ON v.id = i.video_id 
                        WHERE i.timestamp=:timestamp AND v.name = :video_name";
            
            $statement = $this->connection->prepare($query);
            
            $this->videoName = htmlspecialchars(strip_tags($videoName));
            $this->timestamp = htmlspecialchars(strip_tags($timestamp));

            $statement->bindParam(":video_name", $this->videoName);
            $statement->bindParam(":timestamp", $this->timestamp);

            $statement->execute();

            $row = $statement->fetch(PDO::FETCH_ASSOC);

            $this->id = $row['id'];
            $this->path = $row['path'];
            $this->name = $row['name'];
            $this->type = $row['type'];
            $this->timestamp = $row['timestamp'];
            $this->annotation = $row['annotation'];
            $this->videoId = $row['video_id'];
            $this->videoName = $row['video_name'];

            return $this;
        }

        public function findAllImagesByVideoName($videoName) {
            $query = "SELECT i.id, i.path, i.name, i.type, i.timestamp, i.annotation, i.video_id , v.name as video_name
                        FROM video v JOIN ". $this->table ." i ON v.id = i.video_id 
                        WHERE v.name = :video_name";
            
            $statement = $this->connection->prepare($query);

            $this->videoName = htmlspecialchars(strip_tags($videoName));
            
            $statement->bindParam(":video_name", $this->videoName);

            $statement->execute();

            return $statement;
        }

        public function deleteImage($videoId, $timestamp) {
            $query = "DELETE FROM ". $this->table ." WHERE timestamp=:timestamp AND video_id=:video_id";

            $statement = $this->connection->prepare($query);

            $this->videoId = htmlspecialchars(strip_tags($videoId));
            $this->timestamp = htmlspecialchars(strip_tags($timestamp));

            $statement->bindParam(":video_id", $this->videoId);
            $statement->bindParam(":timestamp", $this->timestamp);

            try {
                $statement->execute();
                return true;
            } catch(PDOException $e) {
                return false;
            }
        }
    }