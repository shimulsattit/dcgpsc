@if(isset($offers) && $offers->count() > 0)
    <div class="offers-carousel-section my-4">
        <div class="card border-0 shadow-sm overflow-hidden">
            <div id="offersCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                <div class="carousel-inner p-3">
                    @php 
                        $count = $offers->count();
                        // For a better multi-item experience, we create slides starting with each item
                        // Showing 2 items at a time as requested "2 টা পাশাপাশি থাকবে"
                    @endphp
                    
                    @php
                        // Logic to determine slides
                        $slides = collect();
                        if ($count == 3) {
                            // Just one slide with all 3
                            $slides->push([0, 1, 2]);
                        } elseif ($count < 3) {
                            // Multiple slides to create movement (1-2-1 pattern)
                            foreach($offers as $index => $o) {
                                $slides->push([
                                    $index, 
                                    ($index + 1) % $count, 
                                    ($index + 2) % $count
                                ]);
                            }
                        } else {
                            // More than 3: Rotate one by one
                            foreach($offers as $index => $o) {
                                $slides->push([
                                    $index, 
                                    ($index + 1) % $count, 
                                    ($index + 2) % $count
                                ]);
                            }
                        }
                    @endphp

                    @foreach($slides as $slideIndex => $itemIndices)
                        <div class="carousel-item {{ $slideIndex === 0 ? 'active' : '' }}">
                            <div class="row g-2 justify-content-center">
                                @foreach($itemIndices as $i)
                                    @php $item = $offers[$i]; @endphp
                                    <div class="col-4">
                                        <div class="offer-card h-100 shadow-sm border-0 rounded overflow-hidden position-relative">
                                            @if($item->link)
                                                <a href="{{ $item->link }}" target="_blank">
                                                    <img src="{{ $item->image_url }}" class="w-100 offer-grid-img" alt="{{ $item->title }}" referrerpolicy="no-referrer">
                                                </a>
                                            @else
                                                <img src="{{ $item->image_url }}" class="w-100 offer-grid-img" alt="{{ $item->title }}" referrerpolicy="no-referrer">
                                            @endif
                                            @if($item->title)
                                                <div class="offer-title-overlay"><h6 class="mb-0 x-small text-white text-center" style="font-size: 0.75rem;">{{ $item->title }}</h6></div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($count > 1)
                    <button class="carousel-control-prev custom-control" type="button" data-bs-target="#offersCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next custom-control" type="button" data-bs-target="#offersCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                @endif
            </div>
        </div>
    </div>
    
    <style>
        .offer-grid-img {
            height: 220px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .offer-card:hover .offer-grid-img {
            transform: scale(1.05);
        }
        .offer-title-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 106, 78, 0.8);
            padding: 8px;
        }
        .offers-carousel-section .card {
            border-radius: 15px;
            background-color: #ffffff;
        }
        .custom-control {
            width: 35px;
            height: 35px;
            background: var(--primary-color);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0.6;
            margin: 0 5px;
        }
        .custom-control:hover {
            background: var(--secondary-color);
            opacity: 1;
        }
    </style>
@endif
