DROP DATABASE `codenames`;

CREATE DATABASE `codenames` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

CREATE TABLE `users` (
  `uid` int NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  `active` BOOLEAN DEFAULT false,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username_UNIQUE` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `user_stats` (
  `uid` int NOT NULL,
  `correct_words_blue` int DEFAULT 0,
  `correct_words_red` int DEFAULT 0,
  `wrong_words_blue` int DEFAULT 0,
  `wrong_words_red` int DEFAULT 0,
  `wins_blue` int DEFAULT 0,
  `losses_blue` int DEFAULT 0,
  `wins_red` int DEFAULT 0,
  `losses_red` int DEFAULT 0,
    `black_words_blue` int DEFAULT 0,
      `black_words_red` int DEFAULT 0,

  PRIMARY KEY (`uid`),
  CONSTRAINT `player_stats_users` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `words` (
  `word` varchar(64) NOT NULL,
  PRIMARY KEY (`word`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

DELIMITER $$

CREATE TRIGGER after_users_insert
AFTER INSERT
ON users FOR EACH ROW
BEGIN
	INSERT INTO user_stats VALUES (new.uid, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
END$$

DELIMITER ;

INSERT INTO users VALUES (NULL, "Klemen Kete", "hairline", NULL);
INSERT INTO users VALUES (NULL, "Jaka Božič", "lanky", NULL);
INSERT INTO users VALUES (NULL, "Erik Poljšak", "awesome", NULL);
INSERT INTO users VALUES (NULL, "Matija Krigl", "Urbanjemojdaddy", NULL);
INSERT INTO users VALUES (NULL, "Patrik Vidic", "unibrow", NULL);

LOAD DATA INFILE '/Applications/XAMPP/xamppfiles/htdocs/codenames/src/database/words.csv' 
INTO TABLE words 
FIELDS TERMINATED BY '$' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n';
