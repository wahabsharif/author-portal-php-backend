<?php
include '../db.php';

class AuthorModel
{
    public function getAllAuthors()
    {
        global $pdo; // Make sure to use the global $pdo variable
        $stmt = $pdo->query('SELECT * FROM authors');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertAuthor($email, $password, $firstName, $middleName, $lastName, $penName, $telNo)
    {
        global $pdo; // Make sure to use the global $pdo variable
        $passwordHash = md5($password); // For demonstration; consider using password_hash() in production.
        $stmt = $pdo->prepare('INSERT INTO authors (email_address, password, first_name, middle_name, last_name, pen_name, tel_no) VALUES (?, ?, ?, ?, ?, ?, ?)');
        return $stmt->execute([$email, $passwordHash, $firstName, $middleName, $lastName, $penName, $telNo]);
    }

    public function updateAuthorStatus($authorId, $status)
    {
        global $pdo; // Make sure to use the global $pdo variable
        $stmt = $pdo->prepare('UPDATE authors SET status = ?, last_logged_in = NOW() WHERE author_id = ?');
        return $stmt->execute([$status, $authorId]);
    }
}
