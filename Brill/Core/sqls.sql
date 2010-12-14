/*
Изменения накатываемые на БД
*/

/*
 *   10.12.2010 almazKo
 */
ALTER TABLE `as_SubscribesSites` CHANGE COLUMN `status` `status` ENUM('No','Ok','Busy','Error') NOT NULL DEFAULT 'No' ;


/*
 * 14.12.2010 almazKo
 */

CREATE  TABLE `au_Users` (
  `id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `login` VARCHAR(45) NOT NULL ,
  `password` VARCHAR(32) NOT NULL ,
  `name` VARCHAR(64) NULL ,
  `group_id` SMALLINT UNSIGNED NOT NULL ,
  `status` ENUM('Active', 'Blocked', 'Deleted') NULL ,
  `date_last` TIMESTAMP NOT NULL ,
  `date_created` TIMESTAMP NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `group_id` (`group_id` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;