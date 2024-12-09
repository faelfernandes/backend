<?php

namespace App\Services;

use App\Repositories\Contracts\ContactRepositoryInterface;
use App\Exceptions\Contact\ContactNotFoundException;
use App\Exceptions\Contact\ContactCreationException;
use App\Exceptions\Contact\ContactUpdateException;
use App\Exceptions\Contact\ContactDeleteException;
use App\Exceptions\Contact\ContactExportException;
use App\Exceptions\Contact\ContactListException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContactService
{
    public function __construct(
        protected ContactRepositoryInterface $contactRepository
    ) {}

    public function listContacts($perPage = 15, array $filters = [])
    {
        try {
            return $this->contactRepository->paginateWithFilters($perPage, $filters);
        } catch (\Exception $e) {
            Log::error('Erro ao listar contatos', [
                'perPage' => $perPage,
                'filters' => $filters,
                'error' => $e->getMessage()
            ]);
            throw new ContactListException($e->getMessage());
        }
    }

    public function getContact(int $id)
    {
        try {
            return $this->contactRepository->find($id);
        } catch (ContactNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Erro ao buscar contato', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);
            throw new ContactNotFoundException($e->getMessage());
        }
    }

    public function storeContact(array $data)
    {
        try {
            return DB::transaction(function () use ($data) {
                $contactData = [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['phone']
                ];

                $contact = $this->contactRepository->create($contactData);
                $contact->address()->create($data['address']);

                return $contact->load('address');
            });
        } catch (\Exception $e) {
            Log::error('Erro ao criar contato', [
                'data' => $data,
                'error' => $e->getMessage()
            ]);
            throw new ContactCreationException($e->getMessage());
        }
    }

    public function updateContact(int $id, array $data)
    {
        try {
            return DB::transaction(function () use ($id, $data) {
                $contact = $this->contactRepository->find($id);

                if (!$contact) {
                    throw new ContactNotFoundException();
                }

                $contactData = [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['phone']
                ];

                $contact->update($contactData);
                $contact->address->update($data['address']);

                return $contact->load('address');
            });
        } catch (ContactNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar contato', [
                'id' => $id,
                'data' => $data,
                'error' => $e->getMessage()
            ]);
            throw new ContactUpdateException($e->getMessage());
        }
    }

    public function deleteContact(int $id)
    {
        try {
            return DB::transaction(function () use ($id) {
                if (!$this->contactRepository->exists($id)) {
                    throw new ContactNotFoundException();
                }

                return $this->contactRepository->delete($id);
            });
        } catch (ContactNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Erro ao excluir contato', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);
            throw new ContactDeleteException($e->getMessage());
        }
    }

    public function exportContactsToCsv(array $contactIds)
    {
        try {
            $contacts = $this->contactRepository->findMany($contactIds);

            if ($contacts->isEmpty()) {
                throw new ContactNotFoundException();
            }

            return $contacts;
        } catch (ContactNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Erro ao exportar contatos', [
                'contactIds' => $contactIds,
                'error' => $e->getMessage()
            ]);
            throw new ContactExportException($e->getMessage());
        }
    }

    public function exists($id): bool
    {
        return $this->contactRepository->exists($id);
    }
}
