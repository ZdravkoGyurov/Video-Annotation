<?php
    class Subtitle {
        private $connection;
        private $table = 'subtitle';

        public $id;
        public $path;
        public $name;
        public $type;
        public $videoId;
        public $videoName;

        public function __construct($connection) {
            $this->connection = $connection;
        }
        
        public function createSubtitle($path, $name, $type, $videoId) {
            $query = "INSERT INTO ". $this->table ." SET path=:path, name=:name, type=:type, video_id=:video_id";

            $statement = $this->connection->prepare($query);

            $this->path = htmlspecialchars(strip_tags($path));
            $this->name = htmlspecialchars(strip_tags($name));
            $this->type = htmlspecialchars(strip_tags($type));
            $this->videoId = htmlspecialchars(strip_tags($videoId));

            $statement->bindParam(":path", $this->path);
            $statement->bindParam(":name", $this->name);
            $statement->bindParam(":type", $this->type);
            $statement->bindParam(":video_id", $this->videoId);

            try {
                $statement->execute();
                return true;
            } catch(PDOException $e) {
                return false;
            }
        }

        public function findSubtitleByVideoName($videoName) {
            $query = "SELECT s.id, s.path, s.name, s.type, s.video_id, v.name as video_name 
                        FROM video v JOIN ". $this->table ." s ON v.id = s.video_id
                        WHERE v.name = :video_name";

            $statement = $this->connection->prepare($query);

            $statement->bindParam(":video_name", $videoName);

            $statement->execute();

            $row = $statement->fetch(PDO::FETCH_ASSOC);

            $this->id = $row['id'];
            $this->path = $row['path'];
            $this->name = $row['name'];
            $this->type = $row['type'];
            $this->videoId = $row['video_id'];
            $this->videoName = $row['video_name'];

            return $this;
        }

        public function deleteSubtitle($videoId) {
            $query = "DELETE FROM ". $this->table ." WHERE video_id=:video_id";
            
            $statement = $this->connection->prepare($query);

            $this->videoId = htmlspecialchars(strip_tags($videoId));

            $statement->bindParam(":video_id", $this->videoId);
            
            try {
                $statement->execute();
                return true;
            } catch(PDOException $e) {
                return false;
            }
        }
    }
