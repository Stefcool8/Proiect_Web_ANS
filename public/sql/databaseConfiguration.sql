-- Database: web

--
-- Table structure for `user` in database `web`
--
CREATE TABLE `user` (
                        `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                        `firstName` varchar(128) NOT NULL,
                        `lastName` varchar(128) NOT NULL,
                        `username` varchar(128) NOT NULL,
                        `email` varchar(256) NOT NULL,
                        `bio` varchar(256) NOT NULL,
                        `password` varchar(256) NOT NULL,
                        `uuid` varchar(256) NOT NULL,
                        `isAdmin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for `project` in database `web`
--
CREATE TABLE `project` (
                           `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                           `name` varchar(128) NOT NULL,
                           `chart` int(11) NOT NULL,
                           `uuidUser` varchar(256) NOT NULL REFERENCES `user`(`uuid`),
                           `uuid` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for 'bar_chart' in database 'web'
--
CREATE TABLE `bar_chart` (
                             `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                             `uuidProject` varchar(256) NOT NULL REFERENCES `project`(`uuid`),
                             `bars` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for 'pie_chart' in database 'web'
--
CREATE TABLE `pie_chart` (
                             `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                             `uuidProject` varchar(256) NOT NULL REFERENCES `project`(`uuid`),
                             `slices` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for 'line_chart' in database 'web'
--
CREATE TABLE `line_chart` (
                             `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                             `uuidProject` varchar(256) NOT NULL REFERENCES `project`(`uuid`),
                             `line` int(11) NOT NULL,
                             `lineValue` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for 'map_chart' in database 'web'
--
CREATE TABLE `map_chart` (
                             `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                             `uuidProject` varchar(256) NOT NULL REFERENCES `project`(`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for 'optional_conditions' in database 'web'
--
CREATE TABLE `optional_conditions` (
                                       `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                                       `uuidProject` varchar(256) NOT NULL REFERENCES `project`(`uuid`),
                                       `optionalColumn` int(11) NOT NULL,
                                       `optionalValue` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for 'years' in database 'web'
--
CREATE TABLE `years` (
                         `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                         `uuidProject` varchar(256) NOT NULL REFERENCES `project`(`uuid`),
                         `year` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Altering tables for case sensitive
--
ALTER TABLE `user` CONVERT TO CHARACTER SET utf8 COLLATE utf8_bin;
ALTER TABLE `project` CONVERT TO CHARACTER SET utf8 COLLATE utf8_bin;
ALTER TABLE `bar_chart` CONVERT TO CHARACTER SET utf8 COLLATE utf8_bin;
ALTER TABLE `pie_chart` CONVERT TO CHARACTER SET utf8 COLLATE utf8_bin;
ALTER TABLE `line_chart` CONVERT TO CHARACTER SET utf8 COLLATE utf8_bin;
ALTER TABLE `optional_conditions` CONVERT TO CHARACTER SET utf8 COLLATE utf8_bin;
ALTER TABLE `years` CONVERT TO CHARACTER SET utf8 COLLATE utf8_bin;

--
-- Inserting data in table `user`
--
INSERT INTO `user` (`firstName`, `lastName`, `username`, `email`, `bio`, `password`, `uuid`, `isAdmin`) VALUES
                                                                                                            ('Stefan', 'Nastasiu', 'Stefcool8', 'stefan.nastasiu8@gmail.com', 'I am a cool guy', 'stefan', '123', 1),
                                                                                                            ('Nicolae', 'Martinescu', 'nicolae', 'martinescunicolaee3@gmail.com', 'I am nicu', 'nicu', '234', 0),
                                                                                                            ('Aser', 'Cobaschi', 'aser', 'cobaschiaser8@gmail.com', 'I am aser', 'aser', '345', 1);


select * from user;
select * from project;
select * from bar_chart;
select * from pie_chart;
select * from map_chart;
select * from optional_conditions;
select * from years;

delete from project where id = 9;

drop table user cascade;
drop table project cascade;
drop table bar_chart cascade;
drop table pie_chart cascade;
drop table line_chart cascade;
drop table map_chart cascade;
drop table optional_conditions cascade;
drop table years cascade;