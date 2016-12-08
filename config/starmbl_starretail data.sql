-- phpMyAdmin SQL Dump
-- version 4.0.10.14
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Dec 04, 2016 at 09:08 PM
-- Server version: 5.5.52-cll-lve
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `starmbl_starretail`
--

--
-- Dumping data for table `authorize`
--

INSERT INTO `authorize` (`id`, `user_profile_id`, `uname`, `pword`, `role`, `branch_id`, `statid`) VALUES
(1, 1, 'owner', 'b4af804009cb036a4ccdc33431ef9ac9', 'owner', 1, 1),
(2, 2, 'test.account', '52dcb810931e20f7aa2f49b3510d3805', 'supervisor', 2, 1),
(3, 3, 'user.delete', 'b4af804009cb036a4ccdc33431ef9ac9', 'staff', 2, 2);

--
-- Dumping data for table `branch`
--

INSERT INTO `branch` (`id`, `name`, `tag`, `building_street`, `barangay`, `city_district`, `province`, `landline_no`, `mobile_no`, `date_active`, `active`, `statid`) VALUES
(1, 'Main Office', '', '', '', '', '', '', '', '0000-00-00', 1, 1),
(2, 'Star Pasig Kapitolyo', '', 'Test St.', 'Test Barangay', 'Test City', 'Test Province', '12345', '12345', '2016-12-01', 1, 1),
(3, 'Test Delete ABC', '', 'Test Delete', 'Test Delete', 'Test Delete', 'Test Delete', '123', '123', '2016-12-01', 1, 2);

--
-- Dumping data for table `delivery`
--

INSERT INTO `delivery` (`id`, `create_date`, `create_time`, `create_by`, `from_branch_id`, `to_branch_id`, `delivery_no`, `delivery_date`, `item_id`, `qty`, `qty_receive`, `statid`, `cancel_date`, `cancel_time`, `cancel_by`, `cancel_remarks`) VALUES
(1, '2016-12-02', '10:23:40', 1, 1, 2, 'SM201602016', '2016-11-29', 1, 1, 0, 28, '2016-12-02', '10:26:28', 1, ''),
(2, '2016-12-02', '10:24:07', 1, 1, 2, 'SM201602016', '2016-12-02', 2, 50, 40, 6, '0000-00-00', '00:00:00', 0, ''),
(3, '2016-12-02', '10:25:15', 1, 1, 2, 'SM201602016', '2016-11-29', 3, 5, 5, 8, '0000-00-00', '00:00:00', 0, ''),
(4, '2016-12-02', '10:26:10', 1, 1, 2, 'SM201602016', '2016-12-02', 1, 2, 2, 8, '0000-00-00', '00:00:00', 0, '');

--
-- Dumping data for table `delivery_receive`
--

INSERT INTO `delivery_receive` (`id`, `delivery_id`, `qty`, `create_date`, `create_time`, `create_by`, `statid`) VALUES
(1, 2, 40, '2016-12-02', '10:34:38', 2, 1),
(2, 4, 2, '2016-12-02', '10:35:10', 2, 1),
(3, 3, 5, '2016-12-02', '10:47:23', 2, 1);

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `first_name`, `last_name`, `statid`) VALUES
(1, 'Owner', '', 1);

--
-- Dumping data for table `employee_position`
--

INSERT INTO `employee_position` (`id`, `name`, `statid`) VALUES
(1, 'Administrator', 1),
(2, 'Supervisor', 1),
(3, 'Staff', 1);

--
-- Dumping data for table `fifo`
--

