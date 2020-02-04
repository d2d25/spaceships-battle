-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : Dim 02 fév. 2020 à 19:39
-- Version du serveur :  5.7.24
-- Version de PHP : 7.2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `spaceships_battle`
--

-- --------------------------------------------------------

--
-- Structure de la table `joueurs`
--

CREATE TABLE `joueurs` (
  `idJoueur` smallint(6) NOT NULL,
  `loginJoueur` char(30) NOT NULL,
  `motPasse` tinytext NOT NULL,
  `argent` mediumint(8) UNSIGNED NOT NULL DEFAULT '1000',
  `niveau` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `experience` tinyint(4) NOT NULL DEFAULT '0',
  `nbPointsReparation` smallint(5) UNSIGNED NOT NULL DEFAULT '100',
  `dateDerniereConnexion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nbConnexionSuite` tinyint(3) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `joueurs`
--

INSERT INTO `joueurs` (`idJoueur`, `loginJoueur`, `motPasse`, `argent`, `niveau`, `experience`, `nbPointsReparation`, `dateDerniereConnexion`, `nbConnexionSuite`) VALUES
(25, 'quentin1', '$2y$12$/KH6hGkA4EgEvGeBgjrt1.A0UVCyA6Zlka5Vxy5BZW4M4NdBej9jC', 500, 1, 0, 100, '2020-02-01 10:52:05', 1),
(26, 'quentin2', '$2y$12$KKwilw4EAsuXMswRFHEoX.Yr1IE.jA5Hq1/XEe6cQSs94Pw/.2vYC', 500, 1, 0, 100, '2020-02-02 11:43:48', 1),
(27, 'quentin3', '$2y$12$2.p6zZdb9UF2u9jwsnmrJuvOnmH/l/pxtddXxQK3aeIWl3q92ZndK', 500, 1, 0, 100, '2020-02-02 11:44:47', 1);

-- --------------------------------------------------------

--
-- Structure de la table `joueurs_vaisseaux`
--

CREATE TABLE `joueurs_vaisseaux` (
  `id_joueur_vaisseau` smallint(6) NOT NULL,
  `idJoueur` smallint(6) NOT NULL,
  `idVaisseau` tinyint(4) NOT NULL,
  `nbVictoires` smallint(6) NOT NULL DEFAULT '0',
  `nbDefaites` smallint(6) NOT NULL DEFAULT '0',
  `coutReparation` int(11) NOT NULL DEFAULT '150',
  `dommages` smallint(6) NOT NULL DEFAULT '0',
  `possede` tinyint(1) NOT NULL DEFAULT '0',
  `disponibleAchat` tinyint(1) NOT NULL DEFAULT '1',
  `activite` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `joueurs_vaisseaux`
--

INSERT INTO `joueurs_vaisseaux` (`id_joueur_vaisseau`, `idJoueur`, `idVaisseau`, `nbVictoires`, `nbDefaites`, `coutReparation`, `dommages`, `possede`, `disponibleAchat`, `activite`) VALUES
(22, 25, 4, 0, 0, 150, 0, 1, 1, 1),
(23, 25, 5, 0, 0, 150, 0, 0, 0, 0),
(24, 25, 6, 0, 0, 150, 0, 0, 0, 0),
(25, 26, 4, 0, 0, 150, 0, 0, 0, 0),
(26, 26, 5, 0, 0, 150, 0, 0, 0, 0),
(27, 26, 6, 0, 0, 150, 0, 1, 1, 1),
(28, 27, 4, 0, 0, 150, 0, 0, 0, 0),
(29, 27, 5, 0, 0, 150, 0, 1, 1, 0),
(30, 27, 6, 0, 0, 150, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `pouvoirs`
--

CREATE TABLE `pouvoirs` (
  `idPouvoir` tinyint(4) NOT NULL,
  `nomPouvoir` char(30) NOT NULL,
  `detail` char(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `types`
--

CREATE TABLE `types` (
  `idType` tinyint(4) NOT NULL,
  `nomType` char(30) NOT NULL,
  `detail` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `types`
--

INSERT INTO `types` (`idType`, `nomType`, `detail`) VALUES
(1, 'Léger', 'Les vaisseaux légers sont parfait pour les voyages à distances moyennes. \r\n    Il offrent une protection peu efficace au combat mais compensent avec leur rapidite.\r\n    La fuite est souvent l\'option la plus sûre !'),
(2, 'Lourd', 'Les vaisseaux lourds peuvent changer la tournure du combat en désintégrant l\'adversaire.\r\n    Leur tir une fois chargé peu détruire la plus solide des coques, et briser les boucliers en un coup.\r\n    Si seulement l\'armement ne s\'enrayait pas si souvent !'),
(3, 'Moyen', 'Les vaisseaux moyens offrent une résistance raisonnable au combat. Leur puissance de \r\n    feu leur permet de percer les bouclier ennemis. Presque à toute épreuve, il faut juste ne pas se \r\n    retrouver face à une armée de vaissaux légers !');

-- --------------------------------------------------------

--
-- Structure de la table `vaisseaux`
--

CREATE TABLE `vaisseaux` (
  `idVaisseau` tinyint(4) NOT NULL,
  `nomVaisseau` char(30) NOT NULL,
  `prix` mediumint(8) UNSIGNED NOT NULL,
  `rapidite` smallint(5) UNSIGNED NOT NULL,
  `attaque` smallint(5) UNSIGNED NOT NULL,
  `solidite` smallint(5) UNSIGNED NOT NULL,
  `defense` smallint(5) UNSIGNED NOT NULL,
  `bloque` tinyint(1) NOT NULL DEFAULT '1',
  `idType` tinyint(4) NOT NULL,
  `idPouvoir` tinyint(4) DEFAULT NULL,
  `lienImage` text NOT NULL,
  `niveau` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `vaisseaux`
--

INSERT INTO `vaisseaux` (`idVaisseau`, `nomVaisseau`, `prix`, `rapidite`, `attaque`, `solidite`, `defense`, `bloque`, `idType`, `idPouvoir`, `lienImage`, `niveau`) VALUES
(4, 'Douglas-Adams-I', 500, 350, 100, 150, 150, 1, 1, NULL, 'https://external-content.duckduckgo.com/iu/?u=http%3A%2F%2Fpoopss.p.o.pic.centerblog.net%2Fo%2Fe1d6c2dc.jpg&f=1&nofb=1', 1),
(5, 'Icarus-I', 500, 100, 350, 150, 150, 1, 2, NULL, 'http://medias.3dvf.com/news/making_of_tutos/3d_world/Space_modellingtips_04.jpg', 1),
(6, 'Mandrake-I', 500, 100, 150, 350, 150, 1, 3, NULL, 'https://www.vive-internet-gratuit.com/images/dessins/Vaisseau-spatial_49.jpg', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `joueurs`
--
ALTER TABLE `joueurs`
  ADD PRIMARY KEY (`idJoueur`),
  ADD UNIQUE KEY `loginJoueur` (`loginJoueur`);

--
-- Index pour la table `joueurs_vaisseaux`
--
ALTER TABLE `joueurs_vaisseaux`
  ADD PRIMARY KEY (`id_joueur_vaisseau`),
  ADD UNIQUE KEY `idJoueur` (`idJoueur`,`idVaisseau`),
  ADD KEY `idVaisseau` (`idVaisseau`);

--
-- Index pour la table `pouvoirs`
--
ALTER TABLE `pouvoirs`
  ADD PRIMARY KEY (`idPouvoir`);

--
-- Index pour la table `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`idType`);

--
-- Index pour la table `vaisseaux`
--
ALTER TABLE `vaisseaux`
  ADD PRIMARY KEY (`idVaisseau`),
  ADD KEY `idType` (`idType`),
  ADD KEY `idPouvoir` (`idPouvoir`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `joueurs`
--
ALTER TABLE `joueurs`
  MODIFY `idJoueur` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT pour la table `joueurs_vaisseaux`
--
ALTER TABLE `joueurs_vaisseaux`
  MODIFY `id_joueur_vaisseau` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `pouvoirs`
--
ALTER TABLE `pouvoirs`
  MODIFY `idPouvoir` tinyint(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `types`
--
ALTER TABLE `types`
  MODIFY `idType` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `vaisseaux`
--
ALTER TABLE `vaisseaux`
  MODIFY `idVaisseau` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `joueurs_vaisseaux`
--
ALTER TABLE `joueurs_vaisseaux`
  ADD CONSTRAINT `fk_id_joueur` FOREIGN KEY (`idJoueur`) REFERENCES `joueurs` (`idJoueur`) ON DELETE CASCADE,
  ADD CONSTRAINT `joueurs_vaisseaux_ibfk_2` FOREIGN KEY (`idVaisseau`) REFERENCES `vaisseaux` (`idVaisseau`);

--
-- Contraintes pour la table `vaisseaux`
--
ALTER TABLE `vaisseaux`
  ADD CONSTRAINT `vaisseaux_ibfk_1` FOREIGN KEY (`idType`) REFERENCES `types` (`idType`),
  ADD CONSTRAINT `vaisseaux_ibfk_2` FOREIGN KEY (`idPouvoir`) REFERENCES `pouvoirs` (`idPouvoir`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
