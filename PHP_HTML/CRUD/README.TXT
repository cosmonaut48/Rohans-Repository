This code will only work with a linked SQL database that can be linked by editing my pdo.php file to match your port etc. 
The table used will be listed below, within the database 'misc'
CREATE TABLE autos (
  autos_id INTEGER NOT NULL AUTO_INCREMENT,
  make VARCHAR(255),
  model VARCHAR(255),
  year INTEGER,
  mileage INTEGER,
  PRIMARY KEY (autos_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
