-- Tables--

CREATE TABLE IF NOT EXISTS users (
    user_id CHAR(36) PRIMARY KEY DEFAULT (UUID()),
    role ENUM('admin', 'staff') DEFAULT 'staff',
    name text not null,
    photo text,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
    created_at timestamp default CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS auth (
    user_id CHAR(36) PRIMARY KEY,
    email varchar(255) not null unique,
    password_hash varchar(255) not null,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at timestamp default CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS reports (
    report_id CHAR(36) PRIMARY KEY DEFAULT (UUID()),
    author_id CHAR(36),
    division_place ENUM('ikeja', 'lagos_island', 'badagry', 'epe', 'ikorodu', 'hq') not null,
    enrolment_officer_name text not null,
    enrolment_officer_phone VARCHAR(11) CHECK (LENGTH(enrolment_officer_phone) IN (10, 11)),
    system_id text,
    device ENUM('cpu', 'laptop', 'android') NOT NULL,
    reg_record INT CHECK (reg_record >= 0),
    sending_record INT CHECK (sending_record >= 0),
    duplicates_record INT CHECK (duplicates_record >= 0),
    success_record INT CHECK (success_record >= 0),
    error_record INT CHECK (error_record >= 0),
    remarks text,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at timestamp default CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(user_id) ON DELETE SET NULL
);