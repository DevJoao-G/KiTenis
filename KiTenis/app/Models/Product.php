<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'discount_percentage',
        'promotion_start',
        'promotion_end',
        'stock',
        'image',
        'category',
        'active',
        'featured_in_carousel',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'promotion_start' => 'datetime',
        'promotion_end' => 'datetime',
        'active' => 'boolean',
        'featured_in_carousel' => 'boolean',
    ];

    /**
     * URL da imagem (compatível com public disk /storage).
     */
    public function getImageUrlAttribute(): string
    {
        if (blank($this->image)) {
            return asset('images/placeholder-product.png');
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        $path = preg_replace('#^storage/#', '', $this->image);

        return Storage::disk('public')->url($path);
    }

    /**
     * Promoção está ativa agora?
     */
    public function getIsPromotionActiveAttribute(): bool
    {
        $percent = (float) ($this->discount_percentage ?? 0);

        if ($percent <= 0) {
            return false;
        }

        $now = now();

        if ($this->promotion_start && $now->lt($this->promotion_start)) {
            return false;
        }

        if ($this->promotion_end && $now->gt($this->promotion_end)) {
            return false;
        }

        return true;
    }

    /**
     * Preço com desconto (quando a promoção está ativa).
     */
    public function getDiscountedPriceAttribute(): float
    {
        $price = (float) $this->price;

        if (! $this->is_promotion_active) {
            return $price;
        }

        $percent = (float) $this->discount_percentage;

        return round($price * (1 - ($percent / 100)), 2);
    }

    /**
     * Badge "-xx%".
     */
    public function getDiscountBadgeAttribute(): ?string
    {
        if (! $this->is_promotion_active) {
            return null;
        }

        $percent = (float) $this->discount_percentage;

        $formatted = fmod($percent, 1.0) === 0.0
            ? (string) (int) $percent
            : rtrim(rtrim(number_format($percent, 2, '.', ''), '0'), '.');

        return "-{$formatted}%";
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeOnPromotion($query)
    {
        $now = now();

        return $query
            ->whereNotNull('discount_percentage')
            ->where('discount_percentage', '>', 0)
            ->where(function ($q) use ($now) {
                $q->whereNull('promotion_start')
                    ->orWhere('promotion_start', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('promotion_end')
                    ->orWhere('promotion_end', '>=', $now);
            });
    }

    public function scopePromotionCarousel($query)
    {
        return $query
            ->where('featured_in_carousel', true)
            ->onPromotion();
    }
}
