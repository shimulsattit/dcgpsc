@extends('layouts.app')

@section('title', 'Order Placed Successfully')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 text-center">
            <div class="card border-0 shadow-sm p-5">
                <div class="mb-4">
                    <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex p-4">
                        <i class="bi bi-check-circle-fill text-success fs-1"></i>
                    </div>
                </div>
                <h1 class="fw-bold mb-3">Thank You!</h1>
                <p class="text-muted fs-5 mb-5">Your order for <strong>{{ $order->product->title }}</strong> has been placed successfully.</p>
                
                <div class="bg-light p-4 rounded-3 text-start mb-5">
                    <h6 class="fw-bold border-bottom pb-2 mb-3">Order Details:</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Order ID:</span>
                        <span class="fw-bold">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Amount:</span>
                        <span class="fw-bold">৳{{ number_format($order->amount, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Payment:</span>
                        <span class="fw-bold">{{ strtoupper($order->payment_method) }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Status:</span>
                        <span class="badge bg-warning text-dark">Awaiting Verification</span>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('home') }}" class="btn btn-primary btn-lg">Back to Home</a>
                    <p class="text-muted small mt-3">We will contact you via phone ({{ $order->customer_phone }}) once your payment is verified.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
