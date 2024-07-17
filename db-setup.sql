-- Create Users table
CREATE TABLE Users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL
    is_admin BOOLEAN DEFAULT FALSE
);

-- Create Posts table
CREATE TABLE Posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    userId INT NOT NULL,
    title TEXT NOT NULL
    content TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userId) REFERENCES Users(id) ON DELETE CASCADE
);

-- Create Comments table
CREATE TABLE Comments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    postId INT NOT NULL,
    userId INT NOT NULL,
    content TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (postId) REFERENCES Posts(id) ON DELETE CASCADE,
    FOREIGN KEY (userId) REFERENCES Users(id) ON DELETE CASCADE
);

-- Create UserCommunications table
CREATE TABLE UserCommunications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);