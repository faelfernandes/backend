<?php

namespace App\Http\Controllers;

use App\Services\ContactService;
use App\Mail\ContactCreatedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Http\Requests\ExportContactsRequest;
use App\Exceptions\Contact\ContactNotFoundException;
use App\Exceptions\Contact\ContactCreationException;
use App\Exceptions\Contact\ContactUpdateException;
use App\Exceptions\Contact\ContactDeleteException;
use App\Exceptions\Contact\ContactExportException;
use App\Exceptions\Contact\ContactListException;

class ContactController extends Controller
{
    public function __construct(
        protected ContactService $contactService
    ) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'search' => $request->get('search'),
                'state' => $request->get('state'),
                'city' => $request->get('city'),
            ];

            $perPage = $request->get('per_page', 15);
            return response()->json($this->contactService->listContacts($perPage, $filters));
        } catch (ContactListException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            return response()->json($this->contactService->getContact($id));
        } catch (ContactNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function store(StoreContactRequest $request): JsonResponse
    {
        try {
            $contact = $this->contactService->storeContact($request->validated());

            Mail::to(env('NOTIFICATION_MAIL'))
                ->queue(new ContactCreatedMail($contact));

            return response()->json($contact, 201);
        } catch (ContactCreationException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function update(UpdateContactRequest $request, int $id): JsonResponse
    {
        try {
            if (!$this->contactService->exists($id)) {
                throw new ContactNotFoundException();
            }

            $contact = $this->contactService->updateContact($id, $request->validated());
            return response()->json($contact);
        } catch (ContactNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        } catch (ContactUpdateException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->contactService->deleteContact($id);
            return response()->json(['message' => 'Contato excluído com sucesso']);
        } catch (ContactNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        } catch (ContactDeleteException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function exportCsv(ExportContactsRequest $request)
    {
        try {
            $contacts = $this->contactService->exportContactsToCsv($request->validated()['contacts']);

            return response()->streamDownload(function () use ($contacts) {
                $file = fopen('php://output', 'w');
                fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
                fputcsv($file, [
                    'Nome',
                    'Email',
                    'Telefone',
                    'CEP',
                    'Estado',
                    'Cidade',
                    'Bairro',
                    'Rua',
                    'Número'
                ]);

                foreach ($contacts as $contact) {
                    fputcsv($file, [
                        $contact->name,
                        $contact->email,
                        $contact->phone,
                        $contact->address->zip_code,
                        $contact->address->state,
                        $contact->address->city,
                        $contact->address->neighborhood,
                        $contact->address->street,
                        $contact->address->number
                    ]);
                }

                fclose($file);
            }, 'contacts.csv', [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="contacts.csv"'
            ]);
        } catch (ContactNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        } catch (ContactExportException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
