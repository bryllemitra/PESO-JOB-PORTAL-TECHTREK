-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 24, 2025 at 03:21 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `job_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `about`
--

CREATE TABLE `about` (
  `id` int(11) NOT NULL,
  `cover_photo` varchar(255) DEFAULT NULL,
  `mission` text DEFAULT NULL,
  `vision` text DEFAULT NULL,
  `hero_text` text DEFAULT 'Empowering the community through employment opportunities.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `about`
--

INSERT INTO `about` (`id`, `cover_photo`, `mission`, `vision`, `hero_text`) VALUES
(1, 'ppp.jpg', 'Our mission is to empower individuals and businesses by providing a seamless, inclusive, and efficient platform that connects job seekers with meaningful employment opportunities. We strive to bridge the gap between talent and opportunity, fostering economic growth and personal development within our community. Through innovative technology, personalized support, and a commitment to excellence, we aim to make the job search process transparent, accessible, and rewarding for everyone.', 'Our vision is to become the leading job portal that transforms the way people find work and employers discover talent. We envision a future where every individual has access to opportunities that align with their skills and aspirations, and every organization can build a workforce that drives success. By fostering collaboration, diversity, and innovation, we aim to create a thriving ecosystem that benefits job seekers, employers, and the community at large.', 'Welcome to PESO Job Portal, your trusted partner in connecting talent with opportunity. We are dedicated to empowering job seekers and employers alike by providing a dynamic and user-friendly platform that simplifies the hiring process. Our mission is to bridge the gap between skilled professionals and organizations seeking top talent, fostering growth and success for individuals and businesses across Zamboanga City.');

-- --------------------------------------------------------

--
-- Table structure for table `ads`
--

CREATE TABLE `ads` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image_file` varchar(255) NOT NULL,
  `link_url` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ads`
--

INSERT INTO `ads` (`id`, `title`, `description`, `image_file`, `link_url`, `created_at`) VALUES
(15, '𝙋𝙀𝙎𝙊 𝙕𝙖𝙢𝙗𝙤𝙖𝙣𝙜𝙖 𝙎𝙩𝙧𝙚𝙣𝙜𝙩𝙝𝙚𝙣𝙨 𝙍𝙚𝙘𝙧𝙪𝙞𝙩𝙢𝙚𝙣𝙩 𝘾𝙤𝙡𝙡𝙖𝙗𝙤𝙧𝙖𝙩𝙞𝙤𝙣 𝙬𝙞𝙩𝙝 𝙋𝙚𝙥𝙨𝙞-𝘾𝙤𝙡𝙖 𝙋𝙧𝙤𝙙𝙪𝙘𝙩𝙨 𝙋𝙝𝙞𝙡𝙞𝙥𝙥𝙞𝙣𝙚𝙨, 𝙄𝙣𝙘', 'PESO Zamboanga recently visited 𝙋𝙚𝙥𝙨𝙞-𝘾𝙤𝙡𝙖 𝙋𝙧𝙤𝙙𝙪𝙘𝙩𝙨 𝙋𝙝𝙞𝙡𝙞𝙥𝙥𝙞𝙣𝙚𝙨, 𝙄𝙣𝙘, led by 𝙃𝙍 𝙈𝙖𝙣𝙖𝙜𝙚𝙧, 𝙈𝙨. 𝙅𝙪𝙙𝙞𝙩𝙝 𝘽𝙪𝙘𝙤𝙮, to discuss employment facilitation initiatives.\r\nThe meeting covered the company’s profile and hiring needs, along with 𝙋𝙀𝙎𝙊’𝙨 𝙜𝙪𝙞𝙙𝙚𝙡𝙞𝙣𝙚𝙨 𝙛𝙤𝙧 𝙟𝙤𝙗 𝙛𝙖𝙞𝙧𝙨, 𝙟𝙤𝙗 𝙫𝙖𝙘𝙖𝙣𝙘𝙮 𝙥𝙤𝙨𝙩𝙞𝙣𝙜𝙨, 𝙩𝙝𝙚 𝙇𝙤𝙘𝙖𝙡 𝙍𝙚𝙘𝙧𝙪𝙞𝙩𝙢𝙚𝙣𝙩 𝘼𝙘𝙩𝙞𝙫𝙞𝙩𝙮 (𝙇𝙍𝘼) 𝙥𝙧𝙤𝙘𝙚𝙨𝙨, 𝙖𝙣𝙙 𝙤𝙩𝙝𝙚𝙧 𝙚𝙢𝙥𝙡𝙤𝙮𝙢𝙚𝙣𝙩 𝙥𝙧𝙤𝙜𝙧𝙖𝙢𝙨. This partnership aims to enhance job accessibility and streamline recruitment efforts for jobseekers in the city.', 'ad_67b458b3b0fa08.68602869.jpg', 'https://www.facebook.com/PESOZamboangaCity2022/posts/pfbid0Ec7wDruVa5uz3VdFDK8oGkNrAghSjAQaVa5Yk2nihXkJmdrBZzsS8bpoFwp1h4iCl', '2025-02-18 09:53:55'),
(16, '𝘾𝘼𝙇𝙇 𝙁𝙊𝙍 𝘼𝙋𝙋𝙇𝙄𝘾𝘼𝙉𝙏𝙎: 𝙎𝙥𝙚𝙘𝙞𝙖𝙡 𝙋𝙧𝙤𝙜𝙧𝙖𝙢 𝙛𝙤𝙧 𝙀𝙢𝙥𝙡𝙤𝙮𝙢𝙚𝙣𝙩 𝙤𝙛 𝙎𝙩𝙪𝙙𝙚𝙣𝙩𝙨 (𝙎𝙋𝙀𝙎)', '𝙏𝙝𝙚 𝙋𝙪𝙗𝙡𝙞𝙘 𝙀𝙢𝙥𝙡𝙤𝙮𝙢𝙚𝙣𝙩 𝙎𝙚𝙧𝙫𝙞𝙘𝙚 𝙊𝙛𝙛𝙞𝙘𝙚 (𝙋𝙀𝙎𝙊) - 𝙕𝙖𝙢𝙗𝙤𝙖𝙣𝙜𝙖 𝘾𝙞𝙩𝙮 is now accepting applicants for the 𝙎𝙥𝙚𝙘𝙞𝙖𝙡 𝙋𝙧𝙤𝙜𝙧𝙖𝙢 𝙛𝙤𝙧 𝙀𝙢𝙥𝙡𝙤𝙮𝙢𝙚𝙣𝙩 𝙤𝙛 𝙎𝙩𝙪𝙙𝙚𝙣𝙩𝙨 (𝙎𝙋𝙀𝙎), a program designed to provide temporary employment to deserving students, out-of-school youth, and dependents of displaced workers. If you’re looking for an opportunity to augment your finances and continue your education, this program is for you!', 'ad_67b458f3051418.02438174.png', 'https://www.facebook.com/PESOZamboangaCity2022/posts/pfbid02YJ9adwP41gRurCgBL5W4hYENKfmnmsAAkBogzs778BRQSXZ5hb8pUXrXsDExeMAtl', '2025-02-18 09:54:59'),
(22, ' 𝙋𝙧𝙚𝙨𝙚𝙣𝙩𝙞𝙣𝙜 𝙩𝙝𝙚 𝙀𝙢𝙥𝙡𝙤𝙮𝙚𝙧𝙨 & 𝙅𝙤𝙗 𝙊𝙥𝙥𝙤𝙧𝙩𝙪𝙣𝙞𝙩𝙞𝙚𝙨 𝙖𝙩 𝙩𝙝𝙚 88𝙩𝙝 𝘿𝙞𝙖 𝙙𝙚 𝙕𝙖𝙢𝙗𝙤𝙖𝙣𝙜𝙖 𝙅𝙤𝙗 𝙁𝙖𝙞𝙧!', 'PESO Zamboanga is excited to introduce the 𝙡𝙤𝙘𝙖𝙡 𝙖𝙣𝙙 𝙤𝙫𝙚𝙧𝙨𝙚𝙖𝙨 𝙚𝙢𝙥𝙡𝙤𝙮𝙚𝙧𝙨 participating in this year’s Dia de Zamboanga Job Fair on February 26, 2025, at KCC Mall de Zamboanga – East Wing! Get ready to explore 4,421 𝙡𝙤𝙘𝙖𝙡 𝙖𝙣𝙙 7,972 𝙤𝙫𝙚𝙧𝙨𝙚𝙖𝙨 𝙤𝙛 𝙟𝙤𝙗 𝙫𝙖𝙘𝙖𝙣𝙘𝙞𝙚𝙨 in various industries. \r\nCheck out the list of employers and their available job openings! Your next career move could be right here!\r\nStay tuned for updates, and don’t forget to bring your résumé, valid IDs, 2X2 picture and other requirements for a chance to get hired on the spot!', 'ad_67bbb9fd589af3.78316329.png', 'https://www.facebook.com/PESOZamboangaCity2022/posts/pfbid0CVG9cWJk2paoTX26qfz9nZENnp2WxKUy1J8MkFcLcqARvKXxRgFFqE1Mk1fKNxbrl', '2025-02-24 00:14:53');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `content`, `created_at`) VALUES
(1, 'New Job Openings', 'We are excited to announce new job openings in various industries, ranging from tech and healthcare to finance and customer service. These positions offer competitive salaries, benefits, and opportunities for growth in dynamic, supportive work environments. Whether you\'re looking to take the next step in your career or explore a new field, we have something for everyone. Stay tuned for updates on available roles, and don\'t miss out—apply today and take the first step towards your future!', '2025-02-12 13:29:41'),
(2, 'Upcoming Career Fair', 'Join us for our annual career fair happening next month, where you’ll have the chance to meet top employers, network with industry professionals, and explore exciting career opportunities. Whether you\'re a recent graduate or an experienced professional looking for a new challenge, this event is designed to help you make valuable connections and take the next step in your career. Register now to secure your spot and get ready to unlock new possibilities for your future!', '2025-02-12 13:29:41'),
(3, 'PESO Office Hours Update', 'Our office hours have been updated to better serve you. We encourage you to check the updated schedule on our website for the most accurate information regarding availability. Whether you\'re planning a visit or need assistance during specific hours, the new schedule ensures that we are here when you need us. Be sure to take a look and plan accordingly!', '2025-02-12 13:29:41');

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `resume_file` varchar(255) DEFAULT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `is_read` tinyint(1) DEFAULT 0,
  `status_updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `dismissed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `user_id`, `job_id`, `applied_at`, `resume_file`, `status`, `is_read`, `status_updated_at`, `dismissed`) VALUES
(267, 21, 25, '2025-02-23 23:53:11', '../uploads/resumes/CV-Salido.docx', 'pending', 0, '2025-02-23 23:53:11', 0),
(268, 21, 24, '2025-02-23 23:53:15', '../uploads/resumes/CV-Salido.docx', 'pending', 0, '2025-02-23 23:53:15', 0),
(269, 21, 23, '2025-02-23 23:53:23', '../uploads/resumes/CV-Salido.docx', 'pending', 0, '2025-02-23 23:53:23', 0),
(270, 21, 22, '2025-02-23 23:53:28', '../uploads/resumes/CV-Salido.docx', 'pending', 0, '2025-02-23 23:53:28', 0),
(271, 21, 21, '2025-02-23 23:53:32', '../uploads/resumes/CV-Salido.docx', 'pending', 0, '2025-02-23 23:53:32', 0),
(272, 57, 25, '2025-02-24 10:48:26', '../uploads/resumes/resume.pdf', 'pending', 0, '2025-02-24 10:48:26', 0),
(274, 21, 59, '2025-02-24 14:04:31', '../uploads/resumes/CV-Salido.docx', 'pending', 0, '2025-02-24 14:04:31', 0);

-- --------------------------------------------------------

--
-- Table structure for table `barangay`
--

CREATE TABLE `barangay` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barangay`
--

