CREATE TABLE `project` (
    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` varchar(128) NOT NULL,
    `chart` varchar(128) NOT NULL,
    `uuidUser` varchar(256) NOT NULL,
    `uuid` varchar(256) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
--
-- Inserting data in table `user`
--
INSERT INTO `project` (`name`, `chart`, `uuidUser`,`uuid`) VALUES
    ("aser's Project", 'Bar Chart', '648a329a91c94','project1'),
    ("emanuel's Project", 'Pie Chart', '648a32d0586e9','project2');