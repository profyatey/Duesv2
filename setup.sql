-- Run this once to set up your database

CREATE DATABASE IF NOT EXISTS swc_dues
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE swc_dues;

-- Members table
CREATE TABLE IF NOT EXISTS dues (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    name      VARCHAR(150)        NOT NULL,
    phone     VARCHAR(20)         NOT NULL UNIQUE,
    created_at DATETIME           DEFAULT CURRENT_TIMESTAMP
);

-- Payments table
CREATE TABLE IF NOT EXISTS payments (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    fgci_id    INT            NOT NULL,
    amount     DECIMAL(10, 2) NOT NULL,
    paid_at    DATETIME       DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (fgci_id) REFERENCES dues(id) ON DELETE CASCADE
);
