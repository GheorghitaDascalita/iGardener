-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Gazdă: 127.0.0.1
-- Timp de generare: sept. 11, 2022 la 10:51 PM
-- Versiune server: 10.4.14-MariaDB
-- Versiune PHP: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Bază de date: `igardener`
--

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `buyers`
--

CREATE TABLE `buyers` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(20) NOT NULL,
  `address` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Eliminarea datelor din tabel `buyers`
--

INSERT INTO `buyers` (`id`, `username`, `password`, `email`, `address`) VALUES
(1, 'buyer1', 'ec5c9b91242f18e1ac487661d8b3ea4206701e0e', 'buyer1@gmail.com', NULL);

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `crops`
--

CREATE TABLE `crops` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `price` int(11) NOT NULL,
  `image` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Eliminarea datelor din tabel `crops`
--

INSERT INTO `crops` (`id`, `name`, `price`, `image`) VALUES
(1, 'Tulips', 10, 'tulips.jpg'),
(2, 'Daffodils', 30, 'daffodils.jpg'),
(3, 'Hyacinths', 20, 'hyacinths.jpg'),
(4, 'Snowdrops', 15, 'snowdrops.jpg'),
(5, 'Anemone', 25, 'anemone.jpg'),
(6, 'Freesias', 10, 'freesias.jpg'),
(7, 'Geraniums', 20, 'geraniums.jpg');

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `grow_crops`
--

CREATE TABLE `grow_crops` (
  `id` int(11) NOT NULL,
  `id_seller` int(11) NOT NULL,
  `id_crop` int(11) NOT NULL,
  `owned_quantity` int(11) NOT NULL,
  `sold_quantity` int(11) NOT NULL,
  `recent_watering` datetime NOT NULL DEFAULT current_timestamp(),
  `recent_temp_checking` date NOT NULL,
  `growing_level` int(11) NOT NULL,
  `image` varchar(50) NOT NULL,
  `humidity` int(11) NOT NULL,
  `temperature` int(11) NOT NULL,
  `ready` tinyint(1) NOT NULL,
  `harvested` tinyint(1) NOT NULL,
  `sold` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Eliminarea datelor din tabel `grow_crops`
--

INSERT INTO `grow_crops` (`id`, `id_seller`, `id_crop`, `owned_quantity`, `sold_quantity`, `recent_watering`, `recent_temp_checking`, `growing_level`, `image`, `humidity`, `temperature`, `ready`, `harvested`, `sold`) VALUES
(1, 1, 4, 10, 10, '2022-09-11 23:25:27', '2022-09-11', 4, 'snowdrops', 0, 36, 1, 1, 1),
(2, 1, 2, 0, 10, '2022-09-11 23:22:50', '2022-09-11', 4, 'daffodils', 0, 7, 1, 1, 1),
(3, 1, 1, 10, 0, '2022-09-11 23:25:23', '2022-09-11', 0, 'tulips', 0, 13, 0, 0, 0);

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `id_buyer` int(11) NOT NULL,
  `id_crop` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` varchar(20) NOT NULL,
  `bought` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Eliminarea datelor din tabel `orders`
--

INSERT INTO `orders` (`id`, `id_buyer`, `id_crop`, `quantity`, `date`, `status`, `bought`) VALUES
(1, 1, 2, 10, '2022-09-11', 'available', 1),
(2, 1, 4, 10, '2022-09-11', 'available', 1),
(3, 1, 1, 5, '2022-09-11', 'unavailable', 0);

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `sellers`
--

CREATE TABLE `sellers` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Eliminarea datelor din tabel `sellers`
--

INSERT INTO `sellers` (`id`, `username`, `password`, `email`) VALUES
(1, 'seller1', 'c46d1098df4be876c60e070b66c52b598867bc29', 'seller1@gmail.com'),
(2, 'seller2', 'e1aebd011e46c2cc90643cf88b2f59645722957e', 'seller2@gmail.com');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
