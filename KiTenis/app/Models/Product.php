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
        'stock',
        'image',
        'category',
        'active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'active' => 'boolean',
    ];

    /**
     * Atributo virtual para retornar a URL correta da imagem.
     * Use no Blade: $product->image_url
     */
    public function getImageUrlAttribute(): string
    {
        if (blank($this->image)) {
            return asset('images/placeholder-product.png');
        }

        // Se por algum motivo você salvou uma URL completa no banco
        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        // Normaliza (evita salvar "storage/..." e duplicar)
        $path = preg_replace('#^storage/#', '', $this->image);

        // Padrão do Filament: disk "public" -> /storage/...
        return Storage::disk('public')->url($path);
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
}
