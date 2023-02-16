-- Create database
CREATE DATABASE raid_scheduler;

-- Create events table
CREATE TABLE `raid_scheduler`.`events`
(
    `id` int NOT NULL auto_increment,
    `group` varchar(50),
    `date` varchar(60),
    `status` bigint(10),
    `created_at` timestamp,
    `updated_at` timestamp, PRIMARY KEY (id)
);