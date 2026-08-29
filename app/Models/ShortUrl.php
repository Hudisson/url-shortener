<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model ShortUrl
 * * Representa os links encurtados no banco de dados e suas regras de negócio.
 */
class ShortUrl extends Model
{
    /**
     * Atributos que podem ser preenchidos em massa (Mass Assignment).
     *
     * * @var array<int, string>
     */
    protected $fillable = [
        'user_id',       // Id do usuário que encurtou (null se fou um visitante)
        'original_url', // A URL de destino original
        'short_code',   // O código único gerado para o encurtador (ex: 'aB3X9')
        'clicks',       // Contador de acessos ao link
        'is_active',    // Status de ativação do link (ativo/inativo)
    ];

    /**
     * Define a conversão de tipos (Casting) dos atributos.
     * * Garante que os dados sejam manipulados com os tipos nativos corretos do PHP
     * ao serem recuperados ou salvos no banco de dados.
     *
     * * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'clicks' => 'integer',
            'is_active' => 'boolean',   // Converte 0/1 do banco para true/false do PHP
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
