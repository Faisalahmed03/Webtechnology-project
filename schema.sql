
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'student', 
    is_verified BOOLEAN DEFAULT 0,
    verification_token VARCHAR(64) NULL,
    reset_token VARCHAR(64) NULL,
    reset_token_expiry DATETIME NULL,
    first_name VARCHAR(50) NULL,
    last_name VARCHAR(50) NULL,
    profile_picture_path VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE question_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE, 
    description TEXT NULL
);


INSERT IGNORE INTO `question_types` (`name`, `description`) VALUES
('Multiple Choice', 'User selects one or more correct answers from a list of options.'),
('Essay', 'User provides a free-form text answer.'),
('True/False', 'User selects either True or False.'),
('Matching', 'User matches items from two lists.');


CREATE TABLE difficulty_levels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE 


INSERT IGNORE INTO `difficulty_levels` (`name`) VALUES
('Easy'),
('Medium'),
('Hard');


CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_text TEXT NOT NULL,
    question_type_id INT NOT NULL,
    difficulty_level_id INT NULL,
    explanation_text TEXT NULL,
    created_by_user_id INT NULL, 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (question_type_id) REFERENCES question_types(id),
    FOREIGN KEY (difficulty_level_id) REFERENCES difficulty_levels(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by_user_id) REFERENCES users(id) ON DELETE SET NULL
);


CREATE TABLE question_options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT NOT NULL,
    option_text TEXT NOT NULL,
    is_correct BOOLEAN DEFAULT 0,
    `order` INT DEFAULT 0, 
    feedback_if_selected TEXT NULL, 
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
);


CREATE TABLE tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT NULL,
    created_by_user_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by_user_id) REFERENCES users(id) ON DELETE SET NULL
);


CREATE TABLE question_tags (
    question_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (question_id, tag_id),
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);


CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NULL,
    parent_id INT NULL, 
    created_by_user_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by_user_id) REFERENCES users(id) ON DELETE SET NULL
);


CREATE TABLE quizzes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    category_id INT NULL,
    created_by_user_id INT NOT NULL,
    duration_minutes INT DEFAULT 30, 
    timer_per_question_seconds INT DEFAULT 0, 
    randomize_questions BOOLEAN DEFAULT 1, 
    randomize_options BOOLEAN DEFAULT 1, 
    pass_mark DECIMAL(5,2) DEFAULT 50.00, 
    max_attempts INT DEFAULT 1, 
    show_feedback_after_attempt BOOLEAN DEFAULT 1,
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by_user_id) REFERENCES users(id) ON DELETE CASCADE,
);

 
CREATE TABLE quiz_questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT NOT NULL,
    question_id INT NOT NULL,
    points INT DEFAULT 1, 
    `order` INT DEFAULT 0, 
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE,
    UNIQUE (quiz_id, question_id), 
);


CREATE TABLE quiz_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT NOT NULL,
    user_id INT NOT NULL, 
    start_time DATETIME NOT NULL,
    end_time DATETIME NULL, 
    score DECIMAL(10, 2) NULL, 
    status ENUM('in-progress', 'completed', 'graded', 'abandoned', 'needs_manual_grading') DEFAULT 'in-progress',
    time_taken_seconds INT NULL, 
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    feedback_for_student TEXT NULL, 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE, 
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
CREATE TABLE student_answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_attempt_id INT NOT NULL,
    quiz_question_id INT NOT NULL, 
    question_id INT NOT NULL, 
    answer_text TEXT NULL, 
    selected_option_id INT NULL, 
    is_correct BOOLEAN NULL, 
    score_awarded DECIMAL(5, 2) DEFAULT 0.00, 
    teacher_feedback TEXT NULL, 
    FOREIGN KEY (quiz_attempt_id) REFERENCES quiz_attempts(id) ON DELETE CASCADE,
    FOREIGN KEY (quiz_question_id) REFERENCES quiz_questions(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE, 
    FOREIGN KEY (selected_option_id) REFERENCES question_options(id) ON DELETE SET NULL
);


CREATE TABLE certificates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    quiz_id INT NOT NULL,
    quiz_attempt_id INT NOT NULL UNIQUE, 
    issued_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    certificate_code VARCHAR(64) NOT NULL UNIQUE, 
    template_used VARCHAR(100) NULL,
    custom_details TEXT NULL, 
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE,
    FOREIGN KEY (quiz_attempt_id) REFERENCES quiz_attempts(id) ON DELETE CASCADE
);

