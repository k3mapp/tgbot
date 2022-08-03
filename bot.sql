CREATE TABLE admins(
    id int(10) NOT NULL AUTO_INCREMENT,
    user_id int(255) NOT NULL,
    CONSTRAINT pk_admins PRIMARY KEY(id)
)ENGINE=InnoDB;

CREATE TABLE banned_users(
    id int(50) NOT NULL AUTO_INCREMENT,
    user_id int(255) NOT NULL,
    username varchar(50) NULL,
    reason   varchar(255) NOT NULL,
    CONSTRAINT pk_banned_users PRIMARY KEY(id)
)ENGINE=InnoDB;

CREATE TABLE suggestions(
    id int(50) NOT NULL AUTO_INCREMENT,
    user_id int(255) NOT NULL,
    description varchar(255) NOT NULL,
    CONSTRAINT pk_suggestions PRIMARY KEY(id)
)ENGINE=InnoDB;

CREATE TABLE voices(
    id int(50) NOT NULL AUTO_INCREMENT,
    voice_url VARCHAR(200) NULL,
    description TEXT NULL,
    CONSTRAINT pk_voices PRIMARY KEY(id)
)ENGINE=InnoDB;

ALTER Table voices ADD FULLTEXT(description)

-- SELECT id, voice_url
-- FROM voices
-- WHERE MATCH(description) AGAINST('cago en la última farola que');