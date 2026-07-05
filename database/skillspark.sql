-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 05, 2026 at 12:48 PM
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
-- Database: `skillspark`
--

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `certificate_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `certificate_code` varchar(100) DEFAULT NULL,
  `issue_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `level` varchar(50) DEFAULT NULL,
  `instructor` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `access_type` enum('Free','Premium') DEFAULT 'Free'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `course_name`, `description`, `duration`, `level`, `instructor`, `image`, `access_type`) VALUES
(1, 'Full Stack Development', 'Learn HTML, CSS, JavaScript, PHP and MySQL', '3 Months', 'Beginner', 'John', NULL, 'Free'),
(2, 'Python Programming', 'Python from beginner to advanced', '2 Months', 'Intermediate', 'David', NULL, 'Free'),
(3, 'Java Programming', 'Core Java Concepts', '2 Months', 'Intermediate', 'Smith', NULL, 'Free'),
(4, 'Data Analytics', 'Excel, SQL and Power BI', '3 Months', 'Beginner', 'Sarah', NULL, 'Free'),
(5, 'C Programming', 'Programming fundamentals using C', '2 Months', 'Beginner', 'Alex', NULL, 'Premium'),
(6, 'C++ Programming', 'Object-Oriented Programming with C++', '3 Months', 'Intermediate', 'Emma', NULL, 'Premium'),
(7, 'JavaScript Advanced', 'Advanced JavaScript and ES6 concepts', '2 Months', 'Advanced', 'Michael', NULL, 'Premium'),
(8, 'Web Technology', 'Frontend and Backend Web Technologies', '4 Months', 'Advanced', 'Sophia', NULL, 'Premium'),
(9, 'Data Structures', 'Arrays, Linked Lists, Trees and Graphs', '3 Months', 'Intermediate', 'Daniel', NULL, 'Premium'),
(10, 'Computer Organization', 'Computer Architecture and Organization', '3 Months', 'Intermediate', 'Olivia', NULL, 'Premium');

-- --------------------------------------------------------

--
-- Table structure for table `course_quizzes`
--

