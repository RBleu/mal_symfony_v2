-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 21 jan. 2022 à 13:59
-- Version du serveur : 10.4.20-MariaDB
-- Version de PHP : 8.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `mal_symfony`
--

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20220121091423', '2022-01-21 10:30:37', 224),
('DoctrineMigrations\\Version20220121094026', '2022-01-21 10:46:54', 134),
('DoctrineMigrations\\Version20220121100459', '2022-01-21 11:11:44', 204);

-- --------------------------------------------------------

--
-- Structure de la table `ms_anime`
--

CREATE TABLE `ms_anime` (
  `a_id` int(11) NOT NULL,
  `a_type_id` int(11) NOT NULL,
  `a_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `a_episodes` int(11) DEFAULT NULL,
  `a_airing` smallint(6) NOT NULL,
  `a_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `a_aired_from` date DEFAULT NULL,
  `a_aired_to` date DEFAULT NULL,
  `a_aired` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a_duration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a_score` double DEFAULT NULL,
  `a_scored_by` int(11) DEFAULT NULL,
  `a_rank` int(11) DEFAULT NULL,
  `a_synopsis` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a_premiered` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a_cover` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a_members` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ms_anime_genre`
--

CREATE TABLE `ms_anime_genre` (
  `ag_anime_id` int(11) NOT NULL,
  `ag_genre_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ms_anime_relation`
--

CREATE TABLE `ms_anime_relation` (
  `ar_prequel_id` int(11) NOT NULL,
  `ar_sequel_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ms_genre`
--

CREATE TABLE `ms_genre` (
  `g_id` int(11) NOT NULL,
  `g_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ms_list_type`
--

CREATE TABLE `ms_list_type` (
  `lt_id` int(11) NOT NULL,
  `lt_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lt_list_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ms_priority`
--

CREATE TABLE `ms_priority` (
  `p_id` int(11) NOT NULL,
  `p_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ms_theme`
--

CREATE TABLE `ms_theme` (
  `th_id` int(11) NOT NULL,
  `th_anime_id` int(11) NOT NULL,
  `th_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `th_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ms_type`
--

CREATE TABLE `ms_type` (
  `ty_id` int(11) NOT NULL,
  `ty_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ms_user`
--

CREATE TABLE `ms_user` (
  `u_id` int(11) NOT NULL,
  `u_username` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `u_roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `u_password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `u_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `u_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `u_signup_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `ms_user`
--

INSERT INTO `ms_user` (`u_id`, `u_username`, `u_roles`, `u_password`, `u_email`, `u_image`, `u_signup_date`) VALUES
(1, 'Ryuyeel', '[]', '$2y$13$PKIErHNlPBfu0Gvhgl2VQeKkEysqzI66zbeT2X9w5ShaHSC3/xecu', 'ryuyeel@gmail.com', NULL, '2022-01-21');

-- --------------------------------------------------------

--
-- Structure de la table `ms_user_list`
--

CREATE TABLE `ms_user_list` (
  `ul_user_id` int(11) NOT NULL,
  `ul_anime_id` int(11) NOT NULL,
  `ul_list_type_id` int(11) NOT NULL,
  `ul_priority_id` int(11) NOT NULL,
  `ul_score` int(11) NOT NULL,
  `ul_comment` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ul_modification_date` datetime NOT NULL,
  `ul_progress_episodes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `ms_anime`
--
ALTER TABLE `ms_anime`
  ADD PRIMARY KEY (`a_id`),
  ADD KEY `IDX_13045942C54C8C93` (`a_type_id`);

--
-- Index pour la table `ms_anime_genre`
--
ALTER TABLE `ms_anime_genre`
  ADD PRIMARY KEY (`ag_anime_id`,`ag_genre_id`),
  ADD KEY `IDX_EFF953C7794BBE89` (`ag_anime_id`),
  ADD KEY `IDX_EFF953C74296D31F` (`ag_genre_id`);

--
-- Index pour la table `ms_anime_relation`
--
ALTER TABLE `ms_anime_relation`
  ADD PRIMARY KEY (`ar_prequel_id`,`ar_sequel_id`),
  ADD KEY `IDX_7FAD397DE980FB2E` (`ar_prequel_id`),
  ADD KEY `IDX_7FAD397DF065ABA1` (`ar_sequel_id`);

--
-- Index pour la table `ms_genre`
--
ALTER TABLE `ms_genre`
  ADD PRIMARY KEY (`g_id`);

--
-- Index pour la table `ms_list_type`
--
ALTER TABLE `ms_list_type`
  ADD PRIMARY KEY (`lt_id`);

--
-- Index pour la table `ms_priority`
--
ALTER TABLE `ms_priority`
  ADD PRIMARY KEY (`p_id`);

--
-- Index pour la table `ms_theme`
--
ALTER TABLE `ms_theme`
  ADD PRIMARY KEY (`th_id`),
  ADD KEY `IDX_9775E708794BBE89` (`th_anime_id`);

--
-- Index pour la table `ms_type`
--
ALTER TABLE `ms_type`
  ADD PRIMARY KEY (`ty_id`);

--
-- Index pour la table `ms_user`
--
ALTER TABLE `ms_user`
  ADD PRIMARY KEY (`u_id`),
  ADD UNIQUE KEY `UNIQ_8D93D649F85E0677` (`u_username`);

--
-- Index pour la table `ms_user_list`
--
ALTER TABLE `ms_user_list`
  ADD PRIMARY KEY (`ul_user_id`,`ul_anime_id`),
  ADD KEY `IDX_3E49B4D1A76ED395` (`ul_user_id`),
  ADD KEY `IDX_3E49B4D1794BBE89` (`ul_anime_id`),
  ADD KEY `IDX_3E49B4D11903519E` (`ul_list_type_id`),
  ADD KEY `IDX_3E49B4D1497B19F9` (`ul_priority_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `ms_anime`
--
ALTER TABLE `ms_anime`
  MODIFY `a_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ms_genre`
--
ALTER TABLE `ms_genre`
  MODIFY `g_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ms_list_type`
--
ALTER TABLE `ms_list_type`
  MODIFY `lt_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ms_priority`
--
ALTER TABLE `ms_priority`
  MODIFY `p_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ms_theme`
--
ALTER TABLE `ms_theme`
  MODIFY `th_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ms_type`
--
ALTER TABLE `ms_type`
  MODIFY `ty_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ms_user`
--
ALTER TABLE `ms_user`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `ms_anime`
--
ALTER TABLE `ms_anime`
  ADD CONSTRAINT `FK_13045942C54C8C93` FOREIGN KEY (`a_type_id`) REFERENCES `ms_type` (`ty_id`);

--
-- Contraintes pour la table `ms_anime_genre`
--
ALTER TABLE `ms_anime_genre`
  ADD CONSTRAINT `FK_EFF953C74296D31F` FOREIGN KEY (`ag_genre_id`) REFERENCES `ms_genre` (`g_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_EFF953C7794BBE89` FOREIGN KEY (`ag_anime_id`) REFERENCES `ms_anime` (`a_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `ms_anime_relation`
--
ALTER TABLE `ms_anime_relation`
  ADD CONSTRAINT `FK_7FAD397DE980FB2E` FOREIGN KEY (`ar_prequel_id`) REFERENCES `ms_anime` (`a_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_7FAD397DF065ABA1` FOREIGN KEY (`ar_sequel_id`) REFERENCES `ms_anime` (`a_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `ms_theme`
--
ALTER TABLE `ms_theme`
  ADD CONSTRAINT `FK_9775E708794BBE89` FOREIGN KEY (`th_anime_id`) REFERENCES `ms_anime` (`a_id`);

--
-- Contraintes pour la table `ms_user_list`
--
ALTER TABLE `ms_user_list`
  ADD CONSTRAINT `FK_3E49B4D11903519E` FOREIGN KEY (`ul_list_type_id`) REFERENCES `ms_list_type` (`lt_id`),
  ADD CONSTRAINT `FK_3E49B4D1497B19F9` FOREIGN KEY (`ul_priority_id`) REFERENCES `ms_priority` (`p_id`),
  ADD CONSTRAINT `FK_3E49B4D1794BBE89` FOREIGN KEY (`ul_anime_id`) REFERENCES `ms_anime` (`a_id`),
  ADD CONSTRAINT `FK_3E49B4D1A76ED395` FOREIGN KEY (`ul_user_id`) REFERENCES `ms_user` (`u_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