INSERT INTO `fifo` (`id`, `delivery_id`, `item_id`, `statid`, `receive_qty`, `receive_date`, `transaction_qty`, `transaction_date`, `transaction`, `remarks`, `cost`, `sequence_no`, `branch_id`) VALUES
(1, 1, 1, 28, 1, '2016-12-02 10:23:40', 0, '2016-12-02 10:26:28', '1', 'From purchase delivery.Cancel delivery.', 0.00000, 0, 1),
(2, 2, 2, 7, 50, '2016-12-02 10:24:07', 40, '2016-12-02 10:34:38', '2', 'From purchase delivery.', 0.00000, 0, 1),
(3, 3, 3, 7, 5, '2016-12-02 10:25:15', 5, '2016-12-02 10:47:23', '3', 'From purchase delivery.', 0.00000, 0, 1),
(4, 4, 1, 7, 2, '2016-12-02 10:26:10', 2, '2016-12-02 10:35:10', '4', 'From purchase delivery.', 0.00000, 0, 1),
(5, 2, 2, 6, 10, '2016-12-02 10:34:38', 0, '0000-00-00 00:00:00', '2', 'From pending delivery.', 0.00000, 1, 1),
(6, 2, 2, 1, 40, '2016-12-02 10:34:38', 0, '0000-00-00 00:00:00', '2', 'From delivery.', 0.00000, 1, 2),
(7, 4, 1, 1, 2, '2016-12-02 10:35:10', 0, '0000-00-00 00:00:00', '4', 'From delivery.', 0.00000, 1, 2),
(8, 3, 3, 10, 5, '2016-12-02 10:47:23', 1, '2016-12-02 11:09:16', '1', 'From delivery.After sell item.', 0.00000, 1, 2),
(9, 3, 3, 1, 4, '2016-12-02 11:09:16', 0, '0000-00-00 00:00:00', '', 'After sell item.', 0.00000, 2, 2);

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`id`, `name`, `tag`, `category_id`, `serial`, `srp`, `dp`, `cost`, `active`, `statid`) VALUES
(1, 'Knight Elite Black', 'Knight Elite', 1, 1, 1500.00000, 1000.00000, 0.00000, 1, 1),
(2, 'Test Accessories', 'Test Accessories', 3, 0, 200.00000, 150.00000, 0.00000, 1, 1),
(3, 'Engage 7i+ Black', 'Engage 7i+', 2, 1, 3200.00000, 2900.00000, 0.00000, 1, 1),
(4, 'Test Delete Black', 'Test Delete', 3, 1, 5.00000, 1.00000, 0.00000, 1, 2);

--
-- Dumping data for table `item_category`
--

INSERT INTO `item_category` (`id`, `name`, `statid`) VALUES
(1, 'Mobile', 1),
(2, 'Tablet', 1),
(3, 'Accessories', 1),
(4, 'Load Credits', 1);

--
-- Dumping data for table `payment_type`
--

INSERT INTO `payment_type` (`id`, `name`, `credit_card`, `statid`) VALUES
(1, 'Cash', 0, 1),
(2, 'Credit Card', 1, 1);

--
-- Dumping data for table `promotion`
--

INSERT INTO `promotion` (`id`, `name`, `branch_id`, `item_id`, `item_tag`, `discount`, `start_date`, `end_date`, `active`, `statid`) VALUES
(1, 'Test Promo', 0, 1, 'Knight Elite', 50.00000, '2016-11-25', '2016-12-31', 1, 1),
(2, 'Test Promo Kapitolyo', 2, 1, 'Knight Elite', 10.00000, '2016-11-25', '2016-12-31', 1, 1),
(3, 'Test Delete', 2, 3, 'Engage 7i+', 1.00000, '2016-12-02', '2016-12-02', 1, 2);

--
-- Dumping data for table `sale_fifo`
--

INSERT INTO `sale_fifo` (`id`, `sale_hdr_id`, `qty_sold`, `srp`, `cost`, `profit`, `fifo_id`, `delivery_id`, `item_id`, `statid`, `return_qty`) VALUES
(1, 1, 1, 3200.00000, 0.00000, 3200.00000, 8, 3, 3, 10, 0);

--
-- Dumping data for table `sale_hdr`
--

