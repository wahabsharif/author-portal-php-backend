<?php
include '../models/AuthorModel.php';

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

    public function updateAuthor()
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Methods: PUT');
        header('Access-Control-Allow-Origin: *');

        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($input['author_id']) || !isset($input['status'])) {
            echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
            return;
        }

        $status = $input['status'] === 'Active' ? 'Active' : 'Inactive';

        // Use the model method for database operation
        $result = $this->model->updateAuthorStatus($input['author_id'], $status);

        echo json_encode(['status' => $result ? 'success' : 'error']);
    }
}
