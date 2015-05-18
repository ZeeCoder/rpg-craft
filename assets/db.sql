-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 05, 2013 at 05:29 PM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `rpg`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `eventID` int(11) NOT NULL AUTO_INCREMENT,
  `gameID` int(11) NOT NULL,
  `label` varchar(100) COLLATE utf8_hungarian_ci NOT NULL,
  `description` varchar(5000) COLLATE utf8_hungarian_ci NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`eventID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=210 ;

--
-- Dumping data for table `events`
--
/*
INSERT INTO `events` (`eventID`, `gameID`, `label`, `description`, `last_updated`) VALUES
(1, 15, 'Teszt-event', 'Egy event csak a teszt kedvéért. Tegyük fel, hogy egy karakter leírása ez, csatolok is pár képet. ;)', '2012-07-24 16:26:35'),
(58, 15, 'Lorem Ipsum', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur ut aliquet ligula. Sed a mauris sit amet lectus porttitor sollicitudin quis consectetur nunc. Donec ornare rutrum sollicitudin. Suspendisse laoreet, sapien nec mattis ultrices, neque nibh fringilla leo, vitae porta magna velit et mi. Cras dolor libero, dapibus nec elementum quis, faucibus in diam. Suspendisse molestie, massa vel elementum ullamcorper, arcu libero consequat lorem, vel facilisis eros lectus sed metus. Vestibulum di', '2012-07-24 16:26:35'),
(51, 7, 'Lorem Ipsum', '<p style="text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans; ">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas et enim ac velit sollicitudin malesuada ac nec elit. Sed tristique tellus libero, non tempus arcu. Morbi adipiscing posuere ligula, et molestie purus vestibulum eleifend. Morbi gravida porttitor nibh, sit amet placerat quam imperdiet eu. Quisque libero nisi, gravida quis molestie sit amet, vehicula dictum augue. Proin tristique accumsan sodales. Suspendisse potenti. Donec fermentum dapibus leo, eu convallis dolor ultrices et. Ut posuere consectetur quam, in sollicitudin leo ultrices eget. In metus est, tincidunt eget lacinia at, posuere eu orci. Praesent egestas feugiat egestas.</p><p style="text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans; ">Quisque condimentum ante nec risus tincidunt vel porta erat gravida. Suspendisse potenti. Quisque quis turpis sapien. Vestibulum eget dolor dui. Pellentesque dapibus pharetra vestibulum. Nunc eu arcu ut nulla varius euismod. Aenean turpis eros, pharetra in dictum a, feugiat in odio. Etiam sodales tempus diam ut luctus. In faucibus mi a purus auctor vehicula. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Quisque non mattis nisl. Maecenas vestibulum quam nec justo semper iaculis. Pellentesque fermentum lacus non elit vestibulum accumsan. Vivamus at elit condimentum erat consectetur pellentesque. Mauris cursus purus a arcu rhoncus blandit sed et ligula. Suspendisse lobortis pretium metus sit amet ultricies.</p><p style="text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans; ">Aenean et augue urna. Suspendisse potenti. Fusce sodales condimentum dolor posuere volutpat. Vivamus justo lorem, tincidunt vitae lobortis quis, rhoncus nec mi. Ut venenatis tellus a arcu fermentum tristique. Nunc sagittis dolor sed sapien tincidunt nec lacinia nisi vestibulum. Nulla mattis pretium lacus non porta. Pellentesque accumsan fringilla ligula adipiscing laoreet. Praesent dictum, libero ut dignissim eleifend, elit nisi lobortis sapien, in convallis magna lorem nec dolor. Duis vestibulum lobortis pellentesque. Fusce ac sodales nunc. Aliquam aliquet velit quis nibh ornare ut tincidunt velit eleifend. Nunc tincidunt feugiat pharetra. Praesent ultricies, tellus a malesuada fringilla, purus turpis aliquam elit, id malesuada tellus nisi ut libero.</p><p style="text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans; ">Aenean vel augue magna. Sed purus elit, sollicitudin ut condimentum non, placerat quis leo. Morbi faucibus tristique sem, in vulputate lorem viverra quis. Curabitur pretium, sapien ac vestibulum molestie, nibh tellus lobortis tellus, id feugiat tellus sapien in justo. Nullam fringilla tristique sem. Curabitur egestas lacus vel ipsum ornare quis aliquam justo tempus. Cras bibendum nisi erat. Cras convallis tristique lectus, sodales auctor mauris tincidunt in.</p><p style="text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans; ">Aliquam consectetur volutpat blandit. Fusce tincidunt rutrum augue, imperdiet interdum enim iaculis non. Donec tristique, urna id feugiat dapibus, quam lectus ornare risus, in imperdiet lectus enim sit amet mauris. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec dignissim magna quis dolor commodo vitae mollis ligula auctor. Donec eget velit risus, in molestie nulla. Curabitur dolor neque, auctor sit amet hendrerit vel, aliquam eget augue. Mauris dictum dui eu lectus pulvinar non malesuada velit molestie. Vestibulum quis mauris urna, ut porta orci.</p>', '2013-05-02 21:34:49'),
(53, 32, 'Próba bejegyzés', 'leírása', '2012-07-24 16:26:35'),
(59, 15, 'Bigger Lorem Ipsum', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur ut aliquet ligula. Sed a mauris sit amet lectus porttitor sollicitudin quis consectetur nunc. Donec ornare rutrum sollicitudin. Suspendisse laoreet, sapien nec mattis ultrices, neque nibh fringilla leo, vitae porta magna velit et mi. Cras dolor libero, dapibus nec elementum quis, faucibus in diam. Suspendisse molestie, massa vel elementum ullamcorper, arcu libero consequat lorem, vel facilisis eros lectus sed metus. Vestibulum dictum facilisis metus at accumsan. Praesent quis enim vitae lacus tincidunt rutrum. Aenean rutrum, lacus eu porta commodo, est diam rhoncus lacus, vel molestie ipsum enim ac metus. Nulla lobortis convallis nisi iaculis tincidunt. Aliquam mi risus, lobortis eget commodo non, mattis a ligula. Mauris in rhoncus nisi. Nulla eu magna in urna vulputate ornare ac ut purus. Nulla tortor felis, rhoncus ut iaculis vel, tempus vitae dolor.\r\n\r\nAliquam justo metus, varius at scelerisque at, varius eu purus. Vestibulum feugiat dui non dui adipiscing tempus nec euismod purus. Nullam scelerisque faucibus dictum. Nulla vulputate sapien a massa laoreet dictum ullamcorper neque aliquet. Aliquam ac orci vitae ligula scelerisque iaculis quis ac leo. Aliquam eget quam et justo laoreet pretium. Nulla eu convallis nisi. Suspendisse potenti. Donec eget quam nec diam consequat commodo. Suspendisse ut neque in est facilisis aliquet. Donec elementum urna id diam gravida a ornare nisi molestie. Suspendisse venenatis imperdiet felis, quis eleifend ipsum lacinia id. Vivamus eleifend blandit accumsan.\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur ut aliquet ligula. Sed a mauris sit amet lectus porttitor sollicitudin quis consectetur nunc. Donec ornare rutrum sollicitudin. Suspendisse laoreet, sapien nec mattis ultrices, neque nibh fringilla leo, vitae porta magna velit et mi. Cras dolor libero, dapibus nec elementum quis, faucibus in diam. Suspendisse molestie, massa vel elementum ullamcorper, arcu libero consequat lorem, vel facilisis eros lectus sed metus. Vestibulum dictum facilisis metus at accumsan. Praesent quis enim vitae lacus tincidunt rutrum. Aenean rutrum, lacus eu porta commodo, est diam rhoncus lacus, vel molestie ipsum enim ac metus. Nulla lobortis convallis nisi iaculis tincidunt. Aliquam mi risus, lobortis eget commodo non, mattis a ligula. Mauris in rhoncus nisi. Nulla eu magna in urna vulputate ornare ac ut purus. Nulla tortor felis, rhoncus ut iaculis vel, tempus vitae dolor.\r\n\r\nAliquam justo metus, varius at scelerisque at, varius eu purus. Vestibulum feugiat dui non dui adipiscing tempus nec euismod purus. Nullam scelerisque faucibus dictum. Nulla vulputate sapien a massa laoreet dictum ullamcorper neque aliquet. Aliquam ac orci vitae ligula scelerisque iaculis quis ac leo. Aliquam eget quam et justo laoreet pretium. Nulla eu convallis nisi. Suspendisse potenti. Donec eget quam nec diam consequat commodo. Suspendisse ut neque in est facilisis aliquet. Donec elementum urna id diam gravida a ornare nisi molestie. Suspendisse venenatis imperdiet felis, quis eleifend ipsum lacinia id. Vivamus eleifend blandit accumsan.', '2012-07-24 16:26:35'),
(130, 37, 'A madame', 'Eredeti nevén Evangeline Va''larithae, de mindenki csak Eve-nek hívja, és ő a Hamvas Barack nevű luxusbordély madameja, elég komoly kapcsolatokkal. Azonban nem biztos, hogy ez a valódi neve, mivel nem régen kiderült, hogy ő nem más a Kathrine medáljához "kapcsolt" succubus.', '2012-07-30 15:12:06'),
(206, 82, 'Neverith szeme', '', '2013-02-11 21:42:05'),
(131, 37, 'Robert', 'Keveset lehet róla tudni. Ami bizonyos, hogy jó pár hónapja Kathrine-t oktatja vívásra, és valószínű még sok minden másra. Eléggé "fura" embernek tűnik, aki szereti hülyének tetetni magát, ezenkívül moralizálása gyakran lendül filozófiai magasságokba, emiatt sokan nehezen tudják elviselni.<br>Mint kiderült ő és a madame ismerik egymást régről, valamint, hogy ő is részt vett a Bíbor Árnyak megalapításában. Nem Robert a valódi neve, hanem Garret.<br>', '2012-07-30 14:55:45'),
(132, 37, 'A Karmazsin Átok', 'Karmazsin köpenybe búvó mestertolvaj, aki csak nemesektől lop, és csak műtárgyakat vagy egyéb értékes dolgokat, aranyat közvetlenül sosem. Annyit lehet róla tudni, hogy Suzailban az első számú közellenség. Jelenleg több, mint 1000 arany vérdíj van a fején és már a Hadi Varázslók is keresik. Néhány gyilkossággal is vádolják, azonban kiderült, hogy ezek egyikét sem ő követte el.', '2012-07-30 14:59:15'),
(134, 37, 'A Karmazsin Átok - Wilben', 'Karmazsin köpenybe búvó mestertolvaj, aki csak nemesektől lop, és csak műtárgyakat vagy egyéb értékes dolgokat, aranyat közvetlenül sosem. Annyit lehet róla tudni, hogy Suzailban az első számú közellenség. Jelenleg több, mint 1000 arany vérdíj van a fején és már a Hadi Varázslók is keresik. Néhány gyilkossággal is vádolják, azonban kiderült, hogy ezek egyikét sem ő követte el.\nMivel az elf vándor látta a tolvaj arcát, ezért tudja, hogy a Karmazsin Átok nem más, mint Kathrine Truesilver, aki nem csak, hogy a Truesilver család tagja (ami közeli rokonságban áll Cormyr királyi családjával, az Obarskyrokkal), hanem valójában a jelenlegi uralkodó, Proster húgának, Anna Obarskyrnek a lánya.', '2012-07-30 15:04:51'),
(135, 37, 'bugreports', '', '2012-07-30 15:30:47'),
(137, 42, '', 'fsafas\r\n', '2012-08-27 19:37:27'),
(138, 42, 'da', 'ezt csak te látod?<div><br></div><div><br></div><div>és a szöveget??</div><div><br></div>', '2012-08-27 19:40:31'),
(139, 44, 'aae', 'gageagaega', '2012-08-27 20:04:08'),
(197, 64, 'qwe', 'asd', '2012-10-22 20:17:20'),
(199, 64, 'asdf', 'd', '2012-10-22 20:19:08'),
(204, 78, 'qweqwe', 'qwe', '2013-01-27 16:03:11'),
(207, 82, 'Funtheros hegyeinek térképei', '', '2013-02-11 21:42:23'),
(208, 82, 'Funtheros fontosabb népi szokásai', '', '2013-02-11 21:42:33');
*/
-- --------------------------------------------------------

--
-- Table structure for table `friendconnections`
--

CREATE TABLE IF NOT EXISTS `friendconnections` (
  `specID` int(11) NOT NULL AUTO_INCREMENT,
  `initiaterID` int(11) NOT NULL,
  `accepterID` int(11) NOT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`specID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=52 ;

