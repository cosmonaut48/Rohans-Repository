The table used will be listed below, within the database 'misc'. Please set this in your pdo.php file accordingly
CREATE TABLE autos (
  autos_id INTEGER NOT NULL AUTO_INCREMENT,
  make VARCHAR(255),
  model VARCHAR(255),
  year INTEGER,
  mileage INTEGER,
  PRIMARY KEY (autos_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
