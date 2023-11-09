-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Čtv 09. lis 2023, 13:45
-- Verze serveru: 10.4.28-MariaDB
-- Verze PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `product_manager`
--
CREATE DATABASE IF NOT EXISTS `product_manager` DEFAULT CHARACTER SET utf8 COLLATE utf8_czech_ci;
USE `product_manager`;

-- --------------------------------------------------------

--
-- Struktura tabulky `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(155) NOT NULL,
  `description` text NOT NULL,
  `price` float NOT NULL,
  `image_path` varchar(155) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `price`, `image_path`) VALUES
(1, 'LEGO Harry Potter - Bradavický hrad a okolí', 'LEGO stavebnice - pro dospělé, vhodné od 18 let, řada LEGO® Harry Potter, rok uvedení 2023 - novinky, balení obsahuje 2660 dílků ', 3595, 'images/LO76419.jpg'),
(2, 'LEGO Star Wars - AT-AT', 'LEGO stavebnice - pro děti, vhodné od 10 let, řada LEGO® Star Wars, rok uvedení 2020, balení obsahuje 1267 dílků ', 999, 'images/LO75288.jpg'),
(3, 'LEGO City - Lunární vesmírná stanice', 'LEGO stavebnice - pro děti, vhodné od 6 let, řada LEGO® City, rok uvedení 2022, balení obsahuje 530 dílků      ', 1249, 'images/vesmirna_stanice.jpg'),
(4, 'LEGO Architecture - Hrad Himedži', ' LEGO stavebnice - pro dospělé, vhodné od 18 let, řada LEGO® Architecture, rok uvedení 2023 - novinky, balení obsahuje 15 dílků                            ', 3199, 'images/hrad_himedzi.jpg'),
(5, 'LEGO® Technic - Jeep® Wrangler', ' LEGO stavebnice - pro děti, vhodné od 9 let, řada LEGO® Technic, rok uvedení 2021, balení obsahuje 665 dílků ', 2499, 'images/LO42122.jpg'),
(6, 'LEGO® Minecraft® 21250 Pevnost železného golema', ' LEGO stavebnice - pro děti, vhodné od 8 let, řada LEGO® Minecraft®, rok uvedení 2023 - novinky, balení obsahuje 868 dílků', 2159, 'images/LO21250.jpg'),
(7, 'LEGO® Indiana Jones™ 77015 Chrám zlaté modly', ' LEGO stavebnice - pro dospělé, vhodné od 18 let, řada LEGO® Indiana Jones, rok uvedení 2023 - novinky, balení obsahuje 1545 dílků', 3059, 'images/LO77015.jpg'),
(8, 'LEGO® Super Mario™ 71424 Donkey Kongův dům na stromě', ' LEGO stavebnice - pro děti, vhodné od 10 let, řada LEGO® Super Mario, rok uvedení 2023 - novinky, balení obsahuje 555 dílků ', 1389, 'images/LO71424.jpg'),
(9, 'LEGO® Star Wars™ 75192 Millennium Falcon™', ' LEGO stavebnice - pro děti a dospělé, vhodné od 16 let, řada LEGO® Star Wars, rok uvedení 2017, balení obsahuje 7541 dílků ', 17499, 'images/LO75192.jpg'),
(10, 'LEGO® Architecture 21058 Velká pyramida v Gíze', 'LEGO stavebnice - pro dospělé, vhodné od 18 let, řada LEGO® Architecture, rok uvedení 2022, balení obsahuje 1476 dílků   ', 2999, 'images/LO21058.jpg');

-- --------------------------------------------------------

--
-- Struktura tabulky `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `admin` int(11) NOT NULL DEFAULT 0,
  `username` varchar(155) NOT NULL,
  `password` varchar(155) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `users`
--

INSERT INTO `users` (`user_id`, `admin`, `username`, `password`) VALUES
(1, 1, 'admin', '$2y$10$0cht1XOMNOkylF.6dYBqnOygMEFqV2OFjs58Lp0HHsASi2QfPs8nu'),
(5, 0, 'tomas', '$2y$10$LQU9Grk/VrYZLCOeU2l.YuVpqY22TustaJqHlcrM9YYebFmWb8G56');

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);
ALTER TABLE `products` ADD FULLTEXT KEY `search` (`name`,`description`);

--
-- Indexy pro tabulku `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pro tabulku `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
