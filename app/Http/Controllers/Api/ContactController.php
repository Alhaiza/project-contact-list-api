<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\StoreContactRequest;
use App\Http\Requests\Contact\UpdateContactRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use App\Services\ContactService;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(protected ContactService $contactService) {}


    public function index()
    {
        $contacts = $this->contactService->getAllContacts();
        $data =  ContactResource::collection($contacts);

        return $this->sendResponse($data, 'Contacts retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContactRequest $request)
    {
        $contact = $this->contactService->createContact($request->validated());
        $data = new ContactResource($contact);
        return $this->sendResponse($data, 'Contacts created successfully.', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        if ($contact->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = new ContactResource($contact);

        return $this->sendResponse($data, 'Contact retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContactRequest $request, Contact $contact)
    {
        if ($contact->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $contact = $this->contactService->updateContact($contact, $request->validated());
        $data = new ContactResource($contact);

        return $this->sendResponse($data, 'Contact updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        if ($contact->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $this->contactService->deleteContact($contact);
        return $this->sendResponse([], 'Contact deleted successfully.');
    }
}
