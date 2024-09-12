<?php
include 'db.php'; // Ensure this is included

class AuthorModel
{
    public function getAllAuthors()
    {
        global $pdo;
        try {
            $stmt = $pdo->query('SELECT * FROM authors');
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            file_put_contents('db_error_log.txt', $e->getMessage(), FILE_APPEND);
            throw new Exception('Failed to fetch authors');
        }
    }

    public function insertAuthor($email, $password, $firstName, $middleName, $lastName, $penName, $telNo)
    {
        global $pdo;
        try {
            $passwordHash = md5($password);
            $stmt = $pdo->prepare('INSERT INTO authors (email_address, password, first_name, middle_name, last_name, pen_name, tel_no) VALUES (?, ?, ?, ?, ?, ?, ?)');
            return $stmt->execute([$email, $passwordHash, $firstName, $middleName, $lastName, $penName, $telNo]);
        } catch (PDOException $e) {
            file_put_contents('db_error_log.txt', $e->getMessage(), FILE_APPEND);
            throw new Exception('Failed to insert author');
        }
    }

    public function deleteAuthor($authorId)
    {
        global $pdo;
        try {
            $stmt = $pdo->prepare('DELETE FROM authors WHERE author_id = ?');
            return $stmt->execute([$authorId]);
        } catch (PDOException $e) {
            file_put_contents('db_error_log.txt', $e->getMessage(), FILE_APPEND);
            throw new Exception('Failed to delete author');
        }
    }

    public function getAuthorById($authorId)
    {
        global $pdo;
        try {
            $stmt = $pdo->prepare('SELECT * FROM authors WHERE author_id = ?');
            $stmt->execute([$authorId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            file_put_contents('db_error_log.txt', $e->getMessage(), FILE_APPEND);
            throw new Exception('Failed to fetch author');
        }
    }

    public function updateAuthorById($authorId, $updates)
    {
        global $pdo;
        try {
            $fields = [];
            $values = [];
            foreach ($updates as $field => $value) {
                $fields[] = "$field = ?";
                $values[] = $value;
            }

            if (empty($fields)) {
                return false;
            }

            $fieldsSql = implode(', ', $fields);
            $sql = "UPDATE authors SET $fieldsSql, last_logged_in = NOW() WHERE author_id = ?";
            $values[] = $authorId;

            $stmt = $pdo->prepare($sql);
            return $stmt->execute($values);
        } catch (PDOException $e) {
            file_put_contents('db_error_log.txt', $e->getMessage(), FILE_APPEND);
            throw new Exception('Failed to update author');
        }
    }

    public function loginAuthor($email, $password)
    {
        global $pdo;
        try {
            $stmt = $pdo->prepare('SELECT * FROM authors WHERE email_address = ?');
            $stmt->execute([$email]);
            $author = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($author && md5($password) === $author['password']) {
                return $author;
            }

            return false;
        } catch (PDOException $e) {
            file_put_contents('db_error_log.txt', $e->getMessage(), FILE_APPEND);
            throw new Exception('Failed to log in author');
        }
    }
}
