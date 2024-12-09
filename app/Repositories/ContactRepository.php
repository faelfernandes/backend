<?php

namespace App\Repositories;

use App\Models\Contact;
use App\Repositories\Contracts\ContactRepositoryInterface;

class ContactRepository implements ContactRepositoryInterface
{
    public function __construct(
        protected Contact $model
    ) {}

    public function find($id)
    {
        return $this->model->with('address')->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $contact = $this->model->find($id);

        if (!$contact) {
            return null;
        }

        $contact->update($data);
        return $contact->fresh();
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    public function findMany(array $ids)
    {
        return $this->model->with('address')->whereIn('id', $ids)->get();
    }

    public function paginate(int $perPage)
    {
        return $this->model->with('address')->paginate($perPage);
    }

    public function exists($id): bool
    {
        return $this->model->where('id', $id)->exists();
    }

    public function paginateWithFilters(int $perPage, array $filters)
    {
        $query = Contact::with('address')->orderBy('id', 'desc');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['state'])) {
            $query->whereHas('address', function ($q) use ($filters) {
                $q->where('state', $filters['state']);
            });
        }

        if (!empty($filters['city'])) {
            $query->whereHas('address', function ($q) use ($filters) {
                $q->where('city', 'like', "%{$filters['city']}%");
            });
        }

        return $query->paginate($perPage);
    }
}
