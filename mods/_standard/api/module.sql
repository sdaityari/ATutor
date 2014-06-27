# SQL after confirming structure

CREATE TABLE IF NOT EXISTS `api` (
    `id` serial,
    `member_id` varchar(30),
    `access_level` smallint,
    `token` varchar(50) NOT NULL,
    `created` timestamp DEFAULT CURRENT_TIMESTAMP,
    `modified` timestamp,
    `expiry` timestamp,
    PRIMARY KEY (`id`)
    );

CREATE TABLE IF NOT EXISTS `api_logs` (
    `id` serial,
    `member_id` varchar (30),
    `ip_address` varchar(15),
    `user_agent` varchar(500),
    `request_uri` varchar(500),
    `http_method` varchar(10),
    `token` varchar(50),
    `response` text,
    `request_time` timestamp default CURRENT_TIMESTAMP
);

INSERT INTO `config`(`name`, `value`) VALUES('api_logging_level', 1);

INSERT INTO `language_text` VALUES ('en', '_module','api','API', NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','AT_ERROR_GOES_HERE','There was some error with the API request.', NOW(),'');
