DROP TABLE IF EXISTS video_annotation.subtitle;
DROP TABLE IF EXISTS video_annotation.image;
DROP TABLE IF EXISTS video_annotation.video;
DROP TABLE IF EXISTS video_annotation.user;
DROP TABLE IF EXISTS video_annotation.role;
DROP DATABASE IF EXISTS video_annotation;

CREATE DATABASE video_annotation CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE video_annotation.role (
	id INT NOT NULL AUTO_INCREMENT,
	name VARCHAR(255) NOT NULL UNIQUE,
	PRIMARY KEY (id)
);

CREATE TABLE video_annotation.user (
	id INT NOT NULL AUTO_INCREMENT,
	email VARCHAR(255) NOT NULL UNIQUE,
	name VARCHAR(100) CHARACTER SET utf8 NOT NULL,
	surname VARCHAR(100) CHARACTER SET utf8 NOT NULL,
	password VARCHAR(2056) NOT NULL,
	role_id INT NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (role_id) REFERENCES role(id)
);

CREATE TABLE video_annotation.video(
	id INT NOT NULL AUTO_INCREMENT,
	path VARCHAR(510) NOT NULL,
	name VARCHAR(255) NOT NULL UNIQUE,
	type VARCHAR(255) NOT NULL,
	user_id INT NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (user_id) REFERENCES user(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE video_annotation.image(
	id INT NOT NULL AUTO_INCREMENT,
	path VARCHAR(510) NOT NULL,
	name VARCHAR(255) NOT NULL UNIQUE,
	type VARCHAR(255) NOT NULL,
	timestamp INT NOT NULL,
	annotation VARCHAR(255) NOT NULL,
	video_id INT NOT NULL,
	PRIMARY KEY(id),
	FOREIGN KEY (video_id) REFERENCES video(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE video_annotation.subtitle(
	id INT NOT NULL AUTO_INCREMENT,
	path VARCHAR(510) NOT NULL,
	name VARCHAR(255) NOT NULL UNIQUE,
	type VARCHAR(255) NOT NULL,
	video_id INT NOT NULL UNIQUE,
	PRIMARY KEY (id),
	FOREIGN KEY (video_id) REFERENCES video(id) ON UPDATE CASCADE ON DELETE CASCADE
);

INSERT INTO video_annotation.role(name) VALUES('Admin');
INSERT INTO video_annotation.role(name) VALUES('User');
INSERT INTO video_annotation.user(email, name, surname, password, role_id) VALUES('zdravko.gyurov97@gmail.com', N'Здравко', N'Гюров', '$2y$10$LLyr9O/5n9.MJtGYMCltUerYIO8pbfzWYBJxT9EbCnIqF84PIJqLa', '1');
INSERT INTO video_annotation.video(path, name, type, user_id) VALUES('video_path', 'video_name', 'video_type', '1');
INSERT INTO video_annotation.image(path, name, type, timestamp, annotation, video_id) VALUES('image_path', 'image_name', 'image_type', '60', 'image_annotation', '1');
INSERT INTO video_annotation.subtitle(path, name, type, video_id) VALUES('subtitle_path', 'subtitle_name', 'subtitle_type', '1');