<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class RecaptchaV3 implements ValidationRule
{
    public function __construct(private string $action)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            $fail('No se pudo validar reCAPTCHA.', null);
            return;
        }

        $response = Http::timeout(3)->asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => config('services.recaptcha.secret'),
            'response' => $value,
            'remoteip' => request()->ip()
        ]);

        if (!$response->ok()) {
            $fail('No se pudo validar reCAPTCHA.', null);
            return;
        }

        $json = $response->json();

        // success
        if (!($json['success'] ?? false)) {
            $fail('No se pudo validar reCAPTCHA.', null);
            return;
        }

        // action (anti-replay cross-form)
        if (($json['action'] ?? null) !== $this->action) {
            $fail('Validación reCAPTCHA inválida.', null);
            return;
        }

        // score threshold
        $score = $json['score'] ?? 0.0;

        if ($score < config('services.recaptcha.min_score')) {
            $fail('Actividad sospechosa detectada.', null);
            return;
        }
    }
}
