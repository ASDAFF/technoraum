create table if not exists ipol_sdeklogs
(
	ID int(10) NOT NULL auto_increment,
	ACCOUNT varchar(32),
	SECURE varchar(32),
	ACTIVE varchar(1),
	LABEL varchar(15),
	PRIMARY KEY(ID)
);