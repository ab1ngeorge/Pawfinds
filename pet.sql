-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 26, 2025 at 04:38 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pet`
--

-- --------------------------------------------------------

--
-- Table structure for table `adopt_history`
--

CREATE TABLE `adopt_history` (
  `adopt_history_id` int(11) NOT NULL,
  `adopter_id` int(11) NOT NULL,
  `pet_id` int(11) NOT NULL,
  `adopt_date` datetime DEFAULT current_timestamp(),
  `status` enum('approved','rejected') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adopt_history`
--

INSERT INTO `adopt_history` (`adopt_history_id`, `adopter_id`, `pet_id`, `adopt_date`, `status`) VALUES
(1, 8, 11, '2025-03-13 00:54:58', 'approved'),
(2, 17, 3, '2025-03-21 14:07:18', 'approved'),
(3, 6, 6, '2025-03-21 16:50:28', 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `consultations`
--

CREATE TABLE `consultations` (
  `consultation_id` int(11) NOT NULL,
  `pet_name` varchar(100) NOT NULL,
  `owner_name` varchar(100) NOT NULL,
  `contact_email` varchar(100) NOT NULL,
  `contact_phone` varchar(20) NOT NULL,
  `issue_type` varchar(50) NOT NULL,
  `consultation_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `consultations`
--

INSERT INTO `consultations` (`consultation_id`, `pet_name`, `owner_name`, `contact_email`, `contact_phone`, `issue_type`, `consultation_date`, `created_at`, `updated_at`) VALUES
(1, 'hidwi', 'sFWf', 'jithin@gmail.com', '08547216322', 'Emergency', '2025-03-26', '2025-03-26 10:13:44', '2025-03-26 10:13:44'),
(2, 'jacky', 'adqd', 'manu@gmail.com', 'CWCW', 'Emergency', '2025-03-26', '2025-03-26 10:20:34', '2025-03-26 10:20:34');

-- --------------------------------------------------------

--
-- Table structure for table `dog_training_service`
--

CREATE TABLE `dog_training_service` (
  `Service_ID` int(11) NOT NULL,
  `pet_name` varchar(255) NOT NULL,
  `pet_type` varchar(50) NOT NULL,
  `breed` varchar(100) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `special_instructions` text DEFAULT NULL,
  `client_name` varchar(255) NOT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `service_name` varchar(255) NOT NULL,
  `service_description` text DEFAULT NULL,
  `trainer_name` varchar(255) NOT NULL,
  `appointment_date` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dog_training_service`
--

INSERT INTO `dog_training_service` (`Service_ID`, `pet_name`, `pet_type`, `breed`, `age`, `special_instructions`, `client_name`, `contact_email`, `contact_phone`, `address`, `service_name`, `service_description`, `trainer_name`, `appointment_date`, `created_at`) VALUES
(1, 'awdasf', 'Dog', 'asdvd', 1, 'swDFERB', 'ADSF', 'jithin@gmail.com', 'ADSFFBS', 'VHHBJNKM', 'Basic Obedience', 'sqwdfegr', 'Jane Smith', '2025-03-26 19:36:00', '2025-03-26 14:06:39'),
(2, 'awdasf', 'Dog', 'asdvd', 1, 'swDFERB', 'ADSF', 'jithin@gmail.com', 'ADSFFBS', 'VHHBJNKM', 'Basic Obedience', 'sqwdfegr', 'Jane Smith', '2025-03-26 19:36:00', '2025-03-26 14:08:20'),
(3, 'awdasf', 'Dog', 'asdvd', 1, 'swDFERB', 'ADSF', 'jithin@gmail.com', 'ADSFFBS', 'VHHBJNKM', 'Basic Obedience', 'sqwdfegr', 'Jane Smith', '2025-03-26 19:36:00', '2025-03-26 14:09:22');

-- --------------------------------------------------------

--
-- Table structure for table `dog_walking_service`
--

CREATE TABLE `dog_walking_service` (
  `id` int(11) NOT NULL,
  `dog_name` varchar(255) NOT NULL,
  `walk_date` datetime NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `walk_duration` int(11) NOT NULL,
  `walk_instructions` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dog_walking_service`
--

INSERT INTO `dog_walking_service` (`id`, `dog_name`, `walk_date`, `client_name`, `contact_email`, `contact_phone`, `address`, `walk_duration`, `walk_instructions`) VALUES
(1, 'awd', '2025-03-26 15:13:00', 'tyyvubhin', '', 'hv bjn', 'cyvbuni', 89, 'exrctvybun');

-- --------------------------------------------------------

--
-- Table structure for table `pets`
--

CREATE TABLE `pets` (
  `pet_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `breed` varchar(100) NOT NULL,
  `age` int(11) NOT NULL,
  `specialty` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('available','adopted') DEFAULT 'available',
  `donor_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `special_instructions` text DEFAULT NULL,
  `category` enum('dog','cat','bird',' reptile','small_animal','other') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pets`
--

INSERT INTO `pets` (`pet_id`, `name`, `breed`, `age`, `specialty`, `image`, `status`, `donor_id`, `created_at`, `special_instructions`, `category`) VALUES
(1, 'jacky', 'Parrot', 21, 'Talking Ability\r\n', 'uploads/75152276_1.jpg', 'available', 2, '2025-03-10 19:01:47', 'social birds, and  usually very intelligent', 'bird'),
(2, 'Angel', 'White cockatoo', 21, 'love spending time with their caretakers', 'uploads/210505577_0.jpg', 'available', 2, '2025-03-10 19:15:10', 'wdferg', 'bird'),
(3, 'poppy', 'Golden Retriever', 21, 'The Golden Retriever is a gentle dog with a level disposition, and usually adapts well to family life. They love to be involved in all matters, whether indoors or outdoors.', 'uploads\\Golden.jpg', 'available', 2, '2025-03-10 19:20:47', ' intelligent, eager-to-please, and versatile working dogs', 'dog'),
(4, 'marco', 'tabby cat', 2, 'Tabbies are very good at expressing their feelings', 'uploads/marco.jpg', 'available', 2, '2025-03-10 19:21:30', '', 'cat'),
(5, 'Bella', 'tabby cat', 1, 'Tabbies are very good at expressing their feelings', 'uploads/meow.jpg', 'available', 2, '2025-03-10 19:24:46', 'we', 'cat'),
(6, 'wed', 'asd', 2, NULL, 'uploads/210513381_0.jpg', 'available', 2, '2025-03-10 19:30:16', 'wedf', 'bird'),
(7, 'browdy', 'pug', 1, NULL, 'uploads/pug.jpg', 'available', 2, '2025-03-10 19:33:23', 'QS', 'dog'),
(8, 'Bunny', 'Rabbit', 1, 'gentle handling, they are generally quite tame, playful, and entertaining to watch.', 'uploads/Rabbit.jpg', 'available', 2, '2025-03-11 04:20:51', 'EYFD2GF98IGEFCDXFD', 'small_animal'),
(9, 'Goosey', 'Goose', 2, NULL, 'uploads/Geese.jpg', 'available', 5, '2025-03-11 10:35:40', 'Lifespan and Commitment Geese are a long-term commitment', 'bird'),
(10, 'cocko', 'greman', 1, NULL, 'uploads/German-Shepherd-dog-Alsatian.jpg', 'available', 7, '2025-03-12 18:18:15', 'cute pie', 'dog'),
(11, 'maxxy', 'cat', 1, NULL, 'uploads/yzV5i2F35i9RozwSeFLPJV-970-80.jpg.jpg', 'available', 6, '2025-03-12 19:16:07', 'no rules', 'cat'),
(12, 'jbin', 'husky', 1, NULL, 'uploads/SIBERIAN-HUSKY-PORTRAIT.png', 'available', 10, '2025-03-13 16:12:04', 'Brave', 'dog'),
(13, 'rocky', 'husky', 2, NULL, 'uploads/alaskan-husky-dogs.png', 'available', 6, '2025-03-13 16:47:35', 'lazy', 'dog'),
(14, 'ful', 'vbn', 2, NULL, 'uploads/Rabbit2.jpg', 'available', 16, '2025-03-18 04:57:59', 'helooo', 'dog'),
(15, 'lucy', ' Orange Tabby Cat ', 1, NULL, 'uploads/pexels-lina-1741205.jpg', 'available', 30, '2025-03-26 14:22:36', ' Orange Tabby Cat ', 'cat'),
(16, 'Jennifer', 'Tabby Cat ', -2, NULL, 'uploads/pexels-sunish-chukkath-3443543-5139252.jpg', 'available', 30, '2025-03-26 14:25:32', ' Tabby Cat ', 'cat'),
(17, 'Kate', ' Tabby Cat ', 0, NULL, 'uploads/pexels-wojciech-kumpicki-1084687-2071882.jpg', 'available', 30, '2025-03-26 14:27:09', 'sharpe eye', 'cat');

-- --------------------------------------------------------

--
-- Table structure for table `pet_boarding_service`
--

CREATE TABLE `pet_boarding_service` (
  `id` int(11) NOT NULL,
  `pet_name` varchar(100) NOT NULL,
  `pet_type` varchar(50) NOT NULL,
  `breed` varchar(100) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `special_instructions` text DEFAULT NULL,
  `client_name` varchar(100) NOT NULL,
  `contact_email` varchar(100) DEFAULT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `boarding_start_date` datetime NOT NULL,
  `boarding_end_date` datetime NOT NULL,
  `total_cost` decimal(10,2) DEFAULT NULL,
  `payment_status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pet_boarding_service`
--

INSERT INTO `pet_boarding_service` (`id`, `pet_name`, `pet_type`, `breed`, `age`, `special_instructions`, `client_name`, `contact_email`, `contact_phone`, `address`, `boarding_start_date`, `boarding_end_date`, `total_cost`, `payment_status`, `created_at`, `updated_at`) VALUES
(1, 'ew', 'Cat', 'Sac', 2, '', 'asac', 'scac@gmail.com', 'SX', '', '2025-03-21 20:00:00', '2025-03-23 20:00:00', NULL, NULL, '2025-03-21 14:30:21', '2025-03-21 14:30:21'),
(2, 'jacky', 'Cat', 'persian', 1, 'don\\\'t give choclate', 'bibin', 'bibin@gmail.com', '9544736726', 'ugfheidjcals', '2025-03-26 11:53:00', '2025-03-27 04:48:00', NULL, NULL, '2025-03-26 06:18:28', '2025-03-26 06:18:28'),
(3, 'hidwi', 'Cat', 'WD', 2, 'AFFW', 'wsfd', 'jithin@gmail.com', '08547216322', 'ADFEF', '2025-03-26 14:46:00', '2025-03-27 14:46:00', 100.00, 'Paid', '2025-03-26 09:16:52', '2025-03-26 09:16:52'),
(4, 'hidwi', 'Cat', 'WD', 2, 'AFFW', 'wsfd', 'jithin@gmail.com', '08547216322', 'ADFEF', '2025-03-26 14:46:00', '2025-03-27 14:46:00', 100.00, 'Paid', '2025-03-26 09:18:44', '2025-03-26 09:18:44'),
(5, 'yguhjk', 'Cat', 'rtyu', 2, 'zexrctvybunrxcytvubinj', 'xcvbn', 'jithin@gmail.com', '08547216322', 'cfgvhbnjkm', '2025-03-26 15:15:00', '2025-03-28 15:15:00', NULL, NULL, '2025-03-26 09:45:14', '2025-03-26 09:45:14'),
(6, 'bjnkm', 'Cat', 'rtyu', 2, 'zexrctvybunrxcytvubinj', 'xcvbn', 'jithin@gmail.com', '08547216322', 'cfgvhbnjkm', '2025-03-26 15:15:00', '2025-03-28 15:15:00', NULL, NULL, '2025-03-26 09:47:02', '2025-03-26 09:47:02');

-- --------------------------------------------------------

--
-- Table structure for table `pet_grooming_service`
--

CREATE TABLE `pet_grooming_service` (
  `service_id` int(11) NOT NULL,
  `pet_name` varchar(255) NOT NULL,
  `breed` varchar(255) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `special_instructions` text DEFAULT NULL,
  `client_name` varchar(255) NOT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `service_name` varchar(255) NOT NULL,
  `service_description` text DEFAULT NULL,
  `service_price` decimal(10,2) NOT NULL,
  `groomer_name` varchar(255) DEFAULT NULL,
  `appointment_date` datetime NOT NULL,
  `status` varchar(50) DEFAULT 'Scheduled',
  `total_amount` decimal(10,2) DEFAULT NULL,
  `payment_status` varchar(50) DEFAULT 'Pending',
  `payment_date` datetime DEFAULT NULL,
  `pet_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pet_grooming_service`
--

INSERT INTO `pet_grooming_service` (`service_id`, `pet_name`, `breed`, `age`, `special_instructions`, `client_name`, `contact_email`, `contact_phone`, `address`, `service_name`, `service_description`, `service_price`, `groomer_name`, `appointment_date`, `status`, `total_amount`, `payment_status`, `payment_date`, `pet_type`) VALUES
(3, 'joe', 'pershan', 2, '', 'aswin', 'vaishakhov2003@gmail.com', '9544736726', 'wefg', 'Bath', 'wdefgs', 0.00, 'Jane Smith', '2025-03-21 13:30:00', 'Scheduled', NULL, 'Pending', NULL, 'Cat'),
(4, 'ruby', 'cat', 1, 'no water', 'bibin', 'bibin@gmail.com', '9544736726', '54', 'Haircut', 'no trimmer', 0.00, 'Emily Brown', '2025-03-21 19:38:00', 'Scheduled', NULL, 'Pending', NULL, 'Cat'),
(5, 'ruby', 'cat', 1, 'adaff', 'bibin', 'bibin@gmail.com', '9544736726', 'ascs', 'Nail Trimming', 'ASV', 0.00, 'Jane Smith', '2025-03-26 14:42:00', 'Scheduled', NULL, 'Pending', NULL, 'Dog');

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `request_id` int(11) NOT NULL,
  `adopter_id` int(11) NOT NULL,
  `pet_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `address` varchar(255) NOT NULL,
  `living_situation` enum('own','rent','other') NOT NULL,
  `pets_at_home` text DEFAULT NULL,
  `pet_type` enum('dog','cat','other') NOT NULL,
  `pet_age_range` enum('puppy','kitten','adult','senior') NOT NULL,
  `pet_breed` varchar(255) DEFAULT NULL,
  `pet_gender` enum('male','female','no_preference') NOT NULL,
  `pet_temperament` text DEFAULT NULL,
  `financial_ready` enum('yes','no') NOT NULL,
  `adopt_reason` text NOT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`request_id`, `adopter_id`, `pet_id`, `full_name`, `phone`, `email`, `dob`, `address`, `living_situation`, `pets_at_home`, `pet_type`, `pet_age_range`, `pet_breed`, `pet_gender`, `pet_temperament`, `financial_ready`, `adopt_reason`, `reason`, `status`, `created_at`) VALUES
(1, 8, 10, 'Arjun', '8547216322', 'arjun@gmail.com', '2005-01-11', 'wayanad', 'rent', 'no', 'cat', 'kitten', '', 'male', 'no', 'yes', 'i love this german', NULL, 'approved', '2025-03-12 18:42:17'),
(2, 9, 10, 'Nandhu', '8547216322', 'nandhu@gmail.com', '2003-01-12', 'wayanad', 'own', 'no', 'dog', 'adult', 'german', 'male', 'no', 'yes', 'i hate catss', NULL, 'rejected', '2025-03-12 19:03:19'),
(3, 8, 11, 'Arjun', '8547216325', 'arjun@gmail.com', '2025-03-13', 'wayanad', 'own', 'no', 'cat', 'kitten', 'german', 'female', 'no', 'yes', 'cat lovr', NULL, 'approved', '2025-03-12 19:18:35'),
(4, 6, 6, 'Arjun', '8547216325', 'arjun@gmail.com', '2005-02-10', 'wayanad', 'own', 'no', 'cat', 'kitten', 'german', 'female', 'no', 'yes', 'ubbuis sfauba', NULL, 'approved', '2025-03-18 04:49:17'),
(5, 16, 5, 'sunitha', '944654321', 'ggggg@hiii', '0087-03-12', 'kannur', 'own', 'no', 'cat', 'puppy', 'german', 'male', 'no', 'yes', ' sss', NULL, 'pending', '2025-03-18 05:01:29'),
(6, 17, 3, 'vaishakh', '9544736726', 'vaishakhov2003@gmail.com', '2003-08-06', 'Orkkol(H),Erikkulam(P.O),Kasargod', 'own', 'yes', 'dog', 'puppy', 'german shepherd', 'male', 'no', 'yes', 'because im going through a breakup and im so lonely', NULL, 'approved', '2025-03-21 08:35:06'),
(7, 29, 1, 'rinsha', '8845745868', 'labdbms123@gmail.com', '2003-02-12', 'ksd', 'own', 'no', 'other', 'senior', '', 'female', '', 'yes', 'I love pets', NULL, 'pending', '2025-03-26 07:14:26');

--
-- Triggers `requests`
--
DELIMITER $$
CREATE TRIGGER `insert_adopt_history_after_approval` AFTER UPDATE ON `requests` FOR EACH ROW BEGIN
    IF NEW.status = 'approved' THEN
        INSERT INTO adopt_history (adopter_id, pet_id, adopt_date, status)
        VALUES (NEW.adopter_id, NEW.pet_id, NOW(), 'approved');
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `success_stories`
--

CREATE TABLE `success_stories` (
  `story_id` int(11) NOT NULL,
  `pet_id` int(11) NOT NULL,
  `adopter_id` int(11) NOT NULL,
  `story` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `success_stories`
--

INSERT INTO `success_stories` (`story_id`, `pet_id`, `adopter_id`, `story`, `image`, `created_at`) VALUES
(1, 11, 8, 'I had been looking for a rescue dog for quite some time before finally finding Jackson through Mia. I had become discouraged with my search running into many obstacles in the rescue community. I had the exact opposite experience with Mia.\r\n\r\nWhen I applied online for Jackson, I got a response within hours. I met Jackson for the first time the following day. Another couple was also competing to adopt him, but Mia was forthright about what qualities and environment Jackson would need to make the best transition. I appreciated her honesty and was so glad that I was ultimately selected two days later.', 'uploads/success_stories/67d47f1ec8622_manuela-and-moxi-blue-man-dog (1).jpg', '2025-03-14 19:10:22');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('adopter','donor') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `role`, `created_at`, `reset_token`, `reset_token_expiry`) VALUES
(2, 'abinnr', 'abinnr12345@gmail.com', '$2y$10$4zjjq3UuIzXUuQ.ZiKGSF.sHgONjy8rdnOdLZQEmI.cFNowXN60R6', 'donor', '2025-03-10 18:48:52', NULL, NULL),
(3, 'jithin', 'albinkgeorge4@gmail.com', '$2y$10$LrPR81fIr.yCUU69m9CG.OLpWChc0EJpDkHnHuURZrbWBcNHZam0m', 'adopter', '2025-03-10 20:07:11', '705e4a3db6650d765044d2c8af76298670f793a640758337d0e68a306c2c054d', '2025-03-21 10:30:19'),
(4, 'abhay', 'abhay@gmail.com', '$2y$10$DDUNe48AZziorZkX4SyRDuJYHCzCO0/FQPQ.xCFpOAtfzgjD.voIu', 'adopter', '2025-03-11 03:37:50', NULL, NULL),
(5, 'naju', 'naju@gmail.com', '$2y$10$PAC6N8UlvNihvKKcxnn47e7xpXbratGoIMNupZmy5fXlm4xeWIgzq', 'donor', '2025-03-11 04:15:26', NULL, NULL),
(6, 'manu', 'manu@gmail.com', '$2y$10$DBkoClggnMPN58CbzCUxaOpR6IEy4yTdBfYNiPjQFlp9aiblMf3bu', 'adopter', '2025-03-11 10:32:29', 'e39c1fc8ba8b41516aeb781c81ff400623583643eb7e3cae73c742aebaf13920', '2025-03-21 10:14:58'),
(7, 'bibin', 'bibin@gmail.com', '$2y$10$qH6Mbr0tvQceoqv7KJhroOALYJluhJZhZ2sehge2VHHMSQX3OazSi', 'adopter', '2025-03-12 17:41:21', NULL, NULL),
(8, 'arjun', 'arjun@gmail.com', '$2y$10$I3baTyn0rQRIuLb9oTTWcugZQ/vqML35q3sSRPWs.MGx6tYzN5LR.', 'adopter', '2025-03-12 18:40:05', NULL, NULL),
(9, 'nandhu', 'nandhu@gmail.com', '$2y$10$u0w3zhLps/sK2yK6d64ASeJEHKgF4qXEQZ2reUWKUe7NSzNrHZkjm', 'adopter', '2025-03-12 19:01:57', NULL, NULL),
(10, 'yoosuf', 'yoosuf@gmail.com', '$2y$10$gKUOB3oNbZjzhTZDlcF4KOnXgR.XoFNvhDWbDC5ZwslYBvrInh4qe', 'adopter', '2025-03-13 16:09:11', NULL, NULL),
(11, 'jeswin', 'jeswin@gmail.com', '$2y$10$1jEXwFgtNvb/cmF6H3o1EezOOGKUWME3r8hM9dZKlc94lJDhDRXqS', 'adopter', '2025-03-13 19:08:56', NULL, NULL),
(12, 'aswin', 'aswin@gmail.com', '$2y$10$jdRPr8FhBPZ4myvYcvwkUO.aNe1fbXcyr6yi1Nzc1N9kU5zTLj6w.', 'adopter', '2025-03-13 19:11:18', NULL, NULL),
(13, 'athul', 'athul@gmail.com', '$2y$10$4zjjq3UuIzXUuQ.ZiKGSF.sHgONjy8rdnOdLZQEmI.cFNowXN60R6', 'adopter', '2025-03-13 19:15:21', NULL, NULL),
(14, 'siva', 'siva@gmail.com', '$2y$10$yruGVuBZ/C7uBN9OARZHc.ZVIscB3RRT6afKMsazcDmZuiDBtVnDK', 'adopter', '2025-03-13 19:15:55', NULL, NULL),
(15, 'vishnu', 'vishnu@gmail.com', '$2y$10$yG3zx3edshdrcbkBsyi8XOKobwWmTAVkQ3MECBHeSRzj.pAlcjpcm', 'adopter', '2025-03-13 19:17:21', NULL, NULL),
(16, 'staa', 'suni@gmail.com', '$2y$10$nD//rnDjVYhJWsCzLaiMvO8puA9FDDNINoZ2O3B5sbU1aZna9Z.WW', 'adopter', '2025-03-18 04:55:55', NULL, NULL),
(17, 'vaishakh', 'vaishakhov2003@gmail.com', '$2y$10$ikOYw87EbgF4CdPTbxoJz.MI.eLTPr8CtqoCivpLa7qCs9db5K5BO', 'adopter', '2025-03-21 08:31:30', NULL, NULL),
(23, 'abhay', 'abhrajrj@gmail.com', '$2y$10$0gifoXD5PBU4TFkrhweYqOSmHl1iTiz.Lk.2oLNn5qvpjXviR/gqK', 'adopter', '2025-03-25 18:45:53', NULL, NULL),
(28, 'jibin', 'alwaysloser94@gmail.com', '$2y$10$tyFRE8R9IlWBKDHjF4YeGOsOysAMb1JUBLI7OiWUiJDDcF6LjuDj.', 'adopter', '2025-03-25 19:07:36', NULL, NULL),
(29, 'rinsha', 'labdbms123@gmail.com', '$2y$10$bsy.1RazGkZ.XDuYc17V.eAzjuRvu1nInq9C02KHSAV1tZZD6AjJq', 'adopter', '2025-03-26 07:10:24', NULL, NULL),
(30, 'tony', 'tony@gmail.com', '$2y$10$FYnE40qsec9Npab.iMRnt.rW9937mZnMJ/HNis6Y7bGeMfjfPvK8i', 'adopter', '2025-03-26 14:19:34', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adopt_history`
--
ALTER TABLE `adopt_history`
  ADD PRIMARY KEY (`adopt_history_id`),
  ADD KEY `adopter_id` (`adopter_id`),
  ADD KEY `pet_id` (`pet_id`);

--
-- Indexes for table `consultations`
--
ALTER TABLE `consultations`
  ADD PRIMARY KEY (`consultation_id`);

--
-- Indexes for table `dog_training_service`
--
ALTER TABLE `dog_training_service`
  ADD PRIMARY KEY (`Service_ID`);

--
-- Indexes for table `dog_walking_service`
--
ALTER TABLE `dog_walking_service`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pets`
--
ALTER TABLE `pets`
  ADD PRIMARY KEY (`pet_id`),
  ADD KEY `fk_donor_id` (`donor_id`);

--
-- Indexes for table `pet_boarding_service`
--
ALTER TABLE `pet_boarding_service`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pet_grooming_service`
--
ALTER TABLE `pet_grooming_service`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `adopter_id` (`adopter_id`),
  ADD KEY `pet_id` (`pet_id`);

--
-- Indexes for table `success_stories`
--
ALTER TABLE `success_stories`
  ADD PRIMARY KEY (`story_id`),
  ADD KEY `pet_id` (`pet_id`),
  ADD KEY `adopter_id` (`adopter_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adopt_history`
--
ALTER TABLE `adopt_history`
  MODIFY `adopt_history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `consultations`
--
ALTER TABLE `consultations`
  MODIFY `consultation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dog_training_service`
--
ALTER TABLE `dog_training_service`
  MODIFY `Service_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `dog_walking_service`
--
ALTER TABLE `dog_walking_service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pets`
--
ALTER TABLE `pets`
  MODIFY `pet_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `pet_boarding_service`
--
ALTER TABLE `pet_boarding_service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pet_grooming_service`
--
ALTER TABLE `pet_grooming_service`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `success_stories`
--
ALTER TABLE `success_stories`
  MODIFY `story_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `adopt_history`
--
ALTER TABLE `adopt_history`
  ADD CONSTRAINT `adopt_history_ibfk_1` FOREIGN KEY (`adopter_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `adopt_history_ibfk_2` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`pet_id`);

--
-- Constraints for table `pets`
--
ALTER TABLE `pets`
  ADD CONSTRAINT `fk_donor_id` FOREIGN KEY (`donor_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pets_ibfk_1` FOREIGN KEY (`donor_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`adopter_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `requests_ibfk_2` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`pet_id`);

--
-- Constraints for table `success_stories`
--
ALTER TABLE `success_stories`
  ADD CONSTRAINT `success_stories_ibfk_1` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`pet_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `success_stories_ibfk_2` FOREIGN KEY (`adopter_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
