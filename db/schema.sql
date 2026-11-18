SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS wikipan
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_0900_ai_ci;
USE wikipan;

CREATE TABLE IF NOT EXISTS users (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username      VARCHAR(32)  NOT NULL,
  email         VARCHAR(190) DEFAULT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role          ENUM('user','admin') NOT NULL DEFAULT 'user',
  avatar_hue    SMALLINT UNSIGNED NOT NULL DEFAULT 210,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uk_username (username),
  UNIQUE KEY uk_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS categories (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  slug       VARCHAR(32) NOT NULL,
  name       VARCHAR(32) NOT NULL,
  sort_order SMALLINT NOT NULL DEFAULT 0,
  UNIQUE KEY uk_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS repositories (
  id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  slug           VARCHAR(160) NOT NULL,
  title          VARCHAR(120) NOT NULL,
  original_title VARCHAR(160) DEFAULT NULL,
  category_id    INT UNSIGNED NOT NULL,
  year           SMALLINT UNSIGNED DEFAULT NULL,
  description    TEXT,
  poster         VARCHAR(500) DEFAULT NULL,
  backdrop       VARCHAR(500) DEFAULT NULL,
  tags           VARCHAR(200) DEFAULT NULL,
  featured       TINYINT(1) NOT NULL DEFAULT 0,
  heat           INT UNSIGNED NOT NULL DEFAULT 0,
  created_by     INT UNSIGNED NOT NULL,
  created_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY idx_category (category_id),
  KEY idx_heat (heat),
  KEY idx_created (created_at),
  KEY idx_featured (featured),
  CONSTRAINT fk_repo_category FOREIGN KEY (category_id) REFERENCES categories (id),
  CONSTRAINT fk_repo_user FOREIGN KEY (created_by) REFERENCES users (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS resources (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  repository_id INT UNSIGNED NOT NULL,
  title         VARCHAR(200) NOT NULL,
  quality       VARCHAR(16)  NOT NULL DEFAULT '1080P',
  netdisk       VARCHAR(16)  NOT NULL DEFAULT 'quark',
  share_url     VARCHAR(500) NOT NULL,
  access_code   VARCHAR(16)  DEFAULT NULL,
  size          VARCHAR(24)  DEFAULT NULL,
  subtitle      VARCHAR(120) DEFAULT NULL,
  episodes      VARCHAR(24)  DEFAULT NULL,
  note          VARCHAR(255) DEFAULT NULL,
  votes         INT UNSIGNED NOT NULL DEFAULT 0,
  views         INT UNSIGNED NOT NULL DEFAULT 0,
  publisher_id  INT UNSIGNED NOT NULL,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY idx_repository (repository_id),
  KEY idx_created (created_at),
  KEY idx_votes (votes),
  CONSTRAINT fk_res_repo FOREIGN KEY (repository_id) REFERENCES repositories (id) ON DELETE CASCADE,
  CONSTRAINT fk_res_user FOREIGN KEY (publisher_id) REFERENCES users (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS resource_votes (
  user_id     INT UNSIGNED NOT NULL,
  resource_id INT UNSIGNED NOT NULL,
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (user_id, resource_id),
  KEY idx_resource (resource_id),
  CONSTRAINT fk_vote_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
  CONSTRAINT fk_vote_resource FOREIGN KEY (resource_id) REFERENCES resources (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
