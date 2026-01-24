<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    /**
     * Cadastra novo email na newsletter
     */
    public function subscribe(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:newsletters,email',
        ], [
            'name.required' => 'Por favor, informe seu nome.',
            'email.required' => 'Por favor, informe seu e-mail.',
            'email.email' => 'Por favor, informe um e-mail válido.',
            'email.unique' => 'Este e-mail já está cadastrado em nossa newsletter.',
        ]);

        Newsletter::create($validated);

        return back()->with('success', 'Cadastro realizado com sucesso! Você receberá nossas ofertas exclusivas.');
    }
}