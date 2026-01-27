<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    /**
     * Cadastro na newsletter
     */
    public function subscribe(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:newsletters,email'],
        ], [
            'name.required'  => 'Por favor, informe seu nome.',
            'email.required' => 'Por favor, informe seu e-mail.',
            'email.email'    => 'Informe um e-mail válido.',
            'email.unique'   => 'Este e-mail já está cadastrado.',
        ]);

        Newsletter::create([
            'name'  => $validated['name'],
            'email' => strtolower($validated['email']),
        ]);

        return back()->with(
            'success',
            'Cadastro realizado com sucesso! Você receberá nossas ofertas exclusivas.'
        );
    }
}
