/*
Изменения накатываемые на БД
*/

/*
 *   10.12.2010 almazKo
 */
ALTER TABLE `as_SubscribesSites` CHANGE COLUMN `status` `status` ENUM('No','Ok','Busy','Error') NOT NULL DEFAULT 'No' ;