INSERT INTO `sale_hdr` (`id`, `invoice`, `customer`, `create_date`, `create_by`, `branch_id`, `cancel_date`, `cancel_by`, `statid`, `proceed_to_payment`) VALUES
(1, '123', 'Rafael Riano', '2016-12-02', 2, 2, '0000-00-00', 0, 10, 1);

--
-- Dumping data for table `sale_payment`
--

INSERT INTO `sale_payment` (`id`, `sale_hdr_id`, `payment_type_id`, `card_no`, `amount`, `statid`) VALUES
(1, 1, 2, '12345', 3200.00000, 10);

--
-- Dumping data for table `serial`
--

INSERT INTO `serial` (`id`, `imei`, `branch_id`, `item_id`, `transaction_no`, `transaction_date`, `statid`, `remarks`) VALUES
(1, '12340', 1, 1, 1, '2016-12-02', 28, 'Cancel delivery'),
(2, '12341', 2, 3, 1, '2016-12-02', 10, 'From delivery.'),
(3, '12342', 2, 3, 3, '2016-12-02', 1, 'From delivery.'),
(4, '12343', 2, 3, 3, '2016-12-02', 1, 'From delivery.'),
(5, '12344', 2, 3, 3, '2016-12-02', 1, 'From delivery.'),
(6, '12345', 2, 3, 3, '2016-12-02', 1, 'From delivery.'),
(7, '12346', 2, 1, 4, '2016-12-02', 1, 'From delivery.'),
(8, '12347', 2, 1, 4, '2016-12-02', 1, 'From delivery.');

--
-- Dumping data for table `serial_history`
--

INSERT INTO `serial_history` (`id`, `serial_id`, `branch_id`, `transaction_no`, `transaction_date`, `statid`, `remarks`) VALUES
(1, 1, 1, 1, '2016-12-02', 6, 'From purchase delivery.'),
(2, 2, 1, 3, '2016-12-02', 6, 'From purchase delivery.'),
(3, 3, 1, 3, '2016-12-02', 6, 'From purchase delivery.'),
(4, 4, 1, 3, '2016-12-02', 6, 'From purchase delivery.'),
(5, 5, 1, 3, '2016-12-02', 6, 'From purchase delivery.'),
(6, 6, 1, 3, '2016-12-02', 6, 'From purchase delivery.'),
(7, 7, 1, 4, '2016-12-02', 6, 'From purchase delivery.'),
(8, 8, 1, 4, '2016-12-02', 6, 'From purchase delivery.'),
(9, 1, 1, 1, '2016-12-02', 28, 'Cancel delivery.'),
(10, 7, 2, 2, '2016-12-02', 8, 'From Delivery Received.'),
(11, 8, 2, 2, '2016-12-02', 8, 'From Delivery Received.'),
(12, 2, 2, 3, '2016-12-02', 8, 'From Delivery Received.'),
(13, 3, 2, 3, '2016-12-02', 8, 'From Delivery Received.'),
(14, 4, 2, 3, '2016-12-02', 8, 'From Delivery Received.'),
(15, 5, 2, 3, '2016-12-02', 8, 'From Delivery Received.'),
(16, 6, 2, 3, '2016-12-02', 8, 'From Delivery Received.'),
(17, 2, 2, 1, '2016-12-02', 10, 'Sell item.');

--
-- Dumping data for table `user_profile`
--

INSERT INTO `user_profile` (`id`, `branch_id`, `employee_position_id`, `first_name`, `surname`, `middle_name`, `birthday`, `address`, `contact_no`, `date_hired`, `active`, `statid`) VALUES
(1, 1, 1, 'Admin', '', '', '0000-00-00', '', '', '0000-00-00', 1, 1),
(2, 2, 2, 'test', 'test', 'test', '1990-11-29', 'Test address', '12345', '2016-12-01', 1, 1),
(3, 2, 3, 'testdeleteabc', 'testdelete', 'testdelete', '2016-12-02', 'testdelete', '123', '2016-12-02', 1, 2);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
