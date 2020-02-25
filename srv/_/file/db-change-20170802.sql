alter table `order` add extra json after `i_status`;
alter table `rating`
  add `reply` text comment 'teacher reply' after `remark`,
  add `tms_reply` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP;