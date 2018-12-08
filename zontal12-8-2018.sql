-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2018 at 10:14 AM
-- Server version: 10.1.34-MariaDB
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zontal`
--

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `id` varchar(30) NOT NULL,
  `title` varchar(200) NOT NULL COMMENT 'ชื่อของclass',
  `teacher_email` varchar(150) NOT NULL COMMENT 'emailของอาจารย์',
  `description` varchar(200) NOT NULL COMMENT 'รายละเอียดรายวิชา',
  `password` int(10) NOT NULL,
  `pergroup` int(10) NOT NULL COMMENT 'จำนวนนักเรียนต่อกลุ่ม',
  `n_group` int(10) NOT NULL,
  `student_limit` int(10) NOT NULL,
  `v` tinyint(1) NOT NULL COMMENT 'ต้องการคะแนน v หรือไม่',
  `a` tinyint(1) NOT NULL COMMENT 'ต้องการคะแนน a หรือไม่',
  `k` tinyint(1) NOT NULL COMMENT 'ต้องการคะแนน k หรือไม่',
  `date_start` datetime DEFAULT NULL,
  `date_end` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`id`, `title`, `teacher_email`, `description`, `password`, `pergroup`, `n_group`, `student_limit`, `v`, `a`, `k`, `date_start`, `date_end`) VALUES
('class3', 'Test', 'tea03@gmail.com', 'Test Test', 1234, 4, 3, 12, 1, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
('class4', 'CSSSS', 'tea03@gmail.com', 'sssss', 1234, 4, 5, 20, 1, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
('class6', 'createTest', 'tea03@gmail.com', 'qwer', 1234, 4, 3, 12, 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `gen_classid`
--

CREATE TABLE `gen_classid` (
  `id` int(11) NOT NULL,
  `title` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gen_classid`
--

INSERT INTO `gen_classid` (`id`, `title`) VALUES
(8, 'class3'),
(9, 'class4'),
(10, 'class5'),
(11, 'class4'),
(12, 'class6');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `title`) VALUES
(1, 'admin'),
(2, 'teacher'),
(3, 'student');

-- --------------------------------------------------------

--
-- Table structure for table `pre_data`
--

