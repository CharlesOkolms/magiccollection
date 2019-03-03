CREATE TABLE card (
	id            INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
	img_url       VARCHAR(100)       NOT NULL,
	colors        VARCHAR(10)        NOT NULL,
	cost          VARCHAR(20)        NOT NULL,
	`set`         VARCHAR(50)        NOT NULL,
	set_name      VARCHAR(50)        NOT NULL,
	rarity        VARCHAR(10)        NOT NULL,
	price         DECIMAL(7, 2)      NOT NULL,
	last_update   DATETIME           NOT NULL,
	multiverse_id INT                NOT NULL UNIQUE,
	scryfall_id   VARCHAR(100)       NOT NULL UNIQUE,
	name_eng      VARCHAR(100)       NOT NULL,
	name_fra      VARCHAR(100)       NOT NULL,
	INDEX (name_eng),
	INDEX (name_fra)
)
	ENGINE = InnoDB;
