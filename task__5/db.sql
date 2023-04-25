CREATE TABLE Person (
  p_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  p_name varchar(128) NOT NULL DEFAULT '',
  mail varchar(128) NOT NULL DEFAULT '',
  year int(10) NOT NULL DEFAULT 0,
  gender BOOLEAN NOT NULL DEFAULT 0,
  limbs_num int(1) NOT NULL DEFAULT 4,
  biography varchar(256) NOT NULL DEFAULT '',
  p_login int(5) unsigned,
  p_pass char(8),
  PRIMARY KEY (p_id)
);

CREATE TABLE Ability (
  a_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  a_name varchar(128) NOT NULL,
  PRIMARY KEY (a_id)
);

INSERT INTO Ability (a_name) VALUES ('Invincibility');
INSERT INTO Ability (a_name) VALUES ('Levitation');
INSERT INTO Ability (a_name) VALUES ('Noclip');

CREATE TABLE Person_Ability (
  p_id int(10) unsigned NOT NULL,
  a_id int(10) unsigned NOT NULL,
  FOREIGN KEY (p_id)  REFERENCES Person (p_id),
  FOREIGN KEY (a_id) REFERENCES Ability (a_id)
);