INSERT INTO `barangay` (`id`, `name`) VALUES
(1, 'Arena Blanco'),
(2, 'Ayala'),
(3, 'Baliwasan'),
(4, 'Baluno'),
(5, 'Barangay Zone I'),
(6, 'Barangay Zone II'),
(7, 'Barangay Zone III'),
(8, 'Barangay Zone IV'),
(9, 'Boalan'),
(10, 'Bolong'),
(11, 'Buenavista'),
(12, 'Bunguiao'),
(13, 'Busay'),
(14, 'Cabaluay'),
(15, 'Cabatangan'),
(16, 'Cacao'),
(17, 'Calabasa'),
(18, 'Calarian'),
(19, 'Camino Nuevo'),
(20, 'Campo Islam'),
(21, 'Canelar'),
(22, 'Capisan'),
(23, 'Cawit'),
(24, 'Culianan'),
(25, 'Curuan'),
(26, 'Dita'),
(27, 'Divisoria'),
(28, 'Dulian (Upper Bunguiao)'),
(29, 'Dulian (Upper Pasonanca)'),
(30, 'Guisao'),
(31, 'Guiwan'),
(32, 'Kasanyangan'),
(33, 'La Paz'),
(34, 'Labuan'),
(35, 'Lamisahan'),
(36, 'Landang Gua'),
(37, 'Landang Laum'),
(38, 'Lanzones'),
(39, 'Lapakan'),
(40, 'Latuan'),
(41, 'Licomo'),
(42, 'Limaong'),
(43, 'Limpapa'),
(44, 'Lubigan'),
(45, 'Lumayang'),
(46, 'Lumbangan'),
(47, 'Lunzuran'),
(48, 'Maasin'),
(49, 'Malagutay'),
(50, 'Mampang'),
(51, 'Manalipa'),
(52, 'Mangusu'),
(53, 'Manicahan'),
(54, 'Mariki'),
(55, 'Mercedes'),
(56, 'Muti'),
(57, 'Pamucutan'),
(58, 'Pangapuyan'),
(59, 'Panubigan'),
(60, 'Pasilmanta'),
(61, 'Pasobolong'),
(62, 'Pasonanca'),
(63, 'Patalon'),
(64, 'Putik'),
(65, 'Quiniput'),
(66, 'Recodo'),
(67, 'Rio Hondo'),
(68, 'Salaan'),
(69, 'Sangali'),
(70, 'San Jose Cawa-Cawa'),
(71, 'San Jose Gusu'),
(72, 'San Roque'),
(73, 'Santa Barbara'),
(74, 'Santa Catalina'),
(75, 'Santa Maria'),
(76, 'Santo Niño'),
(77, 'Sibulao'),
(78, 'Sinubung'),
(79, 'Sinunuc'),
(80, 'Tagasilay'),
(81, 'Taguiti'),
(82, 'Talabaan'),
(83, 'Talisayan'),
(84, 'Talon-Talon'),
(85, 'Taluksangay'),
(86, 'Tetuan'),
(87, 'Tictapul'),
(88, 'Tigbalabag'),
(89, 'Tigtabon'),
(90, 'Tolosa'),
(91, 'Tugbungan'),
(92, 'Tulungatung'),
(93, 'Tumaga'),
(94, 'Tumalutap'),
(95, 'Tumitus'),
(96, 'Victoria'),
(97, 'Vitali'),
(98, 'Zambowood');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Accounting & Finance'),
(2, 'Administrative & Office Support'),
(3, 'Advertising & Marketing'),
(4, 'Architecture & Engineering'),
(5, 'Arts, Design & Entertainment'),
(6, 'Customer Service'),
(7, 'Education & Training'),
(8, 'Healthcare & Medical'),
(9, 'Hospitality & Tourism'),
(10, 'Human Resources & Recruitment'),
(11, 'Information Technology (IT)'),
(12, 'Legal & Compliance'),
(13, 'Manufacturing & Production'),
(14, 'Project Management'),
(15, 'Retail & Sales'),
(16, 'Science & Research'),
(17, 'Skilled Trades & Construction'),
(18, 'Supply Chain & Logistics'),
(19, 'Telecommunications'),
(20, 'Writing & Editing'),
(21, 'Business & Strategy'),
(22, 'Consulting'),
(23, 'Government & Public Administration'),
(24, 'Insurance'),
(25, 'Media & Communications'),
(26, 'Nonprofit & Social Services'),
(27, 'Real Estate'),
(28, 'Security & Law Enforcement'),
(29, 'Sports, Fitness & Recreation'),
(30, 'Transportation & Automotive'),
(31, 'General Labor & Construction Workers'),
(32, 'Public Relations & Communications'),
(33, 'E-commerce & Digital Marketing'),
(34, 'Environmental & Sustainability'),
(35, 'Energy & Utilities'),
(36, 'Aerospace & Aviation');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `subject`, `message`, `created_at`, `is_read`) VALUES
(17, 'Venard Jhon C. Salido', 'venard@gmail.com', 'Greetings', 'Have a nice day admin!', '2025-02-17 01:04:23', 1),
(18, 'Venard Jhon C. Salido', 'venard@gmail.com', 'HELP!', 'neeed work!', '2025-02-17 01:04:33', 1);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `category` varchar(100) NOT NULL,
  `category_id` int(11) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `responsibilities` text NOT NULL,
  `requirements` text NOT NULL,
  `preferred_qualifications` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `title`, `description`, `thumbnail`, `photo`, `created_at`, `category`, `category_id`, `location`, `responsibilities`, `requirements`, `preferred_qualifications`) VALUES
