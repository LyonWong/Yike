alter table `money`
  add `sign` int UNSIGNED not null default '0',
  add index `uid-item-sign` (`uid`,`i_item`, `sign`)
;
