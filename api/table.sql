CREATE TABLE IF NOT EXISTS participants (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(25) NULL,
    rollNo VARCHAR(25) NOT NULL,
    projects VARCHAR(1000) NULL,
    created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);