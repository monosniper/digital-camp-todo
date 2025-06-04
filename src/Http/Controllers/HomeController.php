<?php

namespace App\Http\Controllers;

use App\Core\View;
use App\Models\Todo;

class HomeController
{
    public function index(): void
    {
        View::render('index', [
            'todos' => Todo::all(),
        ]);
    }

    public function store(): void
    {
        $title = $_POST['title'] ?? null;
        $description = $_POST['description'] ?? null;
        $deadline = $_POST['deadline'] ?? null;

        $todo = new Todo();
        $todo->create([
            'title' => $title,
            'description' => $description,
            'deadline' => $deadline,
        ]);

        header('Location: /');
        exit;
    }

    public function update(array $params)
    {
        $id = $params['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo "Missing ID";
            exit;
        }

        $todo = Todo::find($id);
        if (!$todo) {
            http_response_code(404);
            echo "Todo not found";
            exit;
        }
        $data = $_POST;
        if (isset($data['is_done'])) {
            $todo->is_done = (bool)$data['is_done'];
        }

        $todo->save();

        header('Location: /');
        exit;
    }

    public function delete(array $params)
    {
        $id = $params['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo "Missing ID";
            exit;
        }

        $todo = Todo::find($id);
        if (!$todo) {
            http_response_code(404);
            echo "Todo not found";
            exit;
        }

        $todo->delete();

        header('Location: /');
        exit;
    }
}
