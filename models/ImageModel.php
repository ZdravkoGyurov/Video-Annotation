<?php
    class ImageModel {
        private $connection;
        private $table = 'image';

        public $id;
        public $path;
        public $name;
        public $type;
        public $timestamp;
        public $annotation;
        public $video_id;
        
        public function __construct($connection) {
            $this->connection = $connection;
        }

        public function createImage() {

        }

        public function findImageByVideoAndTimestamp() {

        }

        public function findAllImagesByVideo() {
            
        }

        public function deleteImage() {

        }
    }