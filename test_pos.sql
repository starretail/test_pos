-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 22, 2016 at 01:21 PM
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
  `employee_id` bigint(20) NOT NULL,
  `uname` varchar(255) NOT NULL,
  `pword` varchar(32) NOT NULL,
  `role` enum('default','admin','owner') NOT NULL DEFAULT 'default',
  `stock_location_id` int(11) NOT NULL DEFAULT '1',
  `statid` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `authorize`
--

INSERT INTO `authorize` (`id`, `employee_id`, `uname`, `pword`, `role`, `stock_location_id`, `statid`) VALUES
(1, 1, 'owner', 'b4af804009cb036a4ccdc33431ef9ac9', 'owner', 0, 1);

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

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `first_name`, `last_name`, `statid`) VALUES
(1, 'Owner', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `fifo`
--

CREATE TABLE `fifo` (
  `id` bigint(20) NOT NULL,
  `purchase_receive_det_id` bigint(20) NOT NULL,
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
  `stock_location_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `serial` tinyint(1) NOT NULL,
  `srp` float(20,5) NOT NULL,
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
-- Table structure for table `pay_type`
--

CREATE TABLE `pay_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `tag` varchar(255) NOT NULL,
  `statid` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pay_type`
--

INSERT INTO `pay_type` (`id`, `name`, `tag`, `statid`) VALUES
(1, 'Cash', 'Cash', 1);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_receive_det`
--

CREATE TABLE `purchase_receive_det` (
  `id` bigint(20) NOT NULL,
  `purchase_receive_hdr_id` bigint(20) NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `qty` int(11) NOT NULL,
  `qty_imei` int(11) NOT NULL,
  `cost` float(20,5) NOT NULL,
  `statid` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_receive_hdr`
--

CREATE TABLE `purchase_receive_hdr` (
  `id` bigint(20) NOT NULL,
  `purchase_request_hdr_id` bigint(20) NOT NULL,
  `receive_date` date NOT NULL,
  `receive_time` time NOT NULL,
  `receive_by` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `reference_no` varchar(255) NOT NULL,
  `statid` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_request_det`
--

CREATE TABLE `purchase_request_det` (
  `id` bigint(20) NOT NULL,
  `purchase_request_hdr_id` bigint(20) NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `qty` int(11) NOT NULL,
  `cost` float(20,5) NOT NULL,
  `qty_bal` int(11) NOT NULL,
  `statid` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_request_hdr`
--

CREATE TABLE `purchase_request_hdr` (
  `id` bigint(20) NOT NULL,
  `reference_no` varchar(255) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `create_date` date NOT NULL,
  `create_by` int(11) NOT NULL,
  `approve_date` date NOT NULL,
  `approve_by` int(11) NOT NULL,
  `create_remarks` text NOT NULL,
  `approve_remarks` text NOT NULL,
  `statid` tinyint(1) NOT NULL,
  `payment_term` int(11) NOT NULL,
  `due_date` date NOT NULL
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
  `purchase_receive_det_id` bigint(20) NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `statid` tinyint(1) NOT NULL
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
  `stock_location_id` int(11) NOT NULL,
  `cancel_date` date NOT NULL,
  `cancel_by` int(11) NOT NULL,
  `statid` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sale_payment`
--

CREATE TABLE `sale_payment` (
  `id` bigint(20) NOT NULL,
  `sale_hdr_id` bigint(20) NOT NULL,
  `pay_type_id` int(11) NOT NULL,
  `amount` float(20,5) NOT NULL,
  `statid` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `serial`
--

CREATE TABLE `serial` (
  `id` bigint(20) NOT NULL,
  `imei` varchar(32) NOT NULL,
  `stock_location_id` int(11) NOT NULL,
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
  `stock_location_id` int(11) NOT NULL,
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
-- Table structure for table `stock_location`
--

CREATE TABLE `stock_location` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `tag` varchar(255) NOT NULL,
  `statid` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stock_location`
--

INSERT INTO `stock_location` (`id`, `name`, `tag`, `statid`) VALUES
(1, 'Head Office', 'HO', 1);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `statid` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id`, `name`, `address`, `statid`) VALUES
(1, 'Supplier', 'Supplier Address', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `authorize`
--
ALTER TABLE `authorize`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
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
-- Indexes for table `pay_type`
--
ALTER TABLE `pay_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_receive_det`
--
ALTER TABLE `purchase_receive_det`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_receive_hdr`
--
ALTER TABLE `purchase_receive_hdr`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_request_det`
--
ALTER TABLE `purchase_request_det`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_request_hdr`
--
ALTER TABLE `purchase_request_hdr`
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
-- Indexes for table `stock_location`
--
ALTER TABLE `stock_location`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `authorize`
--
ALTER TABLE `authorize`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `fifo`
--
ALTER TABLE `fifo`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `item_category`
--
ALTER TABLE `item_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pay_type`
--
ALTER TABLE `pay_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `purchase_receive_det`
--
ALTER TABLE `purchase_receive_det`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `purchase_receive_hdr`
--
ALTER TABLE `purchase_receive_hdr`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `purchase_request_det`
--
ALTER TABLE `purchase_request_det`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `purchase_request_hdr`
--
ALTER TABLE `purchase_request_hdr`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
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
-- AUTO_INCREMENT for table `serial`
--
ALTER TABLE `serial`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `serial_history`
--
ALTER TABLE `serial_history`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `srp_history`
--
ALTER TABLE `srp_history`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `stock_location`
--
ALTER TABLE `stock_location`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
