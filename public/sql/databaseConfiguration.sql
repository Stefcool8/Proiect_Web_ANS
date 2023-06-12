-- Database: web

--
-- Table structure for `user` in database `web`
--
CREATE TABLE `user` (
    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `username` varchar(128) NOT NULL,
    `password` varchar(256) NOT NULL,
    `uuid` varchar(256) NOT NULL,
    `name` varchar(32) NOT NULL,
    `email` varchar(256) NOT NULL,
    `isAdmin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Inserting data in table `user`
--
INSERT INTO `user` (`username`, `password`, `uuid`, `name`, `email`, `isAdmin`) VALUES
    ('admin', 'admin', 'admin', 'admin', 'stefan.nastasiu8@gmail.com', 1),
    ('nicolae', 'nicolae', 'nicolae', 'Martinescu Nicolae', 'martinescunicolaee3@gmail.com', 0);

select * from user;
