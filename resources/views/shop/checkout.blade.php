@extends('layouts.app')

@section('title', 'Checkout - ' . $product->title)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-white border-bottom py-3">
                    <h4 class="fw-bold mb-0">Complete Your Order</h4>
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="row g-4 mb-5">
                        <div class="col-md-3">
                            <img src="{{ $product->image_url ?? 'https://via.placeholder.com/150x150' }}" class="img-fluid rounded shadow-sm" alt="{{ $product->title }}">
                        </div>
                        <div class="col-md-9">
                            <h5 class="fw-bold">{{ $product->title }}</h5>
                            <p class="text-muted small">You are enrolling in the {{ $product->type }} program.</p>
                            <h4 class="text-primary fw-bold">৳{{ number_format($product->discount_price ?? $product->price, 2) }}</h4>
                        </div>
                    </div>

                    <form action="{{ route('shop.order.store', $product) }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="customer_name" class="form-control form-control-lg bg-light" placeholder="Enter your full name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" name="customer_phone" class="form-control form-control-lg bg-light" placeholder="01XXX-XXXXXX" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email Address (Optional)</label>
                                <input type="email" name="customer_email" class="form-control form-control-lg bg-light" placeholder="yourname@domain.com">
                            </div>
                            
                            <div class="col-12 mt-4">
                                <h5 class="fw-bold mb-3">Select Payment Method</h5>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="payment-option p-3 border rounded cursor-pointer transition-all h-100" onclick="selectPayment('manual')">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="payment_method" id="pay_manual" value="manual" checked>
                                                <label class="form-check-label fw-bold cursor-pointer" for="pay_manual">
                                                    Manual (bKash/Nagad)
                                                </label>
                                            </div>
                                            <p class="text-muted small mt-2 mb-0">Send money to our number and provide transaction ID.</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="payment-option p-3 border rounded cursor-pointer transition-all h-100 opacity-50" onclick="selectPayment('automated')">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="payment_method" id="pay_auto" value="automated" disabled>
                                                <label class="form-check-label fw-bold cursor-pointer" for="pay_auto">
                                                    Automated Gateway
                                                </label>
                                            </div>
                                            <p class="text-muted small mt-2 mb-0">Coming soon! Instant access via SSLCommerz/Shurjopay.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="manual_payment_details" class="col-12 mt-4 bg-light p-4 rounded-3">
                                <h6 class="fw-bold mb-3">Payment Instructions:</h6>
                                <p class="small mb-2">1. Please SEND MONEY to the following number:</p>
                                <p class="fw-bold text-dark fs-5 mb-3"><span class="badge bg-danger">bKash/Nagad</span> 01700-000000</p>
                                <p class="small mb-3">2. Once done, enter the Transaction ID below:</p>
                                <div class="col-md-6">
                                    <input type="text" name="transaction_id" class="form-control" placeholder="Enter Transaction ID (e.g. 5G4X2M)">
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <label class="form-label fw-semibold">Order Notes (Optional)</label>
                                <textarea name="order_notes" class="form-control bg-light" rows="3" placeholder="Any special requests or info..."></textarea>
                            </div>

                            <div class="col-12 mt-5">
                                <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold shadow-sm">Confirm Order & Pay</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .cursor-pointer { cursor: pointer; }
    .payment-option:hover { border-color: #27ae60 !important; background: #f8fff9; }
    .payment-option.active { border-color: #27ae60 !important; background: #f8fff9; border-width: 2px; }
    .transition-all { transition: all 0.2s ease; }
</style>

<script>
    function selectPayment(type) {
        if(type === 'automated') return; // Temporarily disabled
        document.getElementById('pay_' + type).checked = true;
    }
</script>
@endsection
