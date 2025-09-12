<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class ContactController extends Controller
{
    /**
     * Show the contact form
     */
    public function show()
    {
        return Inertia::render('StaticPages/Contact');
    }

    /**
     * Handle contact form submission
     */
    public function store(Request $request)
    {
        // Validate the form data
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|max:100',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|in:inquiry,test_drive,financing,trade_in,service,other',
            'message' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $contactData = $validator->validated();

        try {
            // Send email to admin/sales team
            $adminEmail = config('mail.admin_email', 'admin@properautomobile.com');
            Mail::to($adminEmail)->send(new ContactFormSubmission($contactData));

            // Send confirmation email to customer
            $this->sendCustomerConfirmation($contactData);

            // Return success response
            return redirect()->back()->with('success',
                'Thank you for your message! We\'ll get back to you within 24 hours.'
            );

        } catch (\Exception $e) {
            // Log the error
            logger()->error('Contact form submission failed: ' . $e->getMessage());

            // Return error response
            return redirect()->back()
                ->with('error', 'There was an issue sending your message. Please try again or call us directly.')
                ->withInput();
        }
    }

    /**
     * Send confirmation email to customer
     */
    private function sendCustomerConfirmation($contactData)
    {
        try {
            $customerEmail = $contactData['email'];
            $subject = 'Thank you for contacting Proper Automobile';

            $message = "Dear {$contactData['first_name']} {$contactData['last_name']},\n\n";
            $message .= "Thank you for reaching out to us regarding: " . ucfirst(str_replace('_', ' ', $contactData['subject'])) . "\n\n";
            $message .= "We have received your message and will respond within 24 hours. ";
            $message .= "If you need immediate assistance, please call us at +1 (555) 123-4567.\n\n";
            $message .= "Your message:\n";
            $message .= "\"" . $contactData['message'] . "\"\n\n";
            $message .= "Best regards,\n";
            $message .= "The Proper Automobile Team\n";
            $message .= "Email: info@properautomobile.com\n";
            $message .= "Phone: +1 (555) 123-4567\n";
            $message .= "Website: " . config('app.url');

            // Send simple text email for customer confirmation
            Mail::raw($message, function ($mail) use ($customerEmail, $subject) {
                $mail->to($customerEmail)
                     ->subject($subject)
                     ->from(config('mail.from.address'), config('mail.from.name'));
            });

        } catch (\Exception $e) {
            // Log but don't fail the main process
            logger()->warning('Customer confirmation email failed: ' . $e->getMessage());
        }
    }
}
