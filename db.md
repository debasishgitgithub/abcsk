### 23/07/2024


CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `first_name` varchar(150) NOT NULL,
  `last_name` varchar(150) NOT NULL,
  `user_id` int(11) NOT NULL,
  `father_name` varchar(200) NOT NULL,
  `session` varchar(150) NOT NULL,
  `year` varchar(100) NOT NULL,
  `mobile_no` varchar(30) NOT NULL,
  `email` varchar(200) NOT NULL,
  `city` varchar(150) NOT NULL,
  `state` varchar(150) NOT NULL,
  `pin` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `installation_type` enum('one_time','installment') NOT NULL,
  `course_id` int(11) NOT NULL,
  `aadhaar_no` int(11) NOT NULL,
  `profile_image` int(11) NOT NULL,
  `mp_admit_card_image` int(11) NOT NULL,
  `status` enum('ACTIVE','INACTIVE') NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `full_name` varchar(200) NOT NULL,
  `short_name` varchar(150) NOT NULL,
  `details` text NOT NULL,
  `duration_in_month` varchar(50) NOT NULL,
  `fees` varchar(100) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;


### 26/07/2024


ALTER TABLE `students` CHANGE `last_nmae` `last_name` VARCHAR(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;

ALTER TABLE `students` CHANGE `profile_image` `profile_image` VARCHAR(300) NOT NULL, CHANGE `mp_admit_card_image` `mp_admit_card_image` VARCHAR(300) NOT NULL;