<?php

namespace App\Repositories\Contracts;

interface ContactRepositoryInterface
{
    /**
     * Encontra um contato por ID
     *
     * @param int $id
     * @return \App\Models\Contact|null
     */
    public function find($id);

    /**
     * Cria um novo contato
     *
     * @param array $data
     * @return \App\Models\Contact
     */
    public function create(array $data);

    /**
     * Atualiza um contato existente
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\Contact
     */
    public function update($id, array $data);

    /**
     * Remove um contato
     *
     * @param int $id
     * @return void
     */
    public function delete($id);

    /**
     * Encontra múltiplos contatos por IDs
     *
     * @param array $ids
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findMany(array $ids);

    /**
     * Pagina os contatos
     *
     * @param int $perPage
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function paginate(int $perPage);

    /**
     * Verifica se um contato existe
     *
     * @param int $id
     * @return bool
     */
    public function exists($id): bool;

    /**
     * Pagina os contatos com filtros
     *
     * @param int $perPage
     * @param array $filters
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginateWithFilters(int $perPage, array $filters);
}
