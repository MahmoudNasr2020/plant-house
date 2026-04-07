<div class="pcard">
    @if($product->discount > 0)
        <span class="pcard-badge">-{{ $product->discount }}%</span>
    @elseif($product->badge)
        <span class="pcard-badge" style="background:var(--gold);color:var(--gd)">{{ $product->badge }}</span>
    @endif

    @auth('customer')
        <button class="pcard-wish {{ ($product->isWishlisted ?? false) ? 'on' : '' }}"
                onclick="toggleWishlist(this, {{ $product->id }})">
            <i class="fa fa-heart"></i>
        </button>
    @endauth

    <a href="{{ route('store.product', $product->slug) }}">
        <img class="pcard-img"
             src="{{ $product->image_url ?: 'https://placehold.co/300x300/d8f3dc/1a3a2a?text=🌿' }}"
             alt="{{ $product->name }}"
             loading="lazy">
    </a>

    <div class="pcard-body">
        <div class="pcard-cat">{{ $product->category?->name }}</div>
        <a href="{{ route('store.product', $product->slug) }}">
            <div class="pcard-name">{{ $product->name }}</div>
        </a>
        <div class="pcard-brand">{{ $product->brand }}</div>

        <div class="pcard-foot">
            <div>
                <div class="pcard-price">
                    {{ number_format($product->price, 2) }}
                    <span>ر.ق</span>
                </div>
                @if($product->old_price)
                    <div class="pcard-old">{{ number_format($product->old_price, 2) }} ر.ق</div>
                @endif
            </div>

            @if($product->stock > 0)
                <button class="btn-add" onclick="addToCart({{ $product->id }})" title="أضف للسلة">
                    <i class="fa fa-plus"></i>
                </button>
            @else
                <span style="font-size:11px;color:#9aa89e;font-weight:700">نفذ</span>
            @endif
        </div>
    </div>
</div>
