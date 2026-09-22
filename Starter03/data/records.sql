CREATE DATABASE IF NOT EXISTS worldskills;
USE worldskills;

CREATE TABLE IF NOT EXISTS records (
  id INT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL
);

INSERT INTO records (id, name, email) VALUES
  (1, 'Asha Patel', 'asha.patel@example.test'),
  (2, 'Noah Wilson', 'noah.wilson@example.test'),
  (3, 'Mei Chen', 'mei.chen@example.test')
ON DUPLICATE KEY UPDATE name = VALUES(name), email = VALUES(email);
