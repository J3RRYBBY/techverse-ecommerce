<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;

class CustomerMessagesController extends Controller
{
    // Contact list
    public function CustomerMessages()
    {
        $contacts = Contact::latest()->paginate(10);

        return view('admin.customerMessage.customerMessage', compact('contacts'));
    }

    // View contact
    public function messageDetails(int $id)
    {
        $contact = Contact::findOrFail($id);

        // Mark as read
        if ($contact->status === 'unread') {
            $contact->update([
                'status' => 'read',
            ]);
        }

        return view('admin.customerMessage.contactDetails', compact('contact'));
    }

    // Delete contact
    public function deleteCustomerMessage(int $id)
    {
        $contact = Contact::findOrFail($id);

        $contact->delete();

        return redirect()->route('admin#customerMessage')->with('deleteSuccess', 'Contact deleted successfully!');
    }
}
