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

INSERT INTO `language_text` VALUES ('en', '_module','api','API', NOW(),'');
INSERT INTO `language_text` VALUES ('en', '_module','AT_ERROR_GOES_HERE','There was some error with the API request.', NOW(),'');