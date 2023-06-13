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
drop table user;
--
-- Inserting data in table `user`
--
INSERT INTO `user` (`firstName`, `lastName`, `username`, `email`, `bio`, `password`, `uuid`, `isAdmin`) VALUES
    ('Stefan', 'Nastasiu', 'Stefcool8', 'stefan.nastasiu8@gmail.com', 'I am a cool guy', 'stefan', '123', 1),
    ('Nicolae', 'Martinescu', 'nicolae', 'martinescunicolaee3@gmail.com', 'I am nicu', 'nicu', '234', 0),
    ('Aser', 'Cobaschi', 'aser', 'cobaschiaser8@gmail.com', 'I am aser', 'aser', '345', 0);

select * from user;