(21, 'LOOKING FOR A GRAPHIC DESIGNER!', 'As a Graphic Designer, you will be responsible for creating visually engaging designs that align with our brand and marketing objectives. You will work closely with cross-functional teams, including marketing, product, and content, to develop graphics for various platforms, including web, social media, print, and more.', 'uploads/graphics.jpg', NULL, '2025-02-19 05:44:12', 'Information Technology (IT)', 11, 'Bungiao', 'Create and design digital and print materials, such as brochures, websites, social media graphics, banners, email templates, and advertisements.\r\nDevelop concepts and designs based on project requirements and brand guidelines.\r\nCollaborate with marketing, content, and product teams to ensure design consistency across all channels.\r\nEdit and enhance images, videos, and other visual assets for use in marketing campaigns.\r\nStay up to date with industry trends and software to maintain high-quality, innovative designs.\r\nAssist in the creation of presentations, reports, and other internal materials.\r\nEnsure all designs are delivered on time and meet the quality standards set by the company.\r\nMaintain a well-organized design library and asset management system.', 'Bachelor\'s degree in Graphic Design, Visual Arts, or related field (or equivalent practical experience).\r\nProven experience as a Graphic Designer or in a similar role.\r\nProficiency in design software, including Adobe Creative Suite (Photoshop, Illustrator, InDesign, etc.) and other relevant tools.\r\nStrong understanding of design principles, typography, color theory, and layout.\r\nAbility to work efficiently on multiple projects and meet deadlines.\r\nStrong attention to detail and a keen eye for aesthetics.\r\nExcellent communication and collaboration skills.\r\nExperience with video editing software (e.g., Premiere Pro, After Effects) is a plus.\r\nKnowledge of web design principles and familiarity with HTML/CSS is an advantage.\r\nA strong portfolio showcasing your design skills and previous work.', 'Experience with UX/UI design or web development.\r\nFamiliarity with motion graphics and animation.\r\nExperience in brand identity and logo design.'),
(22, 'Wanted Skilled Laborer', 'We are seeking a reliable and skilled laborer to join our team. The ideal candidate will possess strong hands-on skills in performing a variety of tasks related to construction, maintenance, and general labor. This role requires attention to detail, the ability to follow safety procedures, and work as part of a team to complete projects efficiently and to a high standard. The candidate will be responsible for operating equipment, handling tools, performing manual labor, and contributing to the overall success of the project.', 'uploads/kargador.jpg', NULL, '2025-02-19 05:51:19', 'Skilled Trades & Construction', 17, 'Manicahan', 'Assist in the setup and breakdown of construction sites.\r\nOperate and maintain construction equipment, power tools, and machinery.\r\nPerform various manual labor tasks such as digging, lifting, and carrying materials.\r\nFollow blueprints, diagrams, and instructions to ensure accurate work.\r\nEnsure that the worksite is clean, organized, and safe at all times.\r\nAssist with material handling and storage, ensuring proper inventory control.\r\nConduct regular maintenance and repair of equipment.\r\nCollaborate with team members to complete tasks on time and within safety guidelines.\r\nAdhere to all company policies, safety standards, and regulatory requirements.\r\nCommunicate effectively with supervisors and other team members to ensure smooth operations.', 'Proven experience in construction, maintenance, or general labor.\r\nStrong knowledge of hand tools, power tools, and construction equipment.\r\nAbility to perform physical labor, including lifting heavy objects (up to 50 lbs or more).\r\nFamiliarity with safety practices and the ability to follow safety protocols.\r\nStrong attention to detail and the ability to produce high-quality work.\r\nAbility to read and understand blueprints, diagrams, or work orders.\r\nAbility to work in various weather conditions and environments.\r\nExcellent communication and teamwork skills.\r\nWillingness to learn new skills and undergo training if necessary.\r\nHigh school diploma or equivalent.', 'Certification in construction-related fields (e.g., OSHA certification, forklift operation).\r\nPrevious experience working with specific tools, machinery, or materials relevant to the role.\r\nKnowledge of specialized construction methods (e.g., electrical, plumbing, masonry).\r\nFirst aid and CPR certification.\r\nAbility to operate heavy machinery such as cranes, backhoes, or bulldozers.\r\nExperience in maintaining high safety standards on job sites.\r\nStrong problem-solving skills and a proactive attitude towards addressing issues as they arise.'),
(23, 'Animator for HIRE! ASAP!', 'We are seeking a creative and skilled Animator to join our team. The ideal candidate will have a strong passion for storytelling and a deep understanding of animation principles. You will be responsible for creating visually engaging animations, bringing characters and scenes to life, and ensuring that animations align with the project’s vision. The Animator will work closely with the creative team to develop high-quality animations for various media, including film, television, video games, and digital content.', 'uploads/Screenshot 2025-01-19 031039.png', NULL, '2025-02-19 05:56:22', 'Arts, Design & Entertainment', 5, 'Putik', 'Design and create 2D/3D animations based on storyboards, scripts, and creative briefs.\r\nCollaborate with directors, designers, and other animators to ensure consistency in style and visual direction.\r\nCreate character and environmental animations, ensuring fluid movement and accurate timing.\r\nParticipate in brainstorming sessions and contribute ideas to the creative process.\r\nEdit and refine animations based on feedback, ensuring the final product aligns with project goals.\r\nMaintain consistent communication with the team to meet deadlines and project milestones.\r\nIntegrate special effects, sound, and visual elements into animations.\r\nTroubleshoot animation-related technical issues, and suggest improvements or adjustments as needed.\r\nStay updated on the latest animation trends, techniques, and software.', 'Proven experience as an Animator, with a strong portfolio demonstrating skills in 2D/3D animation, motion graphics, or visual effects.\r\nProficiency in industry-standard animation software (e.g., Adobe Animate, Toon Boom, Maya, Blender, Cinema 4D).\r\nUnderstanding of animation principles (timing, weight, and fluidity of movement).\r\nAbility to adapt to various animation styles and techniques.\r\nStrong understanding of character design, facial expressions, and body mechanics.\r\nKnowledge of storyboarding, composition, and visual storytelling.\r\nAbility to meet deadlines and work effectively under pressure.\r\nStrong attention to detail and ability to incorporate feedback into revisions.\r\nExcellent communication skills and ability to collaborate with a creative team.\r\nBasic knowledge of 3D modeling and rigging (for 3D animators)', 'Bachelor’s degree in Animation, Fine Arts, Computer Graphics, or a related field.\r\nExperience working in the animation industry, including film, television, or gaming.\r\nExperience with motion capture or other advanced animation techniques.\r\nFamiliarity with character rigging and 3D rendering processes.\r\nUnderstanding of VR/AR or interactive media animation principles.\r\nProficiency in additional software such as Adobe After Effects, ZBrush, or Houdini.\r\nAbility to work in both traditional and digital animation environments.\r\nStrong understanding of the production pipeline and ability to work in a team-oriented environment.\r\nPassion for animation and storytelling, with a strong desire to innovate and experiment.'),
(24, 'TOURIST GUIDE!', 'We are looking for an enthusiastic, knowledgeable, and personable Tourist Guide to join our team. The ideal candidate will be passionate about sharing the history, culture, and beauty of our region with travelers. You will lead groups on guided tours, providing engaging and informative commentary while ensuring a safe and enjoyable experience for all guests. Your goal is to offer an immersive and memorable experience for tourists by showcasing key attractions and providing insightful information about local landmarks and traditions.', 'uploads/merloquet.jpg', NULL, '2025-02-19 05:57:33', 'Hospitality & Tourism', 9, 'Sangali', 'Lead guided tours for individuals or groups, providing information about the history, culture, and significance of local landmarks and attractions.\r\nShare interesting and engaging facts about the region’s heritage, traditions, and natural features.\r\nEnsure the safety and well-being of all participants during the tour, including managing group dynamics and addressing any concerns.\r\nTailor tours based on the interests and needs of the group, ensuring an enjoyable experience for people of all ages and backgrounds.\r\nMaintain a friendly and approachable demeanor while answering questions and engaging with tourists.\r\nAssist with tour bookings, providing detailed information to potential customers about available tours and schedules.\r\nCoordinate logistics for the tour, including transportation, timing, and ensuring all necessary resources are available.\r\nAdhere to all safety protocols and ensure that all guests are following guidelines throughout the tour.\r\nContinuously stay informed about local events, news, and changes to landmarks or attractions to provide up-to-date information.\r\nManage any issues or concerns that arise during the tour, providing solutions or escalating as', 'Proven experience as a Tourist Guide, or in a similar customer-facing role (hospitality, travel, or tourism).\r\nStrong knowledge of local attractions, landmarks, history, culture, and natural resources.\r\nExcellent communication skills, with the ability to engage and entertain a diverse group of people.\r\nStrong interpersonal skills and the ability to build rapport with tourists and colleagues.\r\nFluent in English (additional language skills are a plus).\r\nAbility to work in various weather conditions and during irregular hours, including weekends and holidays.\r\nA friendly, approachable, and positive attitude, with a passion for sharing knowledge.\r\nStrong organizational skills to manage group logistics and keep tours running smoothly.\r\nAbility to handle minor issues or challenges calmly and professionally during the tour.\r\nFirst aid certification (preferred but not required).', 'Certification in tourism or a related field (e.g., Certified Tourist Guide, Tourism Management).\r\nKnowledge of multiple languages to accommodate tourists from different regions.\r\nFamiliarity with a variety of tour-related technology (audio systems, mobile apps, etc.).\r\nPrevious experience in a customer service role, particularly in the travel or hospitality industry.\r\nPassion for local culture, history, or nature conservation.\r\nAbility to create customized, themed tours for specific types of tourists (e.g., history buffs, families, adventure seekers).'),
(25, 'HIRING School Bus Driver ', 'We are seeking a reliable and dedicated School Bus Driver to ensure the safe and timely transportation of students to and from school. As a School Bus Driver, you will be responsible for operating the school bus, maintaining a positive environment for students, and following all safety regulations. If you have a passion for working with children and a commitment to ensuring their safety, we encourage you to apply', 'uploads/driver.jpg', 'uploads/bbbbbb.jpg', '2025-02-19 06:09:42', 'Transportation & Automotive', 30, 'Cabaluay', 'Safely drive the school bus according to established routes and schedules.\r\nEnsure the safety of all students during loading, unloading, and the duration of the ride.\r\nAdhere to all traffic laws, school policies, and safety procedures.\r\nAssist students in boarding and exiting the bus as needed.\r\nMaintain regular communication with the school and parents regarding any schedule changes or concerns.\r\nPerform routine inspections of the bus to ensure it is in good working order.\r\nReport any maintenance or mechanical issues promptly.\r\nEnsure the bus is clean and presentable at all times.\r\nFollow protocols for emergencies, including evacuation drills and first aid as required.', 'Valid Commercial Driver’s License (CDL) with School Bus Endorsement.\r\nClean driving record with no major traffic violations.\r\nAbility to pass a background check and drug screening.\r\nAbility to work with children and maintain control of the bus environment.\r\nGood communication and interpersonal skills.\r\nPhysical ability to assist students in case of emergency or to maintain the safety of the bus.\r\nAvailability to work early mornings and afternoons, depending on school schedules.', 'Previous experience as a school bus driver or in a similar transportation role.\r\nFirst Aid and CPR certification.\r\nKnowledge of local roads and traffic patterns.\r\nFamiliarity with school district transportation rules and guidelines.\r\nBilingual skills (if applicable).'),
(59, 'GYM INSTRUCTOR FOR HIRE!', 'We are seeking a passionate and certified Gym Instructor to join our fitness team. The ideal candidate will have a strong understanding of fitness principles, excellent communication skills, and a commitment to motivating others to achieve their fitness goals. As a Gym Instructor, you will be responsible for leading individual and group workout sessions, providing guidance on proper form and technique, and ensuring a safe and welcoming environment for all gym members.', 'uploads/GYM.jpg', 'uploads/YEAH BUDDY!.jpg', '2025-02-24 10:21:38', 'Sports, Fitness & Recreation', 29, 'Arena Blanco', 'Conduct individual and group fitness classes, including strength training, cardio, and flexibility workouts.\r\nAssess clients\' fitness levels and create personalized training plans.\r\nMonitor clients\' progress, adjusting training programs as needed.\r\nEnsure the safety and proper use of gym equipment.\r\nProvide advice and support to gym members on proper workout techniques and nutrition.\r\nMaintain a clean and organized gym environment.\r\nStay up to date with industry trends and continuously improve your fitness knowledge.', 'Certification in personal training or group fitness (e.g., NASM, ACE, or equivalent).\r\nHigh school diploma or equivalent; a degree in kinesiology, exercise science, or a related field is preferred.\r\nProven experience as a gym instructor or personal trainer.\r\nExcellent communication and interpersonal skills.\r\nAbility to motivate and inspire clients to reach their fitness goals.\r\nBasic knowledge of nutrition and wellness principles.\r\nAbility to work flexible hours, including evenings and weekends.', 'Experience in teaching specialized fitness classes (e.g., yoga, Pilates, HIIT).\r\nCPR and First Aid certification.\r\nAdditional certifications in advanced training techniques or fitness programs.\r\nExperience working with diverse populations, including elderly or rehabilitation clients.');

