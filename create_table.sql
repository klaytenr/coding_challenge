-- destroy table if it is already there
DROP TABLE IF EXISTS `shows`.`Shows`; 

-- table creation
CREATE TABLE `shows`.`Shows` (
    `id` INT NULL,
    `seriesName` VARCHAR(45),
    `overview` TEXT(500) NULL,
    `network` VARCHAR(45),
    `banner` VARCHAR(45),
    `imdbId` VARCHAR(45),
    `episode1Name` VARCHAR(45),
    `episode1fileName` VARCHAR(45),
    `episode1overview` TEXT(500) NULL,
    `episode2Name` VARCHAR(45),
    `episode2fileName` VARCHAR(45),
    `episode2overview` TEXT(500) NULL,
    `episode3Name` VARCHAR(45),
    `episode3fileName` VARCHAR(45),
    `episode3overview` TEXT(500) NULL);