CREATE TABLE `course_quizzes` (
  `quiz_id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `question_text` text DEFAULT NULL,
  `option_a` varchar(255) DEFAULT NULL,
  `option_b` varchar(255) DEFAULT NULL,
  `option_c` varchar(255) DEFAULT NULL,
  `option_d` varchar(255) DEFAULT NULL,
  `correct_option` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_quizzes`
--

INSERT INTO `course_quizzes` (`quiz_id`, `course_id`, `question_text`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_option`) VALUES
(1, 1, 'What is the main focus of the Full Stack Development course?', 'Learning core concepts', 'Cooking lessons', 'Travel planning', 'Music theory', 'A'),
(2, 1, 'Why are quizzes useful in Full Stack Development?', 'They reduce learning', 'They help check understanding', 'They delete progress', 'They replace lessons', 'B'),
(3, 1, 'How many questions are included in each course quiz on this site?', '3', '4', '5', '10', 'C'),
(4, 1, 'What do points represent in the Full Stack Development quiz?', 'Correct answers earned', 'Video length', 'Subscription price', 'Course duration', 'A'),
(5, 1, 'What is generated after finishing the Full Stack Development quiz?', 'Invoice', 'Certificate', 'Password reset', 'Database backup', 'B'),
(6, 2, 'Which keyword is used to define a function in Python?', 'function', 'def', 'func', 'define', 'B'),
(7, 2, 'Which data type stores True or False values?', 'string', 'integer', 'boolean', 'list', 'C'),
(8, 2, 'Which symbol starts a comment in Python?', '//', '#', '--', '/*', 'B'),
(9, 2, 'Which built-in function shows output on the screen?', 'echo()', 'print()', 'write()', 'show()', 'B'),
(10, 2, 'Which collection type uses square brackets?', 'tuple', 'set', 'dictionary', 'list', 'D'),
(11, 3, 'Which keyword declares a block-scoped variable?', 'var', 'let', 'dim', 'define', 'B'),
(12, 3, 'Which method converts JSON text into a JavaScript object?', 'JSON.parse()', 'JSON.stringify()', 'JSON.object()', 'JSON.convert()', 'A'),
(13, 3, 'Which symbol is used for strict equality?', '==', '!=', '===', '=', 'C'),
(14, 3, 'Which browser object represents the current webpage?', 'window', 'document', 'screen', 'history', 'B'),
(15, 3, 'Which function runs code after a delay?', 'setTimeout()', 'setInterval()', 'delay()', 'wait()', 'A'),
(16, 4, 'What is the main focus of the Data Analytics course?', 'Learning core concepts', 'Cooking lessons', 'Travel planning', 'Music theory', 'A'),
(17, 4, 'Why are quizzes useful in Data Analytics?', 'They reduce learning', 'They help check understanding', 'They delete progress', 'They replace lessons', 'B'),
(18, 4, 'How many questions are included in each course quiz on this site?', '3', '4', '5', '10', 'C'),
(19, 4, 'What do points represent in the Data Analytics quiz?', 'Correct answers earned', 'Video length', 'Subscription price', 'Course duration', 'A'),
(20, 4, 'What is generated after finishing the Data Analytics quiz?', 'Invoice', 'Certificate', 'Password reset', 'Database backup', 'B'),
(21, 5, 'Which header file is commonly used for printf()?', 'stdlib.h', 'string.h', 'stdio.h', 'math.h', 'C'),
(22, 5, 'Which symbol ends a statement in C?', ':', ';', '.', ',', 'B'),
(23, 5, 'Which loop is best when the number of iterations is known?', 'for', 'switch', 'if', 'goto', 'A'),
(24, 5, 'Which operator stores a value in a variable?', '==', ':=', '=', '=>', 'C'),
(25, 5, 'Which data type stores whole numbers?', 'float', 'int', 'char', 'double', 'B'),
(26, 6, 'Which feature allows multiple functions with the same name but different parameters?', 'Encapsulation', 'Inheritance', 'Polymorphism', 'Compilation', 'C'),
(27, 6, 'Which stream is used to print output in C++?', 'cin', 'cout', 'print', 'echo', 'B'),
(28, 6, 'Which operator is used to access a class member through an object?', '->', '::', '.', '#', 'C'),
(29, 6, 'Which keyword creates a class in C++?', 'class', 'struct', 'object', 'define', 'A'),
(30, 6, 'Which concept allows one class to use properties of another class?', 'Looping', 'Inheritance', 'Casting', 'Overloading', 'B'),
(31, 7, 'Which concept lets a function remember variables from its outer scope?', 'Promise', 'Closure', 'Callback', 'Hoisting', 'B'),
(32, 7, 'Which keyword is used with asynchronous functions?', 'await', 'yield', 'pause', 'defer', 'A'),
(33, 7, 'Which method creates a new array with transformed items?', 'filter()', 'map()', 'reduce()', 'find()', 'B'),
(34, 7, 'What does the spread operator look like?', '***', '=>', '...', '??', 'C'),
(35, 7, 'Which object is used to work with asynchronous results?', 'Promise', 'Array', 'Date', 'Math', 'A'),
(36, 8, 'Which language is primarily used to structure web pages?', 'CSS', 'HTML', 'SQL', 'Python Programming', 'B'),
(37, 8, 'Which language is mainly used to style web pages?', 'Java', 'PHP', 'CSS', 'C++ Programming', 'C'),
(38, 8, 'Which protocol is commonly used to transfer web pages?', 'HTTP', 'FTP', 'SMTP', 'SNMP', 'A'),
(39, 8, 'Which tag creates a hyperlink in HTML?', '<link>', '<href>', '<a>', '<url>', 'C'),
(40, 8, 'Which CSS property changes text color?', 'font-color', 'text-style', 'foreground', 'color', 'D'),
(41, 9, 'Which data structure follows First In First Out order?', 'Stack', 'Queue', 'Tree', 'Graph', 'B'),
(42, 9, 'Which data structure follows Last In First Out order?', 'Queue', 'Array', 'Stack', 'Linked list', 'C'),
(43, 9, 'Which data structure is best for hierarchical data?', 'Tree', 'Queue', 'Stack', 'Matrix', 'A'),
(44, 9, 'Which search is commonly used on a sorted array?', 'Linear search', 'Binary search', 'Depth-first search', 'Breadth-first search', 'B'),
(45, 9, 'Which linked list node usually contains data and a pointer?', 'Only data', 'Only address', 'Data and link', 'Index and size', 'C'),
(46, 10, 'What is the main focus of the Computer Organization course?', 'Learning core concepts', 'Cooking lessons', 'Travel planning', 'Music theory', 'A'),
(47, 10, 'Why are quizzes useful in Computer Organization?', 'They reduce learning', 'They help check understanding', 'They delete progress', 'They replace lessons', 'B'),
(48, 10, 'How many questions are included in each course quiz on this site?', '3', '4', '5', '10', 'C'),
(49, 10, 'What do points represent in the Computer Organization quiz?', 'Correct answers earned', 'Video length', 'Subscription price', 'Course duration', 'A'),
(50, 10, 'What is generated after finishing the Computer Organization quiz?', 'Invoice', 'Certificate', 'Password reset', 'Database backup', 'B');

-- --------------------------------------------------------

--
-- Table structure for table `dashboard_progress`
--

CREATE TABLE `dashboard_progress` (
  `progress_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `status` enum('Pending','In Progress','Completed') DEFAULT 'Pending',
  `completion_percentage` int(11) DEFAULT 0,
  `last_accessed` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `enroll_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `enrolled_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `help_queries`
--

CREATE TABLE `help_queries` (
  `query_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `query_status` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_attempts`
--

CREATE TABLE `quiz_attempts` (
  `attempt_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `total_questions` int(11) DEFAULT NULL,
  `correct_answers` int(11) DEFAULT NULL,
  `points` int(11) DEFAULT NULL,
  `completed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz_attempts`
--

INSERT INTO `quiz_attempts` (`attempt_id`, `user_id`, `course_id`, `total_questions`, `correct_answers`, `points`, `completed_at`) VALUES
(1, 1, 1, 5, 4, 80, '2026-07-04 16:48:32'),
(2, 1, 1, 5, 2, 40, '2026-07-04 17:29:53'),
(3, 1, 2, 5, 3, 60, '2026-07-04 17:37:06'),
(4, 1, 1, 5, 4, 80, '2026-07-05 09:56:18'),
(5, 1, 1, 5, 4, 80, '2026-07-05 10:18:18');

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `subscription_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `plan_name` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `birthdate` date DEFAULT NULL,
  `contact` varchar(15) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_image` varchar(255) DEFAULT 'default.png',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fname`, `lname`, `birthdate`, `contact`, `email`, `password`, `profile_image`, `created_at`) VALUES
(1, 'Akshata', 'Lawande', '2005-09-11', '8669127162', 'akshatalawande@gmail.com', '$2y$10$wEWWpmhj9Y.iI2mJ/s1bl.rU9TuoCPO43BNlGJekNVDilLRW5jIm6', 'default.png', '2026-07-04 15:00:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`certificate_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`);

--
-- Indexes for table `course_quizzes`
--
ALTER TABLE `course_quizzes`
  ADD PRIMARY KEY (`quiz_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `dashboard_progress`
--
ALTER TABLE `dashboard_progress`
  ADD PRIMARY KEY (`progress_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`enroll_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `help_queries`
--
ALTER TABLE `help_queries`
  ADD PRIMARY KEY (`query_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD PRIMARY KEY (`attempt_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`subscription_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `certificate_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `course_quizzes`
--
ALTER TABLE `course_quizzes`
  MODIFY `quiz_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `dashboard_progress`
--
ALTER TABLE `dashboard_progress`
  MODIFY `progress_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `enroll_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `help_queries`
--
ALTER TABLE `help_queries`
  MODIFY `query_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  MODIFY `attempt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `subscription_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `certificates`
--
ALTER TABLE `certificates`
  ADD CONSTRAINT `certificates_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `certificates_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);

--
-- Constraints for table `course_quizzes`
--
ALTER TABLE `course_quizzes`
  ADD CONSTRAINT `course_quizzes_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);

--
-- Constraints for table `dashboard_progress`
--
ALTER TABLE `dashboard_progress`
  ADD CONSTRAINT `dashboard_progress_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `dashboard_progress_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);

--
-- Constraints for table `help_queries`
--
ALTER TABLE `help_queries`
  ADD CONSTRAINT `help_queries_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD CONSTRAINT `quiz_attempts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `quiz_attempts_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
