SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `vi_dien_tu` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `vi_dien_tu`;

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `user` varchar(10) DEFAULT NULL,
  `pass` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(13) DEFAULT NULL,
  `birth` date DEFAULT NULL,
  `address` varchar(255) DEFAULT '',
  `face` varchar(255) DEFAULT '',
  `back` varchar(255) DEFAULT '',
  `money` int(11) DEFAULT 10000000,
  `confirm` int(11) DEFAULT 0 COMMENT '0-Chưa xác nhận\r\n1-Đã xác nhận\r\n2-Đã hủy\r\n3-Yc CMND',
  `role_id` int(11) DEFAULT 2,
  `change_pass` int(1) DEFAULT 0 COMMENT '0: Chưa thay pass, 1: Đã thay pass',
  `otp` int(6) DEFAULT NULL,
  `otp_created` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `account` (`id`, `user`, `pass`, `name`, `email`, `phone`, `birth`, `address`, `face`, `back`, `money`, `confirm`, `role_id`, `change_pass`, `otp`, `otp_created`) VALUES
(1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'Admin', NULL, NULL, NULL, NULL, NULL, NULL, 10000000, 1, 1, 1, NULL, NULL),
(6, '3225101270', 'bb76611ff83cc19e6258fabebe86be1f', 'Trần Thị Thor', 'mrs.thor@asgard.loki', '0123321123', '1111-11-11', 'Asgard', '1653915989_1_0_thor_girl.jpg', '1653915989_2_0_thor_girl.jpg', 10000000, 0, 2, 0, NULL, NULL),
(11, '8914361155', 'e10adc3949ba59abbe56e057f20f883e', 'Thor', 'hoangkhacphuc.dev@gmail.com', '0123123112', '2000-01-02', 'Asgard', '1653917554_1_0_thor.png', '1653917554_2_0_thor.png', 10000000, 0, 2, 1, 272921, '2022-05-31 11:01:28');

CREATE TABLE `locked` (
  `id` int(11) NOT NULL,
  `account_id` int(11) DEFAULT NULL,
  `amount` int(11) DEFAULT 1,
  `created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `method` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `method` (`id`, `name`) VALUES
(1, 'Rút tiền'),
(2, 'Nạp tiền'),
(3, 'Chuyển tiền'),
(4, 'Nhận tiền'),
(5, 'Mua thẻ nạp');

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `role` (`id`, `name`) VALUES
(1, 'Admin'),
(2, 'Khách hàng');

CREATE TABLE `transaction` (
  `id` int(11) NOT NULL,
  `account_id` int(11) DEFAULT NULL,
  `method_id` int(11) DEFAULT NULL,
  `total_money` int(11) DEFAULT NULL,
  `card_number` varchar(20) DEFAULT NULL,
  `exp` date DEFAULT NULL,
  `cvv` varchar(3) DEFAULT NULL,
  `content` varchar(255) DEFAULT '',
  `confirm` int(11) DEFAULT 0 COMMENT '0-Chưa xác nhận, 1-Đã xác nhận',
  `created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


ALTER TABLE `account`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`);

ALTER TABLE `locked`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`);

ALTER TABLE `method`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `method_id` (`method_id`);


ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

ALTER TABLE `locked`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

ALTER TABLE `method`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;


ALTER TABLE `account`
  ADD CONSTRAINT `account_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);

ALTER TABLE `locked`
  ADD CONSTRAINT `locked_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`);

ALTER TABLE `transaction`
  ADD CONSTRAINT `transaction_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`),
  ADD CONSTRAINT `transaction_ibfk_2` FOREIGN KEY (`method_id`) REFERENCES `method` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
