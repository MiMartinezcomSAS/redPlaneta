ALTER TABLE 'planet_planeta'.'yq6g5_community_users' 
ADD COLUMN 'country' INT NULL AFTER 'search_email';

ALTER TABLE `planet_planeta`.`yq6g5_users`
ADD COLUMN `country` INT NULL AFTER `resetCount`;
