@extends('emails.layout')

@section('title', 'Booking Confirmation - Proper Automobile')

@section('content')
    <h2>Booking Confirmation</h2>

    <p>Dear {{ $user->name }},</p>

    <p>Thank you for your booking! We're excited to confirm your upcoming appointment for the {{ $automobile->year }} {{ $automobile->make }} {{ $automobile->model }}.</p>

    <div class="details-box">
        <h3>Booking Details</h3>
        <table class="details-table">
            <tr>
                <th>Booking Type:</th>
                <td style="text-transform: capitalize;">{{ str_replace('_', ' ', $booking->type) }}</td>
            </tr>
            <tr>
                <th>Date & Time:</th>
                <td>{{ \Carbon\Carbon::parse($booking->scheduled_at)->format('l, F j, Y \a\t g:i A') }}</td>
            </tr>
            <tr>
                <th>Preferred Time:</th>
                <td>{{ $booking->preferred_time }}</td>
            </tr>
            <tr>
                <th>Status:</th>
                <td>
                    <span class="status-badge status-{{ $booking->status }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                </td>
            </tr>
            @if($booking->notes)
            <tr>
                <th>Your Notes:</th>
                <td>{{ $booking->notes }}</td>
            </tr>
            @endif
        </table>
    </div>

    <div class="automobile-card">
        <h3>Vehicle Information</h3>
        <p><strong>{{ $automobile->year }} {{ $automobile->make }} {{ $automobile->model }}</strong></p>
        <p class="price">${{ number_format($automobile->price) }}</p>
        <table class="details-table">
            <tr>
                <th>VIN:</th>
                <td>{{ $automobile->vin ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Mileage:</th>
                <td>{{ number_format($automobile->mileage) }} miles</td>
            </tr>
            <tr>
                <th>Color:</th>
                <td>{{ $automobile->color }}</td>
            </tr>
            <tr>
                <th>Transmission:</th>
                <td>{{ ucfirst($automobile->transmission) }}</td>
            </tr>
        </table>
    </div>

    <div class="details-box">
        <h3>Dealer Information</h3>
        <p><strong>{{ $dealer->name }}</strong></p>
        @if($dealer->email)
        <p>Email: <a href="mailto:{{ $dealer->email }}">{{ $dealer->email }}</a></p>
        @endif
        @if($dealer->phone)
        <p>Phone: <a href="tel:{{ $dealer->phone }}">{{ $dealer->phone }}</a></p>
        @endif
    </div>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ url('/bookings/' . $booking->id) }}" class="button">
            View Booking Details
        </a>
    </div>

    <div class="details-box">
        <h3>What to Expect</h3>
        @if($booking->type === 'test_drive')
        <ul>
            <li>Please bring a valid driver's license</li>
            <li>Arrive 15 minutes early for paperwork</li>
            <li>The test drive will last approximately 30 minutes</li>
            <li>Feel free to ask any questions about the vehicle</li>
        </ul>
        @elseif($booking->type === 'viewing')
        <ul>
            <li>Detailed vehicle inspection opportunity</li>
            <li>Discussion of financing options</li>
            <li>Trade-in evaluation if applicable</li>
            <li>No pressure environment to make your decision</li>
        </ul>
        @elseif($booking->type === 'consultation')
        <ul>
            <li>Personal consultation with our expert team</li>
            <li>Review of your needs and preferences</li>
            <li>Customized vehicle recommendations</li>
            <li>Financing options discussion</li>
        </ul>
        @endif
    </div>

    <p>If you need to cancel or reschedule your booking, please contact us at least 24 hours in advance.</p>

    <p>We look forward to seeing you soon!</p>

    <p>Best regards,<br>
    <strong>The Proper Automobile Team</strong></p>
@endsection
