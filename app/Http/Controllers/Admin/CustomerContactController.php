<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;

class CustomerContactController extends Controller
{
    // Contact list
    public function CustomerContact()
    {
        $contacts = Contact::latest()->paginate(10);

        return view('admin.customerContact.customerContact', compact('contacts'));
    }

    // View contact
    public function contactDetails(int $id)
    {
        $contact = Contact::findOrFail($id);

        // Mark as read
        if ($contact->status === 'unread') {
            $contact->update([
                'status' => 'read',
            ]);
        }

        return view('admin.customerContact.contactDetails', compact('contact'));
    }

    // Delete contact
    public function deleteCustomerMessage(int $id)
    {
        $contact = Contact::findOrFail($id);

        $contact->delete();

        return redirect()->route('admin#contact')->with('deleteSuccess', 'Contact deleted successfully!');
    }
}
