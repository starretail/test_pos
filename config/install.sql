

CREATE TABLE IF NOT EXISTS `authorize` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_profile_id` bigint(20) NOT NULL,
  `uname` varchar(255) NOT NULL,
  `pword` varchar(32) NOT NULL,
  `role` enum('default','admin','owner','supervisor','staff') NOT NULL DEFAULT 'default',
  `branch_id` int(11) NOT NULL DEFAULT '1',
  `statid` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `authorize`
--

INSERT INTO `authorize` (`id`, `user_profile_id`, `uname`, `pword`, `role`, `branch_id`, `statid`) VALUES
(1, 1, 'owner', 'b4af804009cb036a4ccdc33431ef9ac9', 'owner', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `branch`
--

CREATE TABLE IF NOT EXISTS `branch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `statid` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `branch`
--

INSERT INTO `branch` (`id`, `name`, `tag`, `building_street`, `barangay`, `city_district`, `province`, `landline_no`, `mobile_no`, `date_active`, `active`, `statid`) VALUES
(1, 'Main Office', '', '', '', '', '', '', '', '0000-00-00', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `delivery`
--

CREATE TABLE IF NOT EXISTS `delivery` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
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
  `cancel_remarks` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_receive`
--

CREATE TABLE IF NOT EXISTS `delivery_receive` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `delivery_id` bigint(20) NOT NULL,
  `qty` int(11) NOT NULL,
  `create_date` date NOT NULL,
  `create_time` time NOT NULL,
  `create_by` int(11) NOT NULL,
  `statid` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `deposit`
--

CREATE TABLE IF NOT EXISTS `deposit` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `deposit_date` date NOT NULL,
  `account_no` varchar(255) NOT NULL,
  `account_name` varchar(255) NOT NULL,
  `amount` float(20,5) NOT NULL,
  `deposit_by` varchar(255) NOT NULL,
  `create_date` date NOT NULL,
  `create_time` time NOT NULL,
  `create_by` int(11) NOT NULL,
  `confirm` tinyint(1) NOT NULL,
  `statid` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `employee_position`
--

CREATE TABLE IF NOT EXISTS `employee_position` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `statid` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `employee_position`
--

INSERT INTO `employee_position` (`id`, `name`, `statid`) VALUES
(1, 'Administrator', 1),
(2, 'Supervisor', 1),
(3, 'Staff', 1);

-- --------------------------------------------------------

--
-- Table structure for table `fifo`
--

CREATE TABLE IF NOT EXISTS `fifo` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
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
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE IF NOT EXISTS `item` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `tag` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `serial` tinyint(1) NOT NULL,
  `srp` float(20,5) NOT NULL,
  `dp` float(20,5) NOT NULL,
  `cost` float(20,5) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `statid` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `item_category`
--

CREATE TABLE IF NOT EXISTS `item_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `statid` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `item_category`
--

INSERT INTO `item_category` (`id`, `name`, `statid`) VALUES
(1, 'Mobile', 1),
(2, 'Tablet', 1),
(3, 'Accessories', 1),
(4, 'Load Credits', 1);

-- --------------------------------------------------------

--
-- Table structure for table `payment_type`
--

CREATE TABLE IF NOT EXISTS `payment_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `credit_card` tinyint(1) NOT NULL,
  `statid` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `payment_type`
--

INSERT INTO `payment_type` (`id`, `name`, `credit_card`, `statid`) VALUES
(1, 'Cash', 0, 1),
(2, 'Replacement Credit', 0, 1),
(3, 'Credit Card Straight', 1, 1),
(4, 'Credit Card 3 Months', 1, 1),
(5, 'Credit Card 6 Months', 1, 1),
(6, 'Credit Card 9 Months', 1, 1),
(7, 'Credit Card 12 Months', 1, 1),
(8, 'Credit Card 18 Months', 1, 1),
(9, 'Credit Card 24 Months', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `promotion`
--

CREATE TABLE IF NOT EXISTS `promotion` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `item_tag` varchar(255) NOT NULL,
  `discount` float(20,5) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `active` tinyint(1) NOT NULL,
  `statid` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `replacement_credit`
--

CREATE TABLE IF NOT EXISTS `replacement_credit` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `amount` float(20,5) NOT NULL,
  `sale_return_hdr_id` bigint(20) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `statid` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sale_fifo`
--

CREATE TABLE IF NOT EXISTS `sale_fifo` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sale_hdr_id` bigint(20) NOT NULL,
  `qty_sold` int(11) NOT NULL,
  `srp` float(20,5) NOT NULL,
  `cost` float(20,5) NOT NULL,
  `profit` float(20,5) NOT NULL,
  `fifo_id` bigint(20) NOT NULL,
  `delivery_id` bigint(20) NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `statid` tinyint(1) NOT NULL,
  `qty_return` int(11) NOT NULL,
  `promotion_id` bigint(20) NOT NULL,
  `discount_amount` float(20,5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sale_hdr`
--

CREATE TABLE IF NOT EXISTS `sale_hdr` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `invoice` varchar(255) NOT NULL,
  `customer` varchar(255) NOT NULL,
  `create_date` date NOT NULL,
  `create_by` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `cancel_date` date NOT NULL,
  `cancel_by` int(11) NOT NULL,
  `statid` tinyint(1) NOT NULL,
  `proceed_to_payment` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sale_payment`
--

CREATE TABLE IF NOT EXISTS `sale_payment` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sale_hdr_id` bigint(20) NOT NULL,
  `payment_type_id` int(11) NOT NULL,
  `card_no` varchar(255) NOT NULL,
  `amount` float(20,5) NOT NULL,
  `statid` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sale_return_fifo`
--

CREATE TABLE IF NOT EXISTS `sale_return_fifo` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sale_return_hdr_id` bigint(20) NOT NULL,
  `sale_hdr_id` bigint(20) NOT NULL,
  `sale_fifo_id` bigint(20) NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `qty_return` int(11) NOT NULL,
  `price` float(20,5) NOT NULL,
  `cost` float(20,5) NOT NULL,
  `statid` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sale_return_hdr`
--

CREATE TABLE IF NOT EXISTS `sale_return_hdr` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sale_hdr_id` bigint(20) NOT NULL,
  `create_date` date NOT NULL,
  `create_time` time NOT NULL,
  `create_by` int(11) NOT NULL,
  `return_type_id` int(11) NOT NULL,
  `statid` tinyint(1) NOT NULL,
  `remarks` text NOT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sale_return_payment`
--

CREATE TABLE IF NOT EXISTS `sale_return_payment` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sale_return_hdr_id` bigint(20) NOT NULL,
  `sale_hdr_id` bigint(20) NOT NULL,
  `pay_type_id` int(11) NOT NULL,
  `amount` float(20,5) NOT NULL,
  `statid` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sale_return_type`
--

CREATE TABLE IF NOT EXISTS `sale_return_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `tag` varchar(255) NOT NULL,
  `statid` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `serial`
--

CREATE TABLE IF NOT EXISTS `serial` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `imei` varchar(32) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `transaction_no` bigint(20) NOT NULL,
  `transaction_date` date NOT NULL,
  `statid` tinyint(1) NOT NULL,
  `remarks` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `serial_history`
--

CREATE TABLE IF NOT EXISTS `serial_history` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `serial_id` bigint(20) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `transaction_no` bigint(20) NOT NULL,
  `transaction_date` date NOT NULL,
  `statid` tinyint(4) NOT NULL,
  `remarks` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `srp_history`
--

CREATE TABLE IF NOT EXISTS `srp_history` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `item_id` bigint(20) NOT NULL,
  `old` decimal(20,5) NOT NULL,
  `new` decimal(20,5) NOT NULL,
  `date` date NOT NULL,
  `statid` tinyint(1) NOT NULL,
  `remarks` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

CREATE TABLE IF NOT EXISTS `user_profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `statid` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `user_profile`
--

INSERT INTO `user_profile` (`id`, `branch_id`, `employee_position_id`, `first_name`, `surname`, `middle_name`, `birthday`, `address`, `contact_no`, `date_hired`, `active`, `statid`) VALUES
(1, 1, 1, 'Admin', '', '', '0000-00-00', '', '', '0000-00-00', 1, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
