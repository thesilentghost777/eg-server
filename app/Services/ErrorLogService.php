<?php

namespace App\Services;

use Throwable;
use App\Models\ErrorLog;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ErrorLogService
{
    /**
     * Log une erreur dans la base de données et envoie une notification
     */
    public function logError(Throwable $exception): void
    {
        try {
            // Éviter les boucles infinies
            if ($this->shouldSkipLogging($exception)) {
                return;
            }

            $request = request();
            
            $errorData = [
                'error_type' => get_class($exception),
                'error_message' => $exception->getMessage(),
                'stack_trace' => $exception->getTraceAsString(),
                'file_path' => $exception->getFile(),
                'line_number' => $exception->getLine(),
                'request_method' => $request ? $request->method() : null,
                'request_url' => $request ? $request->fullUrl() : null,
                'request_data' => $request ? $this->sanitizeRequestData($request->all()) : null,
                'user_agent' => $request ? $request->userAgent() : null,
                'ip_address' => $request ? $request->ip() : null,
                'user_id' => auth()->id(),
                'session_id' => session()->getId(),
                'http_status_code' => $this->getStatusCode($exception),
                'error_time' => now(),
                'email_sent' => false
            ];

            // Utiliser une transaction pour s'assurer que tout se passe bien
            DB::transaction(function () use ($errorData) {
                $errorLog = ErrorLog::create($errorData);
                
                // Envoyer l'email de notification
                $this->sendErrorNotification($errorLog);
            });
            
        } catch (\Exception $e) {
            // En cas d'erreur lors de l'enregistrement, logger dans les logs Laravel
            Log::error('Erreur lors de l\'enregistrement en base de données: ' . $e->getMessage(), [
                'original_exception' => $exception->getMessage(),
                'logging_exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Déterminer si on doit ignorer le logging de cette exception
     */
    private function shouldSkipLogging(Throwable $exception): bool
    {
        // Éviter de logger les erreurs de base de données pour éviter les boucles
        if (strpos($exception->getMessage(), 'SQLSTATE') !== false) {
            return true;
        }

        // Éviter de logger certaines exceptions communes
        $skipExceptions = [
            \Illuminate\Database\QueryException::class,
            \PDOException::class,
            \Illuminate\Validation\ValidationException::class,
        ];

        foreach ($skipExceptions as $skipException) {
            if ($exception instanceof $skipException) {
                return true;
            }
        }

        return false;
    }

    /**
     * Nettoyer les données sensibles de la requête
     */
    private function sanitizeRequestData(array $data): array
    {
        $sensitiveFields = [
            'password',
            'password_confirmation',
            'current_password',
            'token',
            'api_token',
            'remember_token',
            'credit_card',
            'cvv',
            'ssn'
        ];

        foreach ($sensitiveFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = '[HIDDEN]';
            }
        }

        return $data;
    }

    /**
     * Obtenir le code de statut HTTP
     */
    private function getStatusCode(Throwable $exception): int
    {
        if ($exception instanceof HttpException) {
            return $exception->getStatusCode();
        }

        if (method_exists($exception, 'getStatusCode')) {
            return $exception->getStatusCode();
        }

        return 500;
    }

    /**
     * Envoyer une notification d'erreur par email
     */
    private function sendErrorNotification(ErrorLog $errorLog): void
    {
        try {
            // Vérifier si l'email est configuré
            if (!config('mail.from.address')) {
                Log::warning('Email non configuré pour les notifications d\'erreur');
                return;
            }

            // Éviter le spam d'emails pour la même erreur
            if ($this->shouldSkipEmailNotification($errorLog)) {
                return;
            }

            $emailContent = $this->buildEmailContent($errorLog);

            Mail::raw($emailContent, function ($message) use ($errorLog) {
                $message->to(config('app.admin_email', 'wilfrieddark2.0@gmail.com'))
                       ->subject('🚨 Erreur Application - ' . $errorLog->error_type);
            });

            $errorLog->update(['email_sent' => true]);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de l\'email: ' . $e->getMessage());
        }
    }

    /**
     * Vérifier si on doit éviter d'envoyer un email
     */
    private function shouldSkipEmailNotification(ErrorLog $errorLog): bool
    {
        // Éviter d'envoyer plus de 3 emails par heure pour le même type d'erreur
        $recentSimilarErrors = ErrorLog::where('error_type', $errorLog->error_type)
            ->where('email_sent', true)
            ->where('created_at', '>=', now()->subHour())
            ->count();

        return $recentSimilarErrors >= 3;
    }

    /**
     * Construire le contenu de l'email
     */
    private function buildEmailContent(ErrorLog $errorLog): string
    {
        $environment = config('app.env');
        $appName = config('app.name');
        
        return "🚨 Une erreur s'est produite sur {$appName} ({$environment})\n\n" .
               "📋 DÉTAILS DE L'ERREUR:\n" .
               "Type: {$errorLog->error_type}\n" .
               "Message: {$errorLog->error_message}\n" .
               "Fichier: {$errorLog->file_path}:{$errorLog->line_number}\n" .
               "Code HTTP: {$errorLog->http_status_code}\n\n" .
               "🌐 INFORMATIONS DE LA REQUÊTE:\n" .
               "URL: {$errorLog->request_url}\n" .
               "Méthode: {$errorLog->request_method}\n" .
               "Utilisateur: " . ($errorLog->user_id ? "ID {$errorLog->user_id}" : "Invité") . "\n" .
               "IP: {$errorLog->ip_address}\n" .
               "User Agent: {$errorLog->user_agent}\n" .
               "Date: {$errorLog->error_time}\n\n" .
               "📊 STACK TRACE:\n" .
               substr($errorLog->stack_trace, 0, 2000) . "\n\n" .
               "---\n" .
               "Cet email a été envoyé automatiquement par le système de monitoring d'erreurs.";
    }
}