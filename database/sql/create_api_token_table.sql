CREATE TABLE api_token (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    api_token VARCHAR(255) NOT NULL,
    fecha_token_inicio DATETIME NOT NULL,
    fecha_fin_token DATETIME NOT NULL
);

CREATE INDEX idx_api_token_token ON api_token (api_token);
