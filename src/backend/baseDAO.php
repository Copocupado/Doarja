<?php

include_once __DIR__ . '/MySQLFunctions.php';
include_once __DIR__ . '/SessionManager.php';
abstract class BaseDAO
{
    protected abstract function getTableName(): string;
    protected abstract function getRole(): string;

    public function fetchAll(): array
    {
        try {
            $response = getTable($this->getTableName());

            if (!$response['success']) {
                return [
                    'success' => false,
                    'message' => 'Erro ao buscar todos os itens: ' . $response['message'],
                ];
            }

            return [
                'success' => true,
                'message' => $response['message'],
            ];
        } catch (\Throwable $th) {
            return [
                'success' => false,
                'message' => 'Erro ao buscar todos os itens: ' . $th->getMessage(),
            ];
        }
    }

    public function create($item): array
    {
        try {
            $response = addEntry($this->getTableName(), $item);

            if (!$response['success']) {
                return $response;
            }

            return [
                'success' => true,
                'message' => 'Item adicionado com sucesso',
            ];
        } catch (\Throwable $th) {
            return [
                'success' => false,
                'message' => 'Erro ao criar item: ' . $th->getMessage(),
            ];
        }
    }

    public function read($item): array
    {
        try {
            $response = getEntry($this->getTableName(), $item);

            if (!$response['success']) {
                return $response;
            }

            return [
                'success' => true,
                'message' => $response['message'],
            ];
        } catch (\Throwable $th) {
            return [
                'success' => false,
                'message' => 'Erro ao ler item: ' . $th->getMessage(),
            ];
        }
    }

    public function update($item): array
    {
        try {
            $response = updateEntry($this->getTableName(), 'email', $item['email'], $item);

            if (!$response['success']) {
                return $response;
            }

            return [
                'success' => true,
                'message' => 'Item atualizado com sucesso',
            ];
        } catch (\Throwable $th) {
            return [
                'success' => false,
                'message' => 'Erro ao atualizar item: ' . $th->getMessage(),
            ];
        }
    }

    public function delete($item): array
    {
        try {
            $response = deleteEntry($this->getTableName(), $item);

            if (!$response['success']) {
                return $response;
            }

            return [
                'success' => true,
                'message' => 'Item deletado com sucesso',
            ];
        } catch (\Throwable $th) {
            return [
                'success' => false,
                'message' => 'Erro ao deletar item: ' . $th->getMessage(),
            ];
        }
    }

    public function isItem(): array
    {
        try {
            $response = getSessionData();
            if (!$response['success']) {
                return $response;
            }

            $data = $response['message'];
            if ($data == null) {
                return [
                    'success' => true,
                    'message' => false,
                ];
            }
            return [
                'success' => true,
                'message' => $data['role'] == $this->getRole(),
            ];
        } catch (\Throwable $th) {
            return [
                'success' => false,
                'message' => 'Erro ao verificar o status do item: ' . $th->getMessage(),
            ];
        }
    }
}

class AdminDAO extends BaseDAO
{
    protected function getTableName(): string
    {
        return 'admins';
    }
    
    protected function getRole(): string
    {
        return 'admin';
    }
}

class EntidadeDAO extends BaseDAO
{
    protected function getTableName(): string
    {
        return 'entidades';
    }
    
    protected function getRole(): string
    {
        return 'entidade';
    }
}

class ItemDAO extends BaseDAO
{
    protected function getTableName(): string
    {
        return 'itens';
    }
    
    protected function getRole(): string
    {
        return 'item';
    }
}
