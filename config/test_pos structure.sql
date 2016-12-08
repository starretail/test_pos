-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 04, 2016 at 09:05 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test_pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `authorize`
--

CREATE TABLE `authorize` (
  `id` bigint(20) NOT NULL,
  `user_profile_id` bigint(20) NOT NULL,
  `uname` varchar(255) NOT NULL,
  `pword` varchar(32) NOT NULL,
  `role` enum('default','admin','owner','supervisor','staff') NOT NULL DEFAULT 'default',
  `branch_id` int(11) NOT NULL DEFAULT '1',
  `statid` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `branch`
--

CREATE TABLE `branch` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `tag` varchar(255) NOT NULL,
  `building_street` varchar(255) NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `city_district` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `landline_no` varchar(255) NOT NULL,
  `mobile_no` varchar(255) NOT NULL,
  `date_active` date NOT NULL,
  `active` tinyint(1) NOT NULL,
  `statid` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customer_credit_det`
--

CREATE TABLE `customer_credit_det` (
  `id` bigint(20) NOT NULL,
  `sale_hdr_id` bigint(20) NOT NULL,
  `sale_payment_id` bigint(20) NOT NULL,
  `used_amount` float(20,5) NOT NULL,
  `used_date` date NOT NULL,
  `statid` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customer_credit_hdr`
--

CREATE TABLE `customer_credit_hdr` (
  `id` bigint(20) NOT NULL,
  `sale_return_hdr_id` bigint(20) NOT NULL,
  `amount` float(20,5) NOT NULL,
  `remaining_amount` float(20,5) NOT NULL,
  `create_date` date NOT NULL,
  `statid` tinyint(1) NOT NULL,
  `stock_location_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `delivery`
--

CREATE TABLE `delivery` (
  `id` bigint(20) NOT NULL,
  `create_date` date NOT NULL,
  `create_time` time NOT NULL,
  `create_by` int(11) NOT NULL,
  `from_branch_id` int(11) NOT NULL,
  `to_branch_id` int(11) NOT NULL,
  `delivery_no` varchar(255) NOT NULL,
  `delivery_date` date NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `qty` int(11) NOT NULL,
  `qty_receive` int(11) NOT NULL,
  `statid` tinyint(1) NOT NULL,
  `cancel_date` date NOT NULL,
  `cancel_time` time NOT NULL,
  `cancel_by` int(11) NOT NULL,
  `cancel_remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_receive`
--

CREATE TABLE `delivery_receive` (
  `id` bigint(20) NOT NULL,
  `delivery_id` bigint(20) NOT NULL,
  `qty` int(11) NOT NULL,
  `create_date` date NOT NULL,
  `create_time` time NOT NULL,
  `create_by` int(11) NOT NULL,
  `statid` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` bigint(20) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `statid` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `employee_position`
--

CREATE TABLE `employee_position` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `statid` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `fifo`
--

CREATE TABLE `fifo` (
  `id` bigint(20) NOT NULL,
  `delivery_id` bigint(20) NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `statid` tinyint(1) NOT NULL,
  `receive_qty` int(11) NOT NULL,
  `receive_date` datetime NOT NULL,
  `transaction_qty` int(11) NOT NULL,
  `transaction_date` datetime NOT NULL,
  `transaction` varchar(255) NOT NULL,
  `remarks` text NOT NULL,
  `cost` float(20,5) NOT NULL,
  `sequence_no` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `tag` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `serial` tinyint(1) NOT NULL,
  `srp` float(20,5) NOT NULL,
  `dp` float(20,5) NOT NULL,
  `cost` float(20,5) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `statid` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `item_category`
--

CREATE TABLE `item_category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `statid` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payment_type`
--

CREATE TABLE `payment_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `credit_card` tinyint(1) NOT NULL,
  `statid` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `promotion`
--

CREATE TABLE `promotion` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `item_tag` varchar(255) NOT NULL,
  `discount` float(20,5) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `active` tinyint(1) NOT NULL,
  `statid` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sale_fifo`
--

CREATE TABLE `sale_fifo` (
  `id` bigint(20) NOT NULL,
  `sale_hdr_id` bigint(20) NOT NULL,
  `qty_sold` int(11) NOT NULL,
  `srp` float(20,5) NOT NULL,
  `cost` float(20,5) NOT NULL,
  `profit` float(20,5) NOT NULL,
  `fifo_id` bigint(20) NOT NULL,
  `delivery_id` bigint(20) NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `statid` tinyint(1) NOT NULL,
  `return_qty` int(11) NOT NULL,
  `promotion_id` bigint(20) NOT NULL,
  `discount_amount` float(20,5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sale_hdr`
--

CREATE TABLE `sale_hdr` (
  `id` bigint(20) NOT NULL,
  `invoice` varchar(255) NOT NULL,
  `customer` varchar(255) NOT NULL,
  `create_date` date NOT NULL,
  `create_by` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `cancel_date` date NOT NULL,
  `cancel_by` int(11) NOT NULL,
  `statid` tinyint(1) NOT NULL,
  `proceed_to_payment` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sale_payment`
--

CREATE TABLE `sale_payment` (
  `id` bigint(20) NOT NULL,
  `sale_hdr_id` bigint(20) NOT NULL,
  `payment_type_id` int(11) NOT NULL,
  `card_no` varchar(255) NOT NULL,
  `amount` float(20,5) NOT NULL,
  `statid` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sale_return_fifo`
--

CREATE TABLE `sale_return_fifo` (
  `id` bigint(20) NOT NULL,
  `sale_return_hdr_id` bigint(20) NOT NULL,
  `sale_hdr_id` bigint(20) NOT NULL,
  `sale_fifo_id` bigint(20) NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `qty_return` int(11) NOT NULL,
  `price` float(20,5) NOT NULL,
  `cost` float(20,5) NOT NULL,
  `statid` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sale_return_hdr`
--

CREATE TABLE `sale_return_hdr` (
  `id` bigint(20) NOT NULL,
  `sale_hdr_id` bigint(20) NOT NULL,
  `create_date` date NOT NULL,
  `create_time` time NOT NULL,
  `create_by` int(11) NOT NULL,
  `return_type_id` int(11) NOT NULL,
  `statid` tinyint(1) NOT NULL,
  `remarks` text NOT NULL,
  `stock_location_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sale_return_payment`
--

CREATE TABLE `sale_return_payment` (
  `id` bigint(20) NOT NULL,
  `sale_return_hdr_id` bigint(20) NOT NULL,
  `sale_hdr_id` bigint(20) NOT NULL,
  `pay_type_id` int(11) NOT NULL,
  `amount` float(20,5) NOT NULL,
  `statid` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sale_return_type`
--

CREATE TABLE `sale_return_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `tag` varchar(255) NOT NULL,
  `statid` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `serial`
--

CREATE TABLE `serial` (
  `id` bigint(20) NOT NULL,
  `imei` varchar(32) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `transaction_no` bigint(20) NOT NULL,
  `transaction_date` date NOT NULL,
  `statid` tinyint(1) NOT NULL,
  `remarks` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `serial_history`
--

CREATE TABLE `serial_history` (
  `id` bigint(20) NOT NULL,
  `serial_id` bigint(20) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `transaction_no` bigint(20) NOT NULL,
  `transaction_date` date NOT NULL,
  `statid` tinyint(4) NOT NULL,
  `remarks` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `srp_history`
--

CREATE TABLE `srp_history` (
  `id` bigint(20) NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `old` decimal(20,5) NOT NULL,
  `new` decimal(20,5) NOT NULL,
  `date` date NOT NULL,
  `statid` tinyint(1) NOT NULL,
  `remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

CREATE TABLE `user_profile` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `employee_position_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `birthday` date NOT NULL,
  `address` varchar(255) NOT NULL,
  `contact_no` varchar(255) NOT NULL,
  `date_hired` date NOT NULL,
  `active` tinyint(1) NOT NULL,
  `statid` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `authorize`
--
ALTER TABLE `authorize`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branch`
--
ALTER TABLE `branch`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_credit_det`
--
ALTER TABLE `customer_credit_det`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_credit_hdr`
--
ALTER TABLE `customer_credit_hdr`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery`
--
ALTER TABLE `delivery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_receive`
--
ALTER TABLE `delivery_receive`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_position`
--
ALTER TABLE `employee_position`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fifo`
--
ALTER TABLE `fifo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_category`
--
ALTER TABLE `item_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_type`
--
ALTER TABLE `payment_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `promotion`
--
ALTER TABLE `promotion`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_fifo`
--
ALTER TABLE `sale_fifo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_hdr`
--
ALTER TABLE `sale_hdr`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_payment`
--
ALTER TABLE `sale_payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_return_fifo`
--
ALTER TABLE `sale_return_fifo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_return_hdr`
--
ALTER TABLE `sale_return_hdr`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_return_payment`
--
ALTER TABLE `sale_return_payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_return_type`
--
ALTER TABLE `sale_return_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `serial`
--
ALTER TABLE `serial`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `serial_history`
--
ALTER TABLE `serial_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `srp_history`
--
ALTER TABLE `srp_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `authorize`
--
ALTER TABLE `authorize`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `branch`
--
ALTER TABLE `branch`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `customer_credit_det`
--
ALTER TABLE `customer_credit_det`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customer_credit_hdr`
--
ALTER TABLE `customer_credit_hdr`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `delivery`
--
ALTER TABLE `delivery`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `delivery_receive`
--
ALTER TABLE `delivery_receive`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `employee_position`
--
ALTER TABLE `employee_position`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `fifo`
--
ALTER TABLE `fifo`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `item_category`
--
ALTER TABLE `item_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `payment_type`
--
ALTER TABLE `payment_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `promotion`
--
ALTER TABLE `promotion`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `sale_fifo`
--
ALTER TABLE `sale_fifo`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sale_hdr`
--
ALTER TABLE `sale_hdr`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sale_payment`
--
ALTER TABLE `sale_payment`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sale_return_fifo`
--
ALTER TABLE `sale_return_fifo`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sale_return_hdr`
--
ALTER TABLE `sale_return_hdr`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sale_return_payment`
--
ALTER TABLE `sale_return_payment`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sale_return_type`
--
ALTER TABLE `sale_return_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `serial`
--
ALTER TABLE `serial`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `serial_history`
--
ALTER TABLE `serial_history`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `srp_history`
--
ALTER TABLE `srp_history`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_profile`
--
ALTER TABLE `user_profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