-- --------------------------------------------------------

--
-- Table structure for table `job_positions`
--

CREATE TABLE `job_positions` (
  `id` int(11) NOT NULL,
  `position_name` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_positions`
--

INSERT INTO `job_positions` (`id`, `position_name`, `category_id`) VALUES
(1, 'Office Clerk', 2),
(2, 'HR Officer', 10),
(3, 'Accounting Staff', 1),
(4, 'IT Support', 11),
(5, 'Cashier', 15),
(6, 'Counter Checker', 15),
(7, 'Sales Associate', 15),
(8, 'Merchandiser', 15),
(9, 'Customer Service Representative', 6),
(10, 'Greeter', 6),
(11, 'Inventory Counter', 18),
(12, 'Stock Clerk', 18),
(13, 'Warehouse Associate', 18),
(14, 'Receiving Clerk', 18),
(15, 'Security Guard', 28),
(16, 'Loss Prevention Officer', 28),
(17, 'Janitor / Housekeeping Staff', 31),
(18, 'Maintenance Technician', 31),
(19, 'Store Supervisor', 15),
(20, 'Department Manager', 15),
(21, 'Floor Manager', 15),
(22, 'Operations Manager', 15),
(23, 'Branch Manager', 15),
(24, 'Financial Analyst', 1),
(25, 'Bookkeeper', 1),
(26, 'Payroll Specialist', 1),
(27, 'Tax Accountant', 1),
(28, 'Administrative Assistant', 2),
(29, 'Executive Assistant', 2),
(30, 'Data Entry Clerk', 2),
(31, 'Receptionist', 2),
(32, 'Marketing Coordinator', 3),
(33, 'SEO Specialist', 3),
(34, 'Social Media Manager', 3),
(35, 'Content Creator', 3),
(36, 'Civil Engineer', 4),
(37, 'Architect', 4),
(38, 'Structural Engineer', 4),
(39, 'CAD Drafter', 4),
(40, 'Graphic Designer', 5),
(41, 'Video Editor', 5),
(42, 'Animator', 5),
(43, 'Photographer', 5),
(44, 'Call Center Agent', 6),
(45, 'Technical Support Specialist', 6),
(46, 'Client Relations Manager', 6),
(47, 'Teacher', 7),
(48, 'Tutor', 7),
(49, 'Corporate Trainer', 7),
(50, 'Instructional Designer', 7),
(51, 'Registered Nurse', 8),
(52, 'Medical Assistant', 8),
(53, 'Pharmacist', 8),
(54, 'Dental Hygienist', 8),
(55, 'Hotel Receptionist', 9),
(56, 'Chef', 9),
(57, 'Tour Guide', 9),
(58, 'Bartender', 9),
(59, 'Recruitment Specialist', 10),
(60, 'HR Generalist', 10),
(61, 'HR Manager', 10),
(62, 'Employee Relations Specialist', 10),
(63, 'Software Developer', 11),
(64, 'IT Support Specialist', 11),
(65, 'Cybersecurity Analyst', 11),
(66, 'Network Administrator', 11),
(67, 'Paralegal', 12),
(68, 'Legal Assistant', 12),
(69, 'Compliance Officer', 12),
(70, 'Corporate Lawyer', 12),
(71, 'Production Supervisor', 13),
(72, 'Quality Control Inspector', 13),
(73, 'Machine Operator', 13),
(74, 'Assembly Line Worker', 13),
(75, 'Project Coordinator', 14),
(76, 'Scrum Master', 14),
(77, 'Agile Coach', 14),
(78, 'Program Manager', 14),
(79, 'Retail Store Manager', 15),
(80, 'Sales Representative', 15),
(81, 'Visual Merchandiser', 15),
(82, 'E-commerce Specialist', 15),
(83, 'Lab Technician', 16),
(84, 'Research Scientist', 16),
(85, 'Clinical Research Associate', 16),
(86, 'Biostatistician', 16),
(87, 'Electrician', 17),
(88, 'Plumber', 17),
(89, 'Welder', 17),
(90, 'Carpenter', 17),
(91, 'Logistics Coordinator', 18),
(92, 'Warehouse Manager', 18),
(93, 'Supply Chain Analyst', 18),
(94, 'Fleet Manager', 18),
(95, 'Network Engineer', 19),
(96, 'Telecom Technician', 19),
(97, 'VoIP Specialist', 19),
(98, 'Wireless Engineer', 19),
(99, 'Copywriter', 20),
(100, 'Editor', 20),
(101, 'Technical Writer', 20),
(102, 'Proofreader', 20),
(103, 'Business Analyst', 21),
(104, 'Management Consultant', 21),
(105, 'Strategy Manager', 21),
(106, 'Operations Consultant', 21),
(107, 'IT Consultant', 22),
(108, 'Financial Consultant', 22),
(109, 'HR Consultant', 22),
(110, 'Marketing Consultant', 22),
(111, 'Policy Analyst', 23),
(112, 'Government Affairs Specialist', 23),
(113, 'Urban Planner', 23),
(114, 'Public Relations Officer', 23),
(115, 'Insurance Agent', 24),
(116, 'Claims Adjuster', 24),
(117, 'Underwriter', 24),
(118, 'Risk Analyst', 24),
(119, 'Public Relations Specialist', 25),
(120, 'Broadcast Journalist', 25),
(121, 'Media Planner', 25),
(122, 'Video Producer', 25),
(123, 'Social Worker', 26),
(124, 'Grant Writer', 26),
(125, 'Community Outreach Coordinator', 26),
(126, 'Volunteer Coordinator', 26),
(127, 'Real Estate Agent', 27),
(128, 'Property Manager', 27),
(129, 'Real Estate Appraiser', 27),
(130, 'Leasing Consultant', 27),
(131, 'Police Officer', 28),
(132, 'Corrections Officer', 28),
(133, 'Private Investigator', 28),
(134, 'Security Consultant', 28),
(135, 'Personal Trainer', 29),
(136, 'Sports Coach', 29),
(137, 'Recreation Coordinator', 29),
(138, 'Athletic Trainer', 29),
(139, 'Truck Driver', 30),
(140, 'Auto Mechanic', 30),
(141, 'Fleet Manager', 30),
(142, 'Delivery Driver', 30),
(143, 'Construction Worker', 31),
(144, 'General Laborer', 31),
(145, 'Heavy Equipment Operator', 31),
(146, 'Demolition Worker', 31);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `saved_jobs`
--

CREATE TABLE `saved_jobs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `saved_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `saved_jobs`
--

INSERT INTO `saved_jobs` (`id`, `user_id`, `job_id`, `saved_at`) VALUES
(23, 21, 15, '2025-02-16 12:58:42'),
(26, 21, 8, '2025-02-16 16:03:08'),
(33, 21, 22, '2025-02-19 07:06:26'),
(34, 21, 24, '2025-02-19 07:06:30'),
(51, 4, 51, '2025-02-24 00:59:58'),
(52, 4, 26, '2025-02-24 01:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `username` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `ext_name` varchar(10) DEFAULT NULL,
  `gender` enum('Male','Female','Non-Binary','LGBTQ+','Other') NOT NULL,
  `birth_date` date DEFAULT NULL,
  `age` int(11) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `place_of_birth` varchar(100) NOT NULL,
  `civil_status` enum('Single','Married','Divorced','Widowed') NOT NULL,
  `zip_code` varchar(10) NOT NULL,
  `street_address` varchar(255) NOT NULL,
  `barangay` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `education_level` varchar(50) DEFAULT NULL,
  `completion_year` int(11) DEFAULT NULL,
  `school_name` varchar(255) DEFAULT NULL,
  `inclusive_years` varchar(50) DEFAULT NULL,
  `uploaded_file` varchar(255) DEFAULT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `work_experience` text DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `linkedin_profile` varchar(255) DEFAULT NULL,
  `portfolio_url` varchar(255) DEFAULT NULL,
  `resume_file` varchar(255) DEFAULT NULL,
  `cover_photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role`, `created_at`, `username`, `first_name`, `middle_name`, `last_name`, `ext_name`, `gender`, `birth_date`, `age`, `phone_number`, `place_of_birth`, `civil_status`, `zip_code`, `street_address`, `barangay`, `city`, `education_level`, `completion_year`, `school_name`, `inclusive_years`, `uploaded_file`, `caption`, `work_experience`, `skills`, `linkedin_profile`, `portfolio_url`, `resume_file`, `cover_photo`) VALUES
(4, 'admin@gmail.com', '$2y$10$l/JQhgiGlsFUDRFopU3uzuLUITk/hPV2yQPdQQeI.9ABeBDx5eyKm', 'admin', '2025-02-09 04:34:27', 'admin', 'Super', NULL, 'Admin', NULL, 'Other', '1956-02-17', 69, '09058316452', '', 'Single', '7000', 'Gov. Alvarez Street', 'Barangay Zone II', 'Zamboanga City', 'Doctorate', 2011, 'Harvard Bolibard', '2007-2011', '../uploads/anonymous-8291223_960_720.webp', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'N/A', 'N/A', '', '', NULL, '../uploads/67bbc1f8de5cb_anonymouse.png'),
(21, 'venard@gmail.com', '$2y$10$GEa.OlRYZb.yPRdNTqWIbevFvFRQIzrwutXyacnTLTqUi83/dA9fS', 'user', '2025-02-12 07:38:43', 'venard', 'Venard Jhon C', 'Cabahug', 'Salido', '', 'Male', '1994-05-12', 30, '09351363586', 'ZC', 'Single', '7000', 'Little Baguio', 'Putik', 'Zamboanga City', 'College', 4, 'WMSU', '2011-2025', '../uploads/316673294_6173019966061225_2179427433992613787_n.jpg', 'Just a little boy', 'Soon', 'PHP, HTML, CSS, Java, Django!', 'https://www.linkedin.com/in/venard-jhon-cabahug-salido-08041434b/', 'https://venardjhoncsalido.netlify.app/', '../uploads/resumes/CV-Salido.docx', '../uploads/67b8401349220_430988045_7819159424780596_2606990541394713286_n.jpg'),
(57, 'borat@gmail.com', '$2y$10$1nigCVnAONIKFGykkU6QaevifI7WcSCnQnRBU/enyeuDrDE9zrKbW', 'user', '2025-02-19 13:40:41', 'borat', 'Borat', 'The Great', 'Sagadiyev', '', 'Male', '1995-02-19', 30, '09265605777', 'Zamboanga City', 'Single', '7000', 'Gov. Alvarez Street!', 'Zone II', 'Zamboanga City', NULL, NULL, NULL, NULL, '../uploads/borat.jpg', NULL, NULL, NULL, NULL, NULL, '../uploads/resumes/CV-Salido.docx', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about`
--
ALTER TABLE `about`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ads`
--
ALTER TABLE `ads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `job_id` (`job_id`);

--
-- Indexes for table `barangay`
--
ALTER TABLE `barangay`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_positions`
--
ALTER TABLE `job_positions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `saved_jobs`
--
ALTER TABLE `saved_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_job` (`user_id`,`job_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about`
--
ALTER TABLE `about`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ads`
--
ALTER TABLE `ads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=275;

--
-- AUTO_INCREMENT for table `barangay`
--
ALTER TABLE `barangay`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `job_positions`
--
ALTER TABLE `job_positions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `saved_jobs`
--
ALTER TABLE `saved_jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `job_positions`
--
ALTER TABLE `job_positions`
  ADD CONSTRAINT `job_positions_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
