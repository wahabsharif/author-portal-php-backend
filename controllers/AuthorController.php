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
        header('Content-Type: application/json');
        $data = $this->model->getAllAuthors();
        echo json_encode($data);
    }

    public function addAuthor()
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Allow-Origin: *');

        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($input['email']) || !isset($input['password']) || !isset($input['first_name']) || !isset($input['last_name'])) {
            echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
            return;
        }

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
    }
    public function deleteAuthor($authorId)
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Methods: DELETE');
        header('Access-Control-Allow-Origin: *');

        if (!$authorId) {
            echo json_encode(['status' => 'error', 'message' => 'Missing author_id']);
            return;
        }

        $result = $this->model->deleteAuthor($authorId);

        echo json_encode(['status' => $result ? 'success' : 'error']);
    }

    public function getAuthorById($authorId)
    {
        header('Content-Type: application/json');
        $data = $this->model->getAuthorById($authorId);
        echo json_encode($data);
    }

    public function updateAuthorById($authorId)
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Methods: PUT');
        header('Access-Control-Allow-Origin: *');

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

        $result = $this->model->updateAuthorById($authorId, $updates);

        echo json_encode(['status' => $result ? 'success' : 'error']);
    }
}
