CREATE DATABASE IF NOT EXISTS sample_slim CHARACTER SET utf8mb4;
CREATE DATABASE IF NOT EXISTS sample_slim_test CHARACTER SET utf8mb4;
CREATE USER IF NOT EXISTS 'slim_user'@'%' identified by 'slim_pass';
GRANT ALL PRIVILEGES ON sample_slim.* TO slim_user@'%' WITH GRANT OPTION;
GRANT ALL PRIVILEGES ON sample_slim_test.* TO slim_user@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