CREATE TABLE `pre_data` (
  `id` int(11) NOT NULL,
  `class_id` varchar(30) NOT NULL,
  `std_email` varchar(150) NOT NULL,
  `score` varchar(200) NOT NULL COMMENT 'ค่า 3ตัวแรก คือ v,a,k หลังจากนั้นคือ คะแนนที่ต้องการ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `result_grouped`
--

CREATE TABLE `result_grouped` (
  `id` int(11) NOT NULL,
  `classid` varchar(30) NOT NULL,
  `result` text NOT NULL COMMENT 'ข้อมูลชื่อและคะแนนนักศึกษา เพื่อเป็นข้อมูลดิบเอาไปใช้จัดกลุ่ม'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `result_grouped`
--

INSERT INTO `result_grouped` (`id`, `classid`, `result`) VALUES
(4, 'class3', '[[{\"name\":\"stest11\",\"score\":[\"9\",\"3\",\"2.50\"]},{\"name\":\"stest09\",\"score\":[\"7\",\"10\",\"2.50\"]},{\"name\":\"stest12\",\"score\":[\"10\",\"1\",\"3.75\"]},{\"name\":\"stest07\",\"score\":[\"3\",\"6\",\"3.00\"]}],[{\"name\":\"stest03\",\"score\":[\"8\",\"1\",\"4.00\"]},{\"name\":\"stest10\",\"score\":[\"6\",\"4\",\"1.50\"]},{\"name\":\"stest06\",\"score\":[\"4\",\"7\",\"4.00\"]},{\"name\":\"stest02\",\"score\":[\"7\",\"5\",\"3.00\"]}],[{\"name\":\"stest05\",\"score\":[\"5\",\"4\",\"3.50\"]},{\"name\":\"stest04\",\"score\":[\"9\",\"2\",\"4.00\"]},{\"name\":\"stest01\",\"score\":[\"7\",\"3\",\"2.00\"]},{\"name\":\"stest08\",\"score\":[\"4\",\"9\",\"2.00\"]}]]');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `email` varchar(150) NOT NULL,
  `u_id` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `password` varchar(64) NOT NULL,
  `p_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_score`
--

CREATE TABLE `student_score` (
  `id` int(11) NOT NULL,
  `class_id` varchar(30) NOT NULL,
  `subject_title` varchar(150) NOT NULL,
  `std_email` varchar(150) NOT NULL,
  `score` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='classนั้น วิชาอะไร นักเรียนคนไหน ได้คะแนนเท่าไหร่';

--
-- Dumping data for table `student_score`
--

INSERT INTO `student_score` (`id`, `class_id`, `subject_title`, `std_email`, `score`) VALUES
(14, 'class3', 'CS100', 'stest01@gmail.com', '2.00'),
(15, 'class3', 'CS100', 'stest02@gmail.com', '3.00'),
(16, 'class3', 'CS100', 'stest03@gmail.com', '4.00'),
(17, 'class3', 'CS100', 'stest04@gmail.com', '4.00'),
(18, 'class3', 'CS100', 'stest05@gmail.com', '3.50'),
(19, 'class3', 'CS100', 'stest06@gmail.com', '4.00'),
(20, 'class3', 'CS100', 'stest07@gmail.com', '3.00'),
(21, 'class3', 'CS100', 'stest08@gmail.com', '2.00'),
(22, 'class3', 'CS100', 'stest09@gmail.com', '2.50'),
(23, 'class3', 'CS100', 'stest10@gmail.com', '1.50'),
(24, 'class3', 'CS100', 'stest11@gmail.com', '2.50'),
(25, 'class3', 'CS100', 'stest12@gmail.com', '3.75'),
(44, 'class4', 'CS100', 'std01@gmail.com', '4.00'),
(45, 'class4', 'CS200', 'std01@gmail.com', '4.00'),
(46, 'class4', 'CS100', 'stest01@gmail.com', '4.00'),
(47, 'class4', 'CS200', 'stest01@gmail.com', '4.00'),
(48, 'class4', 'CS100', 'stest02@gmail.com', '3.50'),
(49, 'class4', 'CS200', 'stest02@gmail.com', '4.00');

-- --------------------------------------------------------

--
-- Table structure for table `sub`
--

CREATE TABLE `sub` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `subject_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sub`
--

INSERT INTO `sub` (`id`, `title`, `subject_id`) VALUES
(1, 'programming1', 1),
(2, 'programming2', 1);

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `decs` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`id`, `title`, `decs`) VALUES
(1, 'OOP', 'การเขียนโปรแกรมเชิงวัตถุ'),
(2, 'etc', 'วิชาศึกษาทั่วไป');

-- --------------------------------------------------------

--
-- Table structure for table `subject_req`
--

CREATE TABLE `subject_req` (
  `id` int(11) NOT NULL,
  `title` varchar(30) NOT NULL COMMENT 'รายวิชาที่ต้องการ',
  `class_id` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `subject_req`
--

INSERT INTO `subject_req` (`id`, `title`, `class_id`) VALUES
(4, 'CS100', 'class3'),
(5, 'CS100', 'class4'),
(6, 'CS200', 'class4'),
(9, 'CS111', 'class6'),
(10, 'CS222', 'class6');

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE `test` (
  `id` int(11) NOT NULL,
  `sub_req_id` int(11) NOT NULL,
  `std_email` varchar(150) NOT NULL,
  `score` decimal(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(150) NOT NULL,
  `u_id` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `password` varchar(64) NOT NULL,
  `v` int(5) NOT NULL,
  `a` int(5) NOT NULL,
  `k` int(5) NOT NULL,
  `p_id` int(11) DEFAULT NULL COMMENT 'Permission ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `u_id`, `name`, `password`, `v`, `a`, `k`, `p_id`) VALUES
(1, 'std01@gmail.com', 'std01', 'student_com', '1234', 7, 3, 10, 3),
(2, 'admin@gmail.com', 'admin01', 'admin', '1234', 0, 0, 0, 1),
(3, 'tea01@gmail.com', 'tea01', 'teacher_com', '1234', 0, 0, 0, 2),
(5, 'std02@gmail.com', 'std02', 'student02', '1234', 8, 2, 10, 3),
(7, 'tea02@gmail.com', 'tea02', 'teacher02', '1234', 8, 2, 10, 2),
(9, 'tea03@gmail.com', 'tea03', 'teacher03', '1234', 0, 0, 0, 2),
(10, 'std03@gmail.com', 'std03', 'student03', '1234', 8, 2, 10, 3),
(11, 'std04@gmail.com', 'std04', 'student04', '1234', 2, 8, 10, 3),
(12, 'std05@gmail.com', 'std05', 'student05', '1234', 2, 10, 8, 3),
(13, 'std06@gmail.com', 'std06', 'student06', '1234', 10, 2, 8, 3),
(14, 'stest01@gmail.com', 'stest01', 'stest01', '1234', 7, 10, 3, 3),
(15, 'stest02@gmail.com', 'stest02', 'stest02', '1234', 7, 8, 5, 3),
(16, 'stest03@gmail.com', 'stest03', 'stest03', '1234', 8, 11, 1, 3),
(17, 'stest04@gmail.com', 'stest04', 'stest04', '1234', 9, 9, 2, 3),
(18, 'stest05@gmail.com', 'stest05', 'stest05', '1234', 5, 11, 4, 3),
(19, 'stest06@gmail.com', 'stest06', 'stest06', '1234', 4, 9, 7, 3),
(20, 'stest07@gmail.com', 'stest07', 'stest07', '1234', 3, 11, 6, 3),
(21, 'stest08@gmail.com', 'stest08', 'stest08', '1234', 4, 7, 9, 3),
(22, 'stest09@gmail.com', 'stest09', 'stest09', '1234', 7, 3, 10, 3),
(23, 'stest10@gmail.com', 'stest10', 'stest10', '1234', 6, 10, 4, 3),
(24, 'stest11@gmail.com', 'stest11', 'stest11', '1234', 9, 8, 3, 3),
(25, 'stest12@gmail.com', 'stest12', 'stest12', '1234', 10, 9, 1, 3),
(27, 'tea04@gmail.com', 'tests', 'test', '1234', 0, 0, 0, 2),
(28, 'stest13@gmail.com', 'stest13', 'test13', '1234', 0, 0, 0, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_email` (`teacher_email`);

--
-- Indexes for table `gen_classid`
--
ALTER TABLE `gen_classid`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_data`
--
ALTER TABLE `pre_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `std_email` (`std_email`);

--
-- Indexes for table `result_grouped`
--
ALTER TABLE `result_grouped`
  ADD PRIMARY KEY (`id`),
  ADD KEY `classid` (`classid`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`),
  ADD KEY `p_id` (`p_id`);

--
-- Indexes for table `student_score`
--
ALTER TABLE `student_score`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `std_email` (`std_email`);

--
-- Indexes for table `sub`
--
ALTER TABLE `sub`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subject_req`
--
ALTER TABLE `subject_req`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`id`),
  ADD KEY `std_email` (`std_email`),
  ADD KEY `sub_req_id` (`sub_req_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `p_id` (`p_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gen_classid`
--
ALTER TABLE `gen_classid`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pre_data`
--
ALTER TABLE `pre_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `result_grouped`
--
ALTER TABLE `result_grouped`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_score`
--
ALTER TABLE `student_score`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `sub`
--
ALTER TABLE `sub`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `subject_req`
--
ALTER TABLE `subject_req`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `test`
--
ALTER TABLE `test`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `class`
--
ALTER TABLE `class`
  ADD CONSTRAINT `class_ibfk_1` FOREIGN KEY (`teacher_email`) REFERENCES `users` (`email`);

--
-- Constraints for table `pre_data`
--
ALTER TABLE `pre_data`
  ADD CONSTRAINT `pre_data_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`),
  ADD CONSTRAINT `pre_data_ibfk_2` FOREIGN KEY (`std_email`) REFERENCES `users` (`email`);

--
-- Constraints for table `result_grouped`
--
ALTER TABLE `result_grouped`
  ADD CONSTRAINT `result_grouped_ibfk_1` FOREIGN KEY (`classid`) REFERENCES `class` (`id`);

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`p_id`) REFERENCES `permissions` (`id`);

--
-- Constraints for table `student_score`
--
ALTER TABLE `student_score`
  ADD CONSTRAINT `student_score_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`),
  ADD CONSTRAINT `student_score_ibfk_2` FOREIGN KEY (`std_email`) REFERENCES `users` (`email`);

--
-- Constraints for table `sub`
--
ALTER TABLE `sub`
  ADD CONSTRAINT `sub_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`id`);

--
-- Constraints for table `subject_req`
--
ALTER TABLE `subject_req`
  ADD CONSTRAINT `subject_req_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`);

--
-- Constraints for table `test`
--
ALTER TABLE `test`
  ADD CONSTRAINT `test_ibfk_1` FOREIGN KEY (`std_email`) REFERENCES `users` (`email`),
  ADD CONSTRAINT `test_ibfk_2` FOREIGN KEY (`sub_req_id`) REFERENCES `subject_req` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`p_id`) REFERENCES `permissions` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
