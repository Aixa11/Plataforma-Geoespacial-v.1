CREATE TABLE users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  last_login DATETIME
);
INSERT INTO users (username, password, last_login)
VALUES ('nombre', 'hashed_password', NOW());
