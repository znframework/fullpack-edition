-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 24 Eki 2017, 18:54:36
-- Sunucu sürümü: 10.1.26-MariaDB
-- PHP Sürümü: 7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `test`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `gallery_categories`
--

CREATE TABLE `gallery_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(250) DEFAULT NULL,
  `date` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `image` varchar(250) DEFAULT NULL,
  `date` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `menus`
--

CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `submenu_id` int(11) DEFAULT NULL,
  `name` varchar(250) DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `url` varchar(500) DEFAULT NULL,
  `active` int(1) DEFAULT '1',
  `order_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Tablo döküm verisi `menus`
--

INSERT INTO `menus` (`id`, `submenu_id`, `name`, `icon`, `url`, `active`, `order_id`) VALUES
(1, 0, 'dashboard', 'fa-home', 'home/main', 1, 1),
(2, 0, 'users', 'fa-user', '#', 1, 2),
(3, 0, 'permissions', 'fa-cog', 'permissions/main', 1, 3),
(4, 0, 'languages', 'fa-language', 'languages/main', 1, 4),
(5, 0, 'gallery', 'fa-photo', '#', 1, 5),
(6, 2, 'create', '', 'users/create', 1, 1),
(7, 2, 'list', '', 'users/list', 1, 2),
(8, 5, 'create', '', 'gallery/create', 1, 1),
(9, 5, 'upload', '', 'gallery/upload', 1, 2),
(10, 5, 'list', '', 'gallery/list', 1, 3);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `migrations`
--

CREATE TABLE `migrations` (
  `name` varchar(512) NOT NULL,
  `type` varchar(256) NOT NULL,
  `version` varchar(3) NOT NULL,
  `date` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Tablo döküm verisi `migrations`
--

INSERT INTO `migrations` (`name`, `type`, `version`, `date`) VALUES
('users', 'createTable', '000', '20171024075417'),
('user_activities', 'createTable', '000', '20171024075417'),
('gallery_categories', 'createTable', '000', '20171024075417'),
('images', 'createTable', '000', '20171024075417'),
('permissions', 'createTable', '000', '20171024075417'),
('menus', 'createTable', '000', '20171024075417');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `type` varchar(10) DEFAULT NULL,
  `rules` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Tablo döküm verisi `permissions`
--

INSERT INTO `permissions` (`id`, `type`, `rules`) VALUES
(1, 'perm', 'all'),
(2, 'noperm', 'editButton|deleteButton'),
(3, 'noperm', 'permissions|editButton|deleteButton');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(250) DEFAULT NULL,
  `name` varchar(250) DEFAULT NULL,
  `password` varchar(64) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `birthdate` datetime DEFAULT NULL,
  `about` text,
  `website` varchar(300) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `ip` varchar(64) DEFAULT NULL,
  `mobile` varchar(30) DEFAULT NULL,
  `address` text,
  `photo` varchar(255) DEFAULT NULL,
  `date` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `email`, `name`, `password`, `gender`, `birthdate`, `about`, `website`, `role_id`, `ip`, `mobile`, `address`, `photo`, `date`) VALUES
(1, 'admin@powerpack.com', 'Admin', 'fddc66ad97cb3a54242d0e8e7a085540', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '2017-10-24 19:54:17');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `user_activities`
--

CREATE TABLE `user_activities` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `activity` varchar(250) DEFAULT NULL,
  `date` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `gallery_categories`
--
ALTER TABLE `gallery_categories`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `user_activities`
--
ALTER TABLE `user_activities`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `gallery_categories`
--
ALTER TABLE `gallery_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Tablo için AUTO_INCREMENT değeri `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `user_activities`
--
ALTER TABLE `user_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
