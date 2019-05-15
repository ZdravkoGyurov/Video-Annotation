<?php
    class SubtitleModel {
        private $connection;
        private $table = 'subtitle';

        public $id;
        public $path;
        public $name;
        public $type;
        public $videoId;

        public function __construct($connection) {
            $this->connection = $connection;
        }
        
        public function createSubtitle() {

        }

        public function findSubtitleByVideo() {

        }

        public function deleteSubtitle() {

        }
    }
