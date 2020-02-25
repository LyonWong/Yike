alter table lesson modify tags json default null;
update lesson set tags = '{}';