--
-- Dumping data for table `friendconnections`
--
/*
INSERT INTO `friendconnections` (`specID`, `initiaterID`, `accepterID`, `approved`) VALUES
(46, 1, 1, 1),
(2, 1, 3, 1),
(29, 4, 4, 1),
(4, 1, 5, 1),
(39, 6, 1, 1),
(6, 2, 5, 1),
(7, 2, 2, 0),
(40, 6, 30, 1),
(35, 30, 1, 1),
(34, 4, 1, 1),
(33, 4, 29, 1),
(32, 4, 27, 1),
(31, 4, 5, 1),
(30, 4, 2, 1),
(28, 26, 1, 1),
(25, 1, 7, 1),
(41, 1, 6, 1),
(42, 1, 34, 1),
(43, 36, 4, 1),
(44, 28, 4, 1),
(45, 29, 28, 1),
(51, 1, 2, 1),
(48, 23, 20, 1),
(49, 1, 20, 1),
(50, 46, 1, 1);
*/
-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE IF NOT EXISTS `games` (
  `gamesID` int(11) NOT NULL AUTO_INCREMENT,
  `usersID` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8_hungarian_ci NOT NULL,
  `description` varchar(1200) COLLATE utf8_hungarian_ci NOT NULL,
  `type` int(11) NOT NULL,
  `started` date NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`gamesID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=84 ;

--
-- Dumping data for table `games`
--
/*
INSERT INTO `games` (`gamesID`, `usersID`, `title`, `description`, `type`, `started`, `active`) VALUES
(7, 1, 'Horror Archam városában!', 'Horrific monsters and spectral presences lurk in manors, crypts, schools, monasteries, and derelict buildings near Arkham, Massachusetts. Some spin dark conspiracies while others wait for hapless victims to devour or drive insane. It’s up to a handful of brave investigators to explore these cursed places and uncover the truth about the living nightmares within.\r\n\r\nDesigned by Corey Konieczka, Mansions of Madness is a macabre game of horror, insanity, and mystery for two to five players. Each game takes place within a pre-designed story that provides players with a unique map and several combinations of plot threads. These threads affect the monsters that investigators may encounter, the clues they need to find, and which climactic story ending they will ultimately experience. One player takes on the role of the keeper, controlling the monsters and other malicious powers within the story. The other players take on the role of investigators, searching for answers while struggling to survive with their minds intact.\r\n\r\nDo you dare enter the Mansions of Madness?', 1, '2011-10-08', 0),
(15, 2, 'Az első cthulhu játék.', 'Cthulhu jön, hogy elpusztítsa a Világot!', 2, '2012-04-02', 0),
(10, 4, 'Vaalnd', 'Ez most a Vaaland játék leírása', 1, '2011-10-28', 0),
(12, 10, 'próba DVD', 'csak kipróbálom', 1, '2011-11-21', 0),
(17, 7, 'grrr', 'mind megfogtok dögleni, azért gyertek :D', 2, '2012-04-20', 0),
(36, 4, 'Shadowrun', 'Minta shadowrun kaland', 5, '2012-05-23', 0),
(40, 33, 'tesztgamelek', 'ez egy teszt', 1, '2012-07-31', 0),
(37, 30, 'A Pusztaság hírnökei (FR)', '1094-et írunk, a Csontok Csatája 4 éve történt. A cormyri gazdaság többé-kevésbé kiheverte az aszályos évek okozta károkat, azonban rengetegen váltak nincstelenné, és jobb híján az útonállást választották, hogy sanyarú helyzetükön javítsanak. A bajokat tetézi, hogy Cormyr fővárosában, Suzailban, egy mestertolvaj lopkodja a nemesek pénzét és műkincseit. A hatóságok semmit nem tudnak róla, csak annyit, hogy karmazsin színű köpenyt visel. Itt kezdődik a kampány.', 1, '2012-07-13', 0),
(41, 37, 'attack!', '', 5, '2012-08-08', 0),
(42, 29, 'fasf', 'faaasf', 5, '2012-08-27', 0),
(43, 29, 'abba', '', 5, '2012-08-27', 0),
(44, 29, 'test2', 'aea', 5, '2012-08-27', 0),
(64, 1, 'Új karakterlap teszting', 'TESZTESZTESZT FTWWWW', 6, '2012-10-19', 0),
(65, 1, 'c', 'c', 2, '2012-10-19', 0),
(66, 1, 'dn', 'dn', 1, '2012-10-19', 0),
(67, 1, 'qweqwe', 'qweqew', 2, '2012-10-19', 0),
(68, 1, 'dh', 'dh', 4, '2012-10-20', 0),
(69, 1, 'dfg', 'dfg', 3, '2012-10-21', 0),
(70, 1, 'ALL Flesh. I mean: ALL.', 'Read the title.', 7, '2012-11-03', 0),
(71, 2, 'afmbe', 'afmbe', 7, '2012-11-03', 0),
(72, 1, 'affdghjkl', 'afhjuzkiujikl', 8, '2012-11-03', 0),
(73, 5, 'asd', 'asd', 1, '2013-01-04', 0),
(74, 1, 'qweqweqwe', 'qweqweqwe', 2, '2013-01-20', 0),
(75, 1, 'w', 'w', 2, '2013-01-20', 0),
(76, 1, 'zeeNewGame', 'zeeNewGame', 2, '2013-01-21', 0),
(77, 2, 'afmbe', '', 6, '2013-01-21', 0),
(78, 1, 'ex', 'ex', 3, '2013-01-27', 0),
(79, 1, '', '', 2, '2013-01-28', 0),
(80, 1, '', '', 2, '2013-01-28', 0),
(81, 1, '', '', 2, '2013-01-28', 0),
(82, 1, 'Kalandozás Aerth világában', '', 1, '2013-02-11', 0),
(83, 1, 'A DND test game', 'A DND type test game for introduction  purposes.', 1, '2013-05-02', 0);
*/
-- --------------------------------------------------------

--
-- Table structure for table `s_all_flesh_must_be_eaten_10_allies`
--

CREATE TABLE IF NOT EXISTS `s_all_flesh_must_be_eaten_10_allies` (
  `specID` int(11) NOT NULL AUTO_INCREMENT,
  `gamesID` int(11) DEFAULT NULL,
  `usersID` int(11) DEFAULT NULL,
  `allies_note` varchar(100) DEFAULT NULL,
  `vis_allies_note` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`specID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `s_all_flesh_must_be_eaten_10_allies`
--
/*
INSERT INTO `s_all_flesh_must_be_eaten_10_allies` (`specID`, `gamesID`, `usersID`, `allies_note`, `vis_allies_note`) VALUES
(1, 64, 2, '', 1),
(2, 64, 2, '', 1),
(3, 64, 2, '', 1);
*/
-- --------------------------------------------------------

--
-- Table structure for table `s_all_flesh_must_be_eaten_10_attrs`
--

CREATE TABLE IF NOT EXISTS `s_all_flesh_must_be_eaten_10_attrs` (
  `specID` int(11) NOT NULL AUTO_INCREMENT,
  `gamesID` int(11) DEFAULT NULL,
  `usersID` int(11) DEFAULT NULL,
  `sex` varchar(100) DEFAULT NULL,
  `vis_sex` tinyint(1) NOT NULL DEFAULT '1',
  `age` varchar(100) DEFAULT NULL,
  `vis_age` tinyint(1) NOT NULL DEFAULT '1',
  `height` varchar(100) DEFAULT NULL,
  `vis_height` tinyint(1) NOT NULL DEFAULT '1',
  `weight` varchar(100) DEFAULT NULL,
  `vis_weight` tinyint(1) NOT NULL DEFAULT '1',
  `hair` varchar(100) DEFAULT NULL,
  `vis_hair` tinyint(1) NOT NULL DEFAULT '1',
  `eyes` varchar(100) DEFAULT NULL,
  `vis_eyes` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`specID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=104 ;

--
-- Dumping data for table `s_all_flesh_must_be_eaten_10_attrs`
--
/*
INSERT INTO `s_all_flesh_must_be_eaten_10_attrs` (`specID`, `gamesID`, `usersID`, `sex`, `vis_sex`, `age`, `vis_age`, `height`, `vis_height`, `weight`, `vis_weight`, `hair`, `vis_hair`, `eyes`, `vis_eyes`) VALUES
(100, 70, 2, 'man', 1, '36', 1, '78kg', 1, '170cm', 1, 'hárafésült', 1, 'green', 1),
(101, 71, 1, NULL, 1, NULL, 1, NULL, 1, NULL, 1, NULL, 1, NULL, 1),
(102, 71, 5, NULL, 1, NULL, 1, NULL, 1, NULL, 1, NULL, 1, NULL, 1),
(103, 64, 2, NULL, 1, NULL, 1, NULL, 1, NULL, 1, NULL, 1, NULL, 1);
*/
-- --------------------------------------------------------

--
-- Table structure for table `s_all_flesh_must_be_eaten_10_base`
--

CREATE TABLE IF NOT EXISTS `s_all_flesh_must_be_eaten_10_base` (
  `specID` int(11) NOT NULL AUTO_INCREMENT,
  `gamesID` int(11) DEFAULT NULL,
  `usersID` int(11) DEFAULT NULL,
  `base_name` varchar(100) DEFAULT NULL,
  `vis_base_name` tinyint(1) NOT NULL DEFAULT '1',
  `character_type` varchar(100) DEFAULT NULL,
  `vis_character_type` tinyint(1) NOT NULL DEFAULT '1',
  `character_points` varchar(100) DEFAULT NULL,
  `vis_character_points` tinyint(1) NOT NULL DEFAULT '1',
  `spent` varchar(100) DEFAULT NULL,
  `vis_spent` tinyint(1) NOT NULL DEFAULT '1',
  `unspent` varchar(100) DEFAULT NULL,
  `vis_unspent` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`specID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=104 ;

--
-- Dumping data for table `s_all_flesh_must_be_eaten_10_base`
--
/*
INSERT INTO `s_all_flesh_must_be_eaten_10_base` (`specID`, `gamesID`, `usersID`, `base_name`, `vis_base_name`, `character_type`, `vis_character_type`, `character_points`, `vis_character_points`, `spent`, `vis_spent`, `unspent`, `vis_unspent`) VALUES
(100, 70, 2, 'Robert Warwick', 1, 'Corp Executive', 1, ' ', 1, ' ', 1, '', 1),
(101, 71, 1, 'asdasdqwe', 1, '', 1, '', 1, '', 1, '', 1),
(102, 71, 5, 'CONTROL FTW', 1, '', 1, '456', 1, '4', 1, '5', 1),
(103, 64, 2, NULL, 1, NULL, 1, NULL, 1, NULL, 1, NULL, 1);
*/
-- --------------------------------------------------------

--
-- Table structure for table `s_all_flesh_must_be_eaten_10_drawbacks`
--

CREATE TABLE IF NOT EXISTS `s_all_flesh_must_be_eaten_10_drawbacks` (
  `specID` int(11) NOT NULL AUTO_INCREMENT,
  `gamesID` int(11) DEFAULT NULL,
  `usersID` int(11) DEFAULT NULL,
  `drawbacks_name` varchar(100) DEFAULT NULL,
  `vis_drawbacks_name` tinyint(1) NOT NULL DEFAULT '1',
  `drawbacks_points` varchar(100) DEFAULT NULL,
  `vis_drawbacks_points` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`specID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=105 ;

--
-- Dumping data for table `s_all_flesh_must_be_eaten_10_drawbacks`
--
/*
INSERT INTO `s_all_flesh_must_be_eaten_10_drawbacks` (`specID`, `gamesID`, `usersID`, `drawbacks_name`, `vis_drawbacks_name`, `drawbacks_points`, `vis_drawbacks_points`) VALUES
(100, 70, 2, 'Cowardly 1', 1, '-1', 1),
(101, 70, 2, 'Emotional Problem (Feeling of uselessnes)', 1, '-1', 1),
(102, 70, 2, 'Emotional Problem (Stoicism)', 1, '-1', 1),
(103, 70, 2, 'Reoccuring nightmares (Dying)', 1, '-1', 1),
(104, 64, 2, '', 1, '', 1);
*/
-- --------------------------------------------------------

--
-- Table structure for table `s_all_flesh_must_be_eaten_10_history`
--

CREATE TABLE IF NOT EXISTS `s_all_flesh_must_be_eaten_10_history` (
  `specID` int(11) NOT NULL AUTO_INCREMENT,
  `gamesID` int(11) DEFAULT NULL,
  `usersID` int(11) DEFAULT NULL,
  `history_note` varchar(100) DEFAULT NULL,
  `vis_history_note` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`specID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `s_all_flesh_must_be_eaten_10_history`
--
/*
INSERT INTO `s_all_flesh_must_be_eaten_10_history` (`specID`, `gamesID`, `usersID`, `history_note`, `vis_history_note`) VALUES
(1, 64, 2, '', 1),
(2, 64, 2, '', 1),
(3, 64, 2, '', 1);
*/
-- --------------------------------------------------------

--
-- Table structure for table `s_all_flesh_must_be_eaten_10_posessions`
--

CREATE TABLE IF NOT EXISTS `s_all_flesh_must_be_eaten_10_posessions` (
  `specID` int(11) NOT NULL AUTO_INCREMENT,
  `gamesID` int(11) DEFAULT NULL,
  `usersID` int(11) DEFAULT NULL,
  `posessions_note` varchar(100) DEFAULT NULL,
  `vis_posessions_note` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`specID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=105 ;

--
-- Dumping data for table `s_all_flesh_must_be_eaten_10_posessions`
--
/*
INSERT INTO `s_all_flesh_must_be_eaten_10_posessions` (`specID`, `gamesID`, `usersID`, `posessions_note`, `vis_posessions_note`) VALUES
(100, 70, 2, 'Briefcase with Business Paper', 1),
(101, 70, 2, 'Smartphone (Almost crushed.)', 1),
(102, 70, 2, 'Limo (Not available right now I guess.)', 1),
(103, 70, 2, 'Tár: 3 töltény még.', 1),
(104, 64, 2, '', 1);
*/
-- --------------------------------------------------------

--
-- Table structure for table `s_all_flesh_must_be_eaten_10_primattr`
--

CREATE TABLE IF NOT EXISTS `s_all_flesh_must_be_eaten_10_primattr` (
  `specID` int(11) NOT NULL AUTO_INCREMENT,
  `gamesID` int(11) DEFAULT NULL,
  `usersID` int(11) DEFAULT NULL,
  `str` varchar(100) DEFAULT NULL,
  `vis_str` tinyint(1) NOT NULL DEFAULT '1',
  `int` varchar(100) DEFAULT NULL,
  `vis_int` tinyint(1) NOT NULL DEFAULT '1',
  `dex` varchar(100) DEFAULT NULL,
  `vis_dex` tinyint(1) NOT NULL DEFAULT '1',
  `per` varchar(100) DEFAULT NULL,
  `vis_per` tinyint(1) NOT NULL DEFAULT '1',
  `con` varchar(100) DEFAULT NULL,
  `vis_con` tinyint(1) NOT NULL DEFAULT '1',
  `will` varchar(100) DEFAULT NULL,
  `vis_will` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`specID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=104 ;

--
-- Dumping data for table `s_all_flesh_must_be_eaten_10_primattr`
--
/*
INSERT INTO `s_all_flesh_must_be_eaten_10_primattr` (`specID`, `gamesID`, `usersID`, `str`, `vis_str`, `int`, `vis_int`, `dex`, `vis_dex`, `per`, `vis_per`, `con`, `vis_con`, `will`, `vis_will`) VALUES
(100, 70, 2, '2', 1, '3', 1, '2', 1, '2', 1, '3', 1, '2', 1),
(101, 71, 1, NULL, 1, NULL, 1, NULL, 1, NULL, 1, NULL, 1, NULL, 1),
(102, 71, 5, NULL, 1, NULL, 1, NULL, 1, NULL, 1, NULL, 1, NULL, 1),
(103, 64, 2, NULL, 1, NULL, 1, NULL, 1, NULL, 1, NULL, 1, NULL, 1);
*/
-- --------------------------------------------------------

--
-- Table structure for table `s_all_flesh_must_be_eaten_10_qualities`
--

CREATE TABLE IF NOT EXISTS `s_all_flesh_must_be_eaten_10_qualities` (
  `specID` int(11) NOT NULL AUTO_INCREMENT,
  `gamesID` int(11) DEFAULT NULL,
  `usersID` int(11) DEFAULT NULL,
  `qualities_name` varchar(100) DEFAULT NULL,
  `vis_qualities_name` tinyint(1) NOT NULL DEFAULT '1',
  `qualities_points` varchar(100) DEFAULT NULL,
  `vis_qualities_points` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`specID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=105 ;

--
-- Dumping data for table `s_all_flesh_must_be_eaten_10_qualities`
--
/*
INSERT INTO `s_all_flesh_must_be_eaten_10_qualities` (`specID`, `gamesID`, `usersID`, `qualities_name`, `vis_qualities_name`, `qualities_points`, `vis_qualities_points`) VALUES
(101, 70, 2, 'Charisma', 1, '+2', 1),
(102, 70, 2, 'Status', 1, '+3', 1),
(103, 70, 2, 'Resources (Mut...? Exec) 2', 1, '4', 1),
(104, 64, 2, '', 1, '', 1);
*/
-- --------------------------------------------------------

--
-- Table structure for table `s_all_flesh_must_be_eaten_10_skill`
--

CREATE TABLE IF NOT EXISTS `s_all_flesh_must_be_eaten_10_skill` (
  `specID` int(11) NOT NULL AUTO_INCREMENT,
  `gamesID` int(11) DEFAULT NULL,
  `usersID` int(11) DEFAULT NULL,
  `skill_name` varchar(100) DEFAULT NULL,
  `vis_skill_name` tinyint(1) NOT NULL DEFAULT '1',
  `skill_level` varchar(100) DEFAULT NULL,
  `vis_skill_level` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`specID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=118 ;

--
-- Dumping data for table `s_all_flesh_must_be_eaten_10_skill`
--
/*
INSERT INTO `s_all_flesh_must_be_eaten_10_skill` (`specID`, `gamesID`, `usersID`, `skill_name`, `vis_skill_name`, `skill_level`, `vis_skill_level`) VALUES
(101, 70, 2, 'Browling', 1, '1', 1),
(102, 70, 2, 'Burocracy', 1, '4', 1),
(103, 70, 2, 'Computers', 1, '2', 1),
(104, 70, 2, 'Drive ( Ca? )', 1, '2', 1),
(105, 70, 2, 'Gun (Handgun)', 1, '1', 1),
(106, 70, 2, 'Hand Weapon Club', 1, '1', 1),
(107, 70, 2, 'Humanitis (Business)', 1, '4', 1),
(108, 70, 2, 'Intimidation', 1, '1', 1),
(109, 70, 2, 'Language (French)', 1, '3', 1),
(110, 70, 2, 'Notice', 1, '2', 1),
(111, 70, 2, 'Sainc (Meth)', 1, '2', 1),
(112, 70, 2, 'Storytelling', 1, '2', 1),
(113, 70, 2, 'Sport (Golf)', 1, '2', 1),
(114, 70, 2, 'Writing (Advocacy)', 1, '2', 1),
(115, 70, 2, 'Writing (Creative)', 1, '1', 1),
(116, 64, 2, '', 1, '', 1),
(117, 64, 2, '', 1, '', 1);
*/
-- --------------------------------------------------------

--
-- Table structure for table `s_all_flesh_must_be_eaten_10_weapons`
--

CREATE TABLE IF NOT EXISTS `s_all_flesh_must_be_eaten_10_weapons` (
  `specID` int(11) NOT NULL AUTO_INCREMENT,
  `gamesID` int(11) DEFAULT NULL,
  `usersID` int(11) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `vis_type` tinyint(1) NOT NULL DEFAULT '1',
  `rateoffire` varchar(100) DEFAULT NULL,
  `vis_rateoffire` tinyint(1) NOT NULL DEFAULT '1',
  `attack` varchar(100) DEFAULT NULL,
  `vis_attack` tinyint(1) NOT NULL DEFAULT '1',
  `damage` varchar(100) DEFAULT NULL,
  `vis_damage` tinyint(1) NOT NULL DEFAULT '1',
  `damagebonus` varchar(100) DEFAULT NULL,
  `vis_damagebonus` tinyint(1) NOT NULL DEFAULT '1',
  `range` varchar(100) DEFAULT NULL,
  `vis_range` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`specID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=102 ;

--
-- Dumping data for table `s_all_flesh_must_be_eaten_10_weapons`
--
/*
INSERT INTO `s_all_flesh_must_be_eaten_10_weapons` (`specID`, `gamesID`, `usersID`, `type`, `vis_type`, `rateoffire`, `vis_rateoffire`, `attack`, `vis_attack`, `damage`, `vis_damage`, `damagebonus`, `vis_damagebonus`, `range`, `vis_range`) VALUES
(100, 70, 2, 'Handgun 9mm', 1, '1', 1, ' ', 1, 'd6x4 (12)', 1, ' ', 1, ' ', 1),
(101, 64, 2, '', 1, '', 1, '', 1, '', 1, '', 1, '', 1);
*/
-- --------------------------------------------------------

--
-- Table structure for table `s_example_10_base`
--

CREATE TABLE IF NOT EXISTS `s_example_10_base` (
  `specID` int(11) NOT NULL AUTO_INCREMENT,
  `gamesID` int(11) DEFAULT NULL,
  `usersID` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `vis_name` tinyint(1) NOT NULL DEFAULT '1',
  `xp` varchar(100) DEFAULT NULL,
  `vis_xp` tinyint(1) NOT NULL DEFAULT '1',
  `hit_points` varchar(100) DEFAULT NULL,
  `vis_hit_points` tinyint(1) NOT NULL DEFAULT '1',
  `defense` varchar(100) DEFAULT NULL,
  `vis_defense` tinyint(1) NOT NULL DEFAULT '1',
  `damage` varchar(100) DEFAULT NULL,
  `vis_damage` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`specID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=101 ;

--
-- Dumping data for table `s_example_10_base`
--
/*
INSERT INTO `s_example_10_base` (`specID`, `gamesID`, `usersID`, `name`, `vis_name`, `xp`, `vis_xp`, `hit_points`, `vis_hit_points`, `defense`, `vis_defense`, `damage`, `vis_damage`) VALUES
(100, 69, 2, NULL, 1, NULL, 1, NULL, 1, NULL, 1, NULL, 1);
*/
-- --------------------------------------------------------

--
-- Table structure for table `s_example_10_inventory`
--

CREATE TABLE IF NOT EXISTS `s_example_10_inventory` (
  `specID` int(11) NOT NULL AUTO_INCREMENT,
  `gamesID` int(11) DEFAULT NULL,
  `usersID` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `vis_name` tinyint(1) NOT NULL DEFAULT '1',
  `quantity` varchar(100) DEFAULT NULL,
  `vis_quantity` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`specID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `usersID` int(11) NOT NULL AUTO_INCREMENT,
  `mail` varchar(30) COLLATE utf8_hungarian_ci NOT NULL,
  `nick` varchar(20) COLLATE utf8_hungarian_ci NOT NULL,
  `pass` varchar(32) COLLATE utf8_hungarian_ci NOT NULL,
  `born` date NOT NULL,
  `gender` tinyint(1) NOT NULL,
  `lang` varchar(2) COLLATE utf8_hungarian_ci NOT NULL DEFAULT 'hu',
  `joined` date NOT NULL,
  `code` varchar(10) COLLATE utf8_hungarian_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `rights` tinyint(1) DEFAULT '1',
  `forgottenCode` varchar(10) COLLATE utf8_hungarian_ci DEFAULT NULL,
  PRIMARY KEY (`usersID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=48 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`usersID`, `mail`, `nick`, `pass`, `born`, `gender`, `lang`, `joined`, `code`, `active`, `rights`, `forgottenCode`) VALUES
(1, 'admin@admin.admin', 'admin', '21232f297a57a5a743894a0e4a801fc3', '1990-01-01', 1, 'hu', '2013-01-01', NULL, 1, 2, NULL),
(2, 'tester@tester.tester', 'tester', 'f5d1278e8109edd94e1e4197e04873b9', '1990-01-01', 1, 'en', '2013-01-01', NULL, 1, 1, NULL);
/*
,
(3, 'lszabady@citromail.hu', 'MuFFynMaker', 'ff09bfd979ea671118735f2b9cb0d53c', '0000-00-00', 1, 'hu', '2011-09-12', NULL, 1, 1, NULL),
(4, 'vaalen@freemail.hu', 'Vaalen', 'ef25e33830f2e06812a73ca2df828412', '0000-00-00', 1, 'hu', '2011-09-14', NULL, 1, 1, NULL),
(5, 'control@control.control', 'control', '9b0521e23569ee5c51cd69827e201038', '0000-00-00', 1, 'en', '2011-10-02', NULL, 1, 1, NULL),
(6, 'lajko.mate@gmail.com', 'NotShouting', '21b72c0b7adc5c7b4a50ffcb90d92dd6', '0000-00-00', 1, 'hu', '2011-10-16', NULL, 1, 1, NULL),
(7, 'totalkar@yahoo.com', 'Tefl0n', '8856a8ad07feb60e414d0e8594a9b73b', '0000-00-00', 1, 'hu', '2011-10-19', NULL, 1, 1, NULL),
(10, 'havasi@inf.u-szeged.hu', 'havasi', '03e9efb83458b67c16eaebd03ac08bcc', '0000-00-00', 1, 'hu', '2011-11-21', NULL, 1, 1, NULL),
(20, 'rpgmorpheus@vipmail.hu', 'asd', '9b0521e23569ee5c51cd69827e201038', '2013-01-01', 1, 'hu', '2012-01-27', NULL, 1, 1, NULL),
(40, 'pootyx@gmail.com', 'pootyx', '90074121f4c2e8b4ad3ce52e36b0c39d', '1994-07-20', 1, 'hu', '2012-08-08', NULL, 1, 1, NULL),
(26, 'petaktamas@gmail.com', 'Mrgoodbyes', 'e68e14aac203ff3678e3fa630d09fba6', '1991-04-11', 1, 'hu', '2012-05-02', NULL, 1, 1, NULL),
(27, 'bitpork@gmail.com', 'bitpork', 'db3dd905a452b05692de0dacfee56531', '1978-07-11', 1, 'hu', '2012-05-23', NULL, 1, 1, NULL),
(28, 'tb.torok@gmail.com', 'Velinor', 'ef25e33830f2e06812a73ca2df828412', '1978-04-21', 1, 'hu', '2012-05-23', NULL, 1, 1, NULL),
(29, 'matedavide@gmail.com', 'matedavide', '03dbb0e7a464aea8a4a5d3b7781d3e29', '1977-04-03', 1, 'hu', '2012-05-24', NULL, 1, 1, NULL),
(30, 'the_beyonder87@yahoo.com', 'eClipse', 'fca3319d18c2794a2b863d4fc54244db', '1987-12-31', 1, 'hu', '2012-07-11', NULL, 1, 1, NULL),
(31, 'lajko.mate@gmail.com', 'NotShouting', '21b72c0b7adc5c7b4a50ffcb90d92dd6', '1990-07-01', 1, 'hu', '2012-07-30', NULL, 1, 1, NULL),
(32, 'azmonad@gmail.com', 'Atis', '8ace23e822a2d79650d9719dd2503fd2', '1987-01-07', 1, 'hu', '2012-07-31', NULL, 1, 1, NULL),
(33, 'chekow@freemail.hu', 'Chekow', '2a20a701e2ca356608bd4f1c018cf958', '2012-07-09', 1, 'en', '2012-07-31', NULL, 1, 1, NULL),
(34, 'mokokomita@gmail.com', 'SimonMester', '80416e58c362013d52388d49952c030a', '1993-08-15', 1, 'hu', '2012-08-03', NULL, 1, 1, NULL),
(36, 'rita.torok@h-lab.eu', 'Meroko', 'cb81be0e3fe7f9328bed66b15ef7119e', '2012-08-01', 1, 'hu', '2012-08-08', NULL, 1, 1, NULL),
(37, 'ravine.hu@gmail.com', 'tj', 'cb7fb1a844580d658370f6739f10c52b', '1977-01-01', 1, 'hu', '2012-08-08', NULL, 1, 1, NULL),
(47, 'hubertviktor@hv-web.hu', 'KingSlayer', 'a8f5f167f44f4964e6c998dee827110c', '2013-02-01', 1, 'hu', '2013-02-27', 'QdTeAbPXNW', 0, 1, NULL);
*/

-- --------------------------------------------------------

--
-- Table structure for table `usersmessages`
--

CREATE TABLE IF NOT EXISTS `usersmessages` (
  `usersMessagesID` int(11) NOT NULL AUTO_INCREMENT,
  `toID` int(11) NOT NULL,
  `fromID` int(11) NOT NULL,
  `title` varchar(30) COLLATE utf8_hungarian_ci NOT NULL,
  `message` varchar(500) COLLATE utf8_hungarian_ci NOT NULL,
  `date` date NOT NULL,
  `shown` tinyint(1) NOT NULL DEFAULT '0',
  `seen` tinyint(1) NOT NULL DEFAULT '0',
  `friendRequestID` int(11) DEFAULT NULL,
  `gameRequestID` int(11) DEFAULT NULL,
  PRIMARY KEY (`usersMessagesID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=416 ;

--
-- Dumping data for table `usersmessages`
--
/*
INSERT INTO `usersmessages` (`usersMessagesID`, `toID`, `fromID`, `title`, `message`, `date`, `shown`, `seen`, `friendRequestID`, `gameRequestID`) VALUES
(349, 30, 1, '', '', '0000-00-00', 0, 0, NULL, 7),
(412, 46, 1, '', '', '0000-00-00', 0, 0, NULL, 7),
(371, 27, 4, '', '', '0000-00-00', 0, 0, NULL, 36),
(341, 4, 5, '', '', '0000-00-00', 0, 0, NULL, 45),
(402, 20, 1, '', '', '0000-00-00', 0, 0, NULL, 7),
(214, 22, 1, '-', '-', '2012-04-23', 0, 0, 1, NULL),
(365, 34, 1, '', '', '0000-00-00', 0, 0, NULL, 7),
(337, 4, 29, '', '', '0000-00-00', 0, 0, NULL, 44),
(336, 28, 29, '', '', '0000-00-00', 0, 0, NULL, 44),
(295, 6, 30, '-', '-', '2012-07-30', 0, 0, 30, NULL),
(296, 31, 30, '-', '-', '2012-07-30', 0, 0, 30, NULL),
(413, 26, 1, '', '', '0000-00-00', 0, 0, NULL, 7),
(414, 34, 1, '', '', '0000-00-00', 0, 0, NULL, 7),
(407, 26, 1, '', '', '0000-00-00', 0, 0, NULL, 7),
(415, 2, 1, '', '', '0000-00-00', 0, 0, NULL, 83);
*/

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
