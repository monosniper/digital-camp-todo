<?php

namespace App\Core;

use App\Database\Connection;
use PDO;
use Carbon\Carbon;

abstract class Model
{
    protected string $table;
    protected array $fillable = [];
    protected array $casts = [];

    protected PDO $db;
    protected array $attributes = [];

    public function __construct(array $attributes = [])
    {
        $this->db = Connection::getInstance();
        $this->fill($attributes);
    }

    public function __get(string $key)
    {
        return $this->getAttribute($key);
    }

    protected function getAttribute(string $key)
    {
        $value = $this->attributes[$key] ?? null;

        if (isset($this->casts[$key])) {
            switch ($this->casts[$key]) {
                case 'datetime':
                    return Carbon::parse($value);
                case 'bool':
                case 'boolean':
                    return (bool) $value;
                case 'int':
                case 'integer':
                    return (int) $value;
                case 'float':
                    return (float) $value;
            }
        }

        return $value;
    }

    protected function fill(array $data)
    {
        foreach ($data as $key => $value) {
            if ($key === 'id' || empty($this->fillable) || in_array($key, $this->fillable)) {
                $this->attributes[$key] = $value;
            }
        }
    }

    public function create(array $data)
    {
        $columns = array_keys($data);
        $placeholders = array_map(fn($key) => ":$key", $columns);

        $sql = "INSERT INTO {$this->table} (" . implode(',', $columns) . ") 
                VALUES (" . implode(',', $placeholders) . ")";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
    }

    public function update(int $id, array $data)
    {
        $set = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($data)));

        $sql = "UPDATE {$this->table} SET $set WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $data['id'] = $id;
        $stmt->execute($data);
    }

    public static function all(): array
    {
        $instance = new static;
        $stmt = $instance->db->query("SELECT * FROM {$instance->table} ORDER BY id DESC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($row) => new static($row), $rows);
    }

    public static function find(int $id): ?static
    {
        $instance = new static;
        $stmt = $instance->db->prepare("SELECT * FROM {$instance->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new static($data) : null;
    }

    public function save()
    {
        if (isset($this->attributes['id'])) {
            $id = $this->attributes['id'];
            $data = $this->attributes;
            unset($data['id']);
            $this->update($id, $data);
        } else {
            $this->create($this->attributes);
            $this->attributes['id'] = (int)$this->db->lastInsertId();
        }
    }

    public function __set(string $key, $value)
    {
        if (empty($this->fillable) || in_array($key, $this->fillable)) {
            $this->attributes[$key] = $value;
        }
    }

    public function delete()
    {
        if (!isset($this->attributes['id'])) {
            return false;
        }
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute(['id' => $this->attributes['id']]);
    }
}