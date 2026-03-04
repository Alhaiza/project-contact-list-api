<?php

namespace App\Services;

use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ContactService
{
    public function getAllContacts()
    {
        return Auth::user()->contacts()->latest()->get();
    }

    public function createContact(array $data)
    {
        if (isset($data['image'])) {
            $path = $data['image']->store('contacts', 'public');
            $data['image_path'] = $path;
        }
        return Auth::user()->contacts()->create($data);
    }

    public function updateContact(Contact $contact, array $data)
    {
        if (isset($data['image'])) {

            if ($contact->image_path) {
                Storage::disk('public')->delete($contact->image_path);
            }

            $path = $data['image']->store('contacts', 'public');
            $data['image_path'] = $path;
        }
        Log::debug($data);
        $contact->update($data);
        return $contact;
    }

    public function deleteContact(Contact $contact)
    {
        return $contact->delete();
    }
}
