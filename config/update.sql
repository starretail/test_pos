#2016-12-06
ALTER TABLE `sale_return_hdr` CHANGE `stock_location_id` `branch_id` INT(11) NOT NULL;
ALTER TABLE `sale_fifo` CHANGE `return_qty` `qty_return` INT(11) NOT NULL;

INSERT INTO `sale_return_type` (`id`, `name`, `tag`, `statid`) VALUES (NULL, 'Refund', '', '1'), (NULL, 'Replacement', '', '1');

CREATE TABLE `replacement_credit` (
  `id` bigint(20) NOT NULL,
  `amount` float(20,5) NOT NULL,
  `sale_return_hdr_id` bigint(20) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `statid` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `replacement_credit`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `replacement_credit`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
