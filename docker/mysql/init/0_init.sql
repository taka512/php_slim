create database IF NOT EXISTS sample_slim CHARACTER SET utf8;
GRANT ALL PRIVILEGES ON sample_slim.* TO slim_user@'%' IDENTIFIED BY 'slim_pass';
create database IF NOT EXISTS sample_slim_test CHARACTER SET utf8;
GRANT ALL PRIVILEGES ON sample_slim_test.* TO slim_user@'%' IDENTIFIED BY 'slim_pass';
FLUSH PRIVILEGES;
