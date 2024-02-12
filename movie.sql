CREATE TABLE
    movies (
        id INT AUTO_INCREMENT PRIMARY KEY,
        movieName VARCHAR(255) NOT NULL,
        movieLength INT NOT NULL,
        authorName VARCHAR(255) NOT NULL,
        movieImage VARCHAR(255) NOT NULL
    );