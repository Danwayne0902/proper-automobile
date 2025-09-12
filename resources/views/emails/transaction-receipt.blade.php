@extends('emails.layout')

@section('title', 'Transaction Receipt - Proper Automobile')

@section('content')
    <h2>Payment Receipt</h2>

    <p>Dear {{ $user->name }},</p>

    <p>Thank you for your payment! This email serves as your official receipt for transaction <strong>{{ $transaction->transaction_number }}</strong>.</p>

    <div class="details-box">
        <h3>Transaction Details</h3>
        <table class="details-table">
            <tr>
                <th>Transaction Number:</th>
                <td><strong>{{ $transaction->transaction_number }}</strong></td>
            </tr>
            <tr>
                <th>Amount:</th>
                <td class="price">${{ number_format($transaction->amount, 2) }}</td>
            </tr>
            <tr>
                <th>Payment Method:</th>
                <td style="text-transform: capitalize;">{{ str_replace('_', ' ', $transaction->payment_method) }}</td>
            </tr>
            <tr>
                <th>Transaction Type:</th>
                <td style="text-transform: capitalize;">{{ str_replace('_', ' ', $transaction->type) }}</td>
            </tr>
            <tr>
                <th>Status:</th>
                <td>
                    <span class="status-badge status-{{ $transaction->status }}">
                        {{ ucfirst($transaction->status) }}
                    </span>
                </td>
            </tr>
            <tr>
                <th>Date:</th>
                <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('l, F j, Y \a\t g:i A') }}</td>
            </tr>
            @if($transaction->processed_at)
            <tr>
                <th>Processed:</th>
                <td>{{ \Carbon\Carbon::parse($transaction->processed_at)->format('l, F j, Y \a\t g:i A') }}</td>
            </tr>
            @endif
        </table>
    </div>

    @if($automobile)
    <div class="automobile-card">
        <h3>Vehicle Information</h3>
        <p><strong>{{ $automobile->year }} {{ $automobile->make }} {{ $automobile->model }}</strong></p>
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
                <th>Vehicle Status:</th>
                <td style="text-transform: capitalize;">{{ $automobile->status }}</td>
            </tr>
        </table>
    </div>
    @endif

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ url('/transactions/' . $transaction->id) }}" class="button">
            View Transaction Details
        </a>
    </div>

    @if($transaction->notes)
    <div class="details-box">
        <h3>Transaction Notes</h3>
        <p>{{ $transaction->notes }}</p>
    </div>
    @endif

    <div class="details-box">
        <h3>Important Information</h3>
        <ul>
            <li>Please keep this receipt for your records</li>
            <li>This receipt serves as proof of payment</li>
            @if($transaction->status === 'completed')
            <li>Your payment has been processed successfully</li>
            @elseif($transaction->status === 'pending')
            <li>Your payment is being processed and will be confirmed shortly</li>
            @elseif($transaction->status === 'processing')
            <li>Your payment is currently being processed</li>
            @endif
            <li>For any questions, please contact us using the information below</li>
        </ul>
    </div>

    @if($transaction->type === 'purchase')
    <p>Congratulations on your new vehicle purchase! Our team will contact you shortly to arrange for vehicle pickup or delivery.</p>
    @elseif($transaction->type === 'deposit')
    <p>Thank you for securing this vehicle with your deposit. We'll be in touch to finalize the purchase process.</p>
    @endif

    <p>Thank you for choosing Proper Automobile!</p>

    <p>Best regards,<br>
    <strong>The Proper Automobile Team</strong></p>
@endsection
