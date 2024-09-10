<?php
include 'db.php';

class AuthorModel
{
    public function getAllAuthors()
    {
        global $pdo;
        $stmt = $pdo->query('SELECT * FROM authors');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertAuthor($email, $password, $firstName, $middleName, $lastName, $penName, $telNo)
    {
        global $pdo;
        $passwordHash = md5($password);
        $stmt = $pdo->prepare('INSERT INTO authors (email_address, password, first_name, middle_name, last_name, pen_name, tel_no) VALUES (?, ?, ?, ?, ?, ?, ?)');
        return $stmt->execute([$email, $passwordHash, $firstName, $middleName, $lastName, $penName, $telNo]);
    }

    public function updateAuthorStatus($authorId, $status)
    {
        global $pdo;
        $stmt = $pdo->prepare('UPDATE authors SET status = ?, last_logged_in = NOW() WHERE author_id = ?');
        return $stmt->execute([$status, $authorId]);
    }

    public function deleteAuthor($authorId)
    {
        global $pdo;
        $stmt = $pdo->prepare('DELETE FROM authors WHERE author_id = ?');
        return $stmt->execute([$authorId]);
    }

    public function getAuthorById($authorId)
    {
        global $pdo;
        $stmt = $pdo->prepare('SELECT * FROM authors WHERE author_id = ?');
        $stmt->execute([$authorId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // New method to update an author by ID
    public function updateAuthorById($authorId, $updates)
    {
        global $pdo;

        // Build the SQL query dynamically
        $fields = [];
        $values = [];
        foreach ($updates as $field => $value) {
            $fields[] = "$field = ?";
            $values[] = $value;
        }

        if (empty($fields)) {
            return false;
        }

        // Add the author_id to the values array
        $fieldsSql = implode(', ', $fields);
        $sql = "UPDATE authors SET $fieldsSql, last_logged_in = NOW() WHERE author_id = ?";
        $values[] = $authorId;

        $stmt = $pdo->prepare($sql);
        return $stmt->execute($values);
    }
}
