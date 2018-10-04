-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Окт 04 2018 г., 03:57
-- Версия сервера: 10.1.26-MariaDB-0+deb9u1
-- Версия PHP: 7.0.27-0+deb9u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `answers`
--

-- --------------------------------------------------------

--
-- Структура таблицы `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `login` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `admins`
--

INSERT INTO `admins` (`id`, `login`, `password`) VALUES
(1, 'admin', 'admin'),
(7, 'Natasha', 'pass');

-- --------------------------------------------------------

--
-- Структура таблицы `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `text` text NOT NULL,
  `user` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `answer` text,
  `subject_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `questions`
--

INSERT INTO `questions` (`id`, `text`, `user`, `email`, `answer`, `subject_id`, `status`, `created_at`) VALUES
(2, 'Чем кормить хомяков?                ', 'Сергей', 'test@test.ru', 'Барсучьим жиром', 4, 2, '2018-09-30'),
(6, 'Как долго собака может плавать в пруду?', 'Михаил', 'miha@il.com', 'Не очень долго', 2, 2, '2018-10-02'),
(8, 'Чем кормить барсука? ', 'Наташа', 'ddjdj@aaa.ru', 'Барсучьим жиром', 6, 2, '2018-10-02'),
(9, 'Что делать если барсук не хочет мыть лапки? ', 'Наташа', 'ddjdj@aaa.ru', 'Посадить его в тазик с водой', 6, 2, '2018-10-02'),
(17, 'Любят ли барсуки морковь? ', 'Олеся', 'aaa@jj.ru', 'Нет', 6, 2, '2018-10-02'),
(18, 'Впадают ли барсуки в спячку? ', 'Коля', 'miha@il.com', 'Да', 6, 1, '2018-10-02'),
(19, 'Почему барсуки не едят морковь? ', 'Михаил', 'miha@il.com', 'Она не вкусная', 6, 1, '2018-10-02'),
(22, 'Можно ли кошкам молоко? ', 'Коля', 'ddjdj@aaa.ru', NULL, 1, 1, '2018-10-03'),
(23, 'Можно ли кошкам рыбу? ', 'Коля', 'ddjdj@aaa.ru', NULL, 1, 1, '2018-10-03');

-- --------------------------------------------------------

--
-- Структура таблицы `themes`
--

CREATE TABLE `themes` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `themes`
--

INSERT INTO `themes` (`id`, `title`) VALUES
(1, 'Кошки'),
(2, 'Собаки'),
(4, 'Хомяки'),
(6, 'Барсуки'),
(9, 'Попугайчики');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `subject_id_2` (`subject_id`);

--
-- Индексы таблицы `themes`
--
ALTER TABLE `themes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT для таблицы `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT для таблицы `themes`
--
ALTER TABLE `themes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `themes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
