-- Demo customer for login testing (password is 'demo1234')
INSERT INTO customers (id, name, furigana, postcode_a, postcode_b, address, mail, password)
VALUES (1001, 'デモ太郎', 'でもたろう', 123, 4567, '東京都千代田区1-2-3', 'demo@example.com', 'demo1234')
ON DUPLICATE KEY UPDATE name=VALUES(name), mail=VALUES(mail), password=VALUES(password);
