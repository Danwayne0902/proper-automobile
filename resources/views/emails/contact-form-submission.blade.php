@extends('emails.layout')

@section('content')
    <h2 style="color: #1f2937; font-size: 24px; font-weight: 600; margin-bottom: 20px;">
        New Contact Form Submission
    </h2>

    <div style="background-color: #f8fafc; padding: 20px; border-radius: 8px; border-left: 4px solid #3b82f6; margin-bottom: 24px;">
        <p style="color: #374151; font-size: 16px; margin: 0; font-weight: 600;">
            You have received a new message through the website contact form.
        </p>
    </div>

    <!-- Contact Details -->
    <div style="margin-bottom: 24px;">
        <h3 style="color: #1f2937; font-size: 18px; font-weight: 600; margin-bottom: 12px;">Contact Information</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb; width: 120px;">
                    <strong style="color: #374151;">Name:</strong>
                </td>
                <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb; color: #6b7280;">
                    {{ $contactData['first_name'] }} {{ $contactData['last_name'] }}
                </td>
            </tr>
            <tr>
                <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;">
                    <strong style="color: #374151;">Email:</strong>
                </td>
                <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb; color: #6b7280;">
                    <a href="mailto:{{ $contactData['email'] }}" style="color: #3b82f6; text-decoration: none;">
                        {{ $contactData['email'] }}
                    </a>
                </td>
            </tr>
            @if(!empty($contactData['phone']))
            <tr>
                <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;">
                    <strong style="color: #374151;">Phone:</strong>
                </td>
                <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb; color: #6b7280;">
                    {{ $contactData['phone'] }}
                </td>
            </tr>
            @endif
            <tr>
                <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;">
                    <strong style="color: #374151;">Subject:</strong>
                </td>
                <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb; color: #6b7280;">
                    {{ ucfirst(str_replace('_', ' ', $contactData['subject'])) }}
                </td>
            </tr>
            <tr>
                <td style="padding: 8px 0;">
                    <strong style="color: #374151;">Submitted:</strong>
                </td>
                <td style="padding: 8px 0; color: #6b7280;">
                    {{ $submittedAt->format('F j, Y \a\t g:i A T') }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Message -->
    <div style="margin-bottom: 24px;">
        <h3 style="color: #1f2937; font-size: 18px; font-weight: 600; margin-bottom: 12px;">Message</h3>
        <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 16px;">
            <p style="color: #374151; font-size: 16px; line-height: 1.6; margin: 0; white-space: pre-line;">{{ $contactData['message'] }}</p>
        </div>
    </div>

    <!-- Quick Response Actions -->
    <div style="background-color: #f0f9ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 20px; margin-bottom: 24px;">
        <h3 style="color: #1e40af; font-size: 16px; font-weight: 600; margin-bottom: 12px;">Quick Actions</h3>
        <p style="color: #1e40af; font-size: 14px; margin-bottom: 16px;">
            Respond quickly to provide excellent customer service:
        </p>
        <table style="width: 100%;">
            <tr>
                <td style="padding-right: 10px;">
                    <a href="mailto:{{ $contactData['email'] }}?subject=Re: {{ ucfirst(str_replace('_', ' ', $contactData['subject'])) }}"
                       style="display: inline-block; background-color: #3b82f6; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 500; font-size: 14px;">
                        Reply via Email
                    </a>
                </td>
                @if(!empty($contactData['phone']))
                <td>
                    <a href="tel:{{ $contactData['phone'] }}"
                       style="display: inline-block; background-color: #10b981; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 500; font-size: 14px;">
                        Call Customer
                    </a>
                </td>
                @endif
            </tr>
        </table>
    </div>

    <!-- Response Guidelines -->
    @if($contactData['subject'] !== 'other')
    <div style="background-color: #fefce8; border: 1px solid #fde047; border-radius: 8px; padding: 16px; margin-bottom: 24px;">
        <h4 style="color: #a16207; font-size: 14px; font-weight: 600; margin-bottom: 8px;">Response Guidelines for {{ ucfirst(str_replace('_', ' ', $contactData['subject'])) }}:</h4>
        <ul style="color: #a16207; font-size: 14px; margin: 0; padding-left: 20px;">
            @switch($contactData['subject'])
                @case('test_drive')
                    <li>Check vehicle availability and schedule appointment</li>
                    <li>Confirm driver's license and insurance requirements</li>
                    <li>Prepare vehicle inspection checklist</li>
                    @break
                @case('financing')
                    <li>Review customer's credit requirements</li>
                    <li>Prepare financing options and rates</li>
                    <li>Schedule appointment with finance manager</li>
                    @break
                @case('trade_in')
                    <li>Schedule vehicle appraisal appointment</li>
                    <li>Prepare trade-in evaluation form</li>
                    <li>Review current market values</li>
                    @break
                @case('service')
                    <li>Identify service type and urgency</li>
                    <li>Check warranty status if applicable</li>
                    <li>Schedule service appointment</li>
                    @break
                @default
                    <li>Respond within 24 hours for best customer experience</li>
                    <li>Provide detailed and helpful information</li>
                    <li>Follow up to ensure customer satisfaction</li>
            @endswitch
        </ul>
    </div>
    @endif

    <div style="text-align: center; margin-top: 32px; padding-top: 24px; border-top: 1px solid #e5e7eb;">
        <p style="color: #6b7280; font-size: 14px; margin: 0;">
            This message was sent from the Proper Automobile website contact form<br>
            <a href="{{ config('app.url') }}" style="color: #3b82f6; text-decoration: none;">{{ config('app.url') }}</a>
        </p>
    </div>
@endsection
