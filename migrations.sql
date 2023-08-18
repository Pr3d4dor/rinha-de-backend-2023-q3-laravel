create table `people` (
    `id` bigint unsigned not null auto_increment primary key,
    `uuid` char(36) not null,
    `nickname` varchar(32) not null,
    `name` varchar(100) not null,
    `date_of_birth` date not null,
    `stack` json null
) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `people` add unique `people_uuid_unique`(`uuid`);
alter table `people` add unique `people_nickname_unique`(`nickname`);
