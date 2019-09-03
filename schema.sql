CREATE DATABASE yeticave_wel80
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;
USE yeticave_wel80;

CREATE TABLE category (
    id_cat INT AUTO_INCREMENT PRIMARY KEY,
    name_cat CHAR(15) NOT NULL UNIQUE,
    code_cat CHAR(15) NOT NULL UNIQUE
);

CREATE TABLE lot (
    id_lot INT AUTO_INCREMENT PRIMARY KEY,
    date_lot DATETIME DEFAULT NOW(),
    name_lot CHAR(100) NOT NULL,
    cat_id INT NOT NULL,
    description_lot TEXT,
    image_lot CHAR(100),
    author_id INT NOT NULL,
    initial_price DECIMAL DEFAULT 0,
    completion_date DATETIME NOT NULL,
    step_rate DECIMAL DEFAULT 0,
    winner_id INT
);

CREATE TABLE rate (
    id_rate INT AUTO_INCREMENT PRIMARY KEY,
    date_rate DATETIME DEFAULT NOW(),
    bet_amount DECIMAL NOT NULL,
    participant_id INT NOT NULL,
    lot_id INT NOT NULL
);

CREATE TABLE user (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    date_registration DATETIME DEFAULT NOW(),
    e_mail CHAR(100) NOT NULL UNIQUE,
    name_user CHAR(100) NOT NULL,
    password_user CHAR(64) NOT NULL,
    avatar CHAR(100),
    contact CHAR(255) NOT NULL
);

CREATE INDEX name_lot ON lot(name_lot);
CREATE INDEX name_user ON user(name_user);
CREATE INDEX cat_id ON lot(cat_id);
CREATE INDEX lot_id ON rate(lot_id);
CREATE INDEX author_id ON lot(author_id);
CREATE INDEX participant_id ON rate(participant_id);