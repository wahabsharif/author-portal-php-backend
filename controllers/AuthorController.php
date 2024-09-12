<?php
include 'models/AuthorModel.php';


class AuthorController
{
    protected $model;

    public function __construct()
    {
        $this->model = new AuthorModel();
    }

    public function getAuthors()
    {
        handleCORS();
        try {
            $data = $this->model->getAllAuthors();
            echo json_encode($data);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to fetch authors']);
        }
    }

    public function addAuthor()
    {
        handleCORS();
        header('Access-Control-Allow-Methods: POST');

        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($input['email']) || !isset($input['password']) || !isset($input['first_name']) || !isset($input['last_name'])) {
            echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
            return;
        }

        try {
            $result = $this->model->insertAuthor(
                $input['email'],
                $input['password'],
                $input['first_name'],
                $input['middle_name'] ?? '',
                $input['last_name'],
                $input['pen_name'] ?? '',
                $input['tel_no'] ?? ''
            );
            echo json_encode(['status' => $result ? 'success' : 'error']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add author']);
        }
    }

    public function deleteAuthor($authorId)
    {
        handleCORS();
        header('Access-Control-Allow-Methods: DELETE');

        if (!$authorId) {
            echo json_encode(['status' => 'error', 'message' => 'Missing author_id']);
            return;
        }

        try {
            $result = $this->model->deleteAuthor($authorId);
            echo json_encode(['status' => $result ? 'success' : 'error']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete author']);
        }
    }

    public function getAuthorById($authorId)
    {
        handleCORS();
        try {
            $data = $this->model->getAuthorById($authorId);
            echo json_encode($data);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to fetch author']);
        }
    }

    public function updateAuthorById($authorId)
    {
        handleCORS();
        header('Access-Control-Allow-Methods: PUT');

        $input = json_decode(file_get_contents('php://input'), true);

        if (!$authorId) {
            echo json_encode(['status' => 'error', 'message' => 'Missing author_id']);
            return;
        }

        $updates = [];
        if (isset($input['email'])) {
            $updates['email_address'] = $input['email'];
        }
        if (isset($input['password'])) {
            $updates['password'] = md5($input['password']);
        }
        if (isset($input['first_name'])) {
            $updates['first_name'] = $input['first_name'];
        }
        if (isset($input['middle_name'])) {
            $updates['middle_name'] = $input['middle_name'];
        }
        if (isset($input['last_name'])) {
            $updates['last_name'] = $input['last_name'];
        }
        if (isset($input['pen_name'])) {
            $updates['pen_name'] = $input['pen_name'];
        }
        if (isset($input['tel_no'])) {
            $updates['tel_no'] = $input['tel_no'];
        }
        if (empty($updates)) {
            echo json_encode(['status' => 'error', 'message' => 'No fields to update']);
            return;
        }

        try {
            $result = $this->model->updateAuthorById($authorId, $updates);
            echo json_encode(['status' => $result ? 'success' : 'error']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update author']);
        }
    }

    public function loginAuthor()
    {
        handleCORS();
        header('Access-Control-Allow-Methods: POST');

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($input['email']) || !isset($input['password'])) {
            echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
            return;
        }

        try {
            $author = $this->model->loginAuthor($input['email'], $input['password']);
            if ($author) {
                // Generate a simple token (in production, you should use JWT or a similar secure method)
                $token = base64_encode($author['email_address'] . ':' . $author['password']);

                // Store the token in the session
                $_SESSION['token'] = $token;

                echo json_encode(['status' => 'success', 'token' => $token, 'author' => $author]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Invalid email or password']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to log in']);
        }
    }
}
