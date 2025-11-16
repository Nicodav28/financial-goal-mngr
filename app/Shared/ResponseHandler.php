<?php

namespace App\Shared;

// use App\Facades\TraceCodeMaker;
use App\Resources\ApiResponseResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ResponseHandler
{
    /**
     * Succesfull base response
     *
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     * @return ApiResponseResource
     */
    public static function response(
        int $httpCode,
        ?string $service = 'API',
        ?string $resultMessage = null,
        ?string $resultCode = null,
        mixed $data = null,
        ?Request $request = null,
        ?array $metadata = null
    ): ApiResponseResource {
        // $originCaller = self::resolveCaller();

        // $traceCode = TraceCodeMaker::fetchOrCreateTraceCode(
        //     $service,
        //     $httpCode,
        //     $originCaller['method'],
        //     $originCaller['class'],
        //     $resultMessage
        // );

        $resultMessage ??= $httpCode < 299 ? 'Successful Response.' : 'An Error Occurred.';
        $resultCode ??= $httpCode < 299 ? 'SUCCESS' : 'ERROR';

        // if (is_array($traceCode) && $traceCode['error']) {
        //     //TODO: Must audit error
        // }

        $response = new ApiResponseResource((object) [
            'resultCode'    => $resultCode,
            'resultMessage' => $resultMessage,
            'traceCode'     => null,
            'result'        => $data,
            'httpCode'      => $httpCode
        ]);

        if ($request && self::shouldAudit($httpCode, $service)) {
            self::auditResponse($request, $response, $metadata);
        }

        self::logResponse($httpCode, $service, $resultMessage, $resultCode, $request, $metadata);

        return $response;
    }

    /**
     * Respuesta de error con logging detallado
     */
    public static function errorResponse(
        int $httpCode,
        string $message,
        ?\Throwable $exception = null,
        ?Request $request = null,
        ?array $metadata = null
    ): ApiResponseResource {
        $originCaller = self::resolveCaller();

        // $traceCode = TraceCodeMaker::fetchOrCreateTraceCode(
        //     'API',
        //     $httpCode,
        //     $originCaller['method'],
        //     $originCaller['class'],
        //     $message
        // );

        $response = new ApiResponseResource((object) [
            'resultCode'    => 'ERROR',
            'resultMessage' => $message,
            'traceCode'     => null,
            'result'        => null,
            'httpCode'      => $httpCode
        ]);

        // Log del error
        // self::logError($httpCode, $message, $exception, $request, $metadata);

        // Registrar error en auditoría
        // if ($request && $exception) {
        //     self::auditError($exception, $request, $metadata);
        // }

        return $response;
    }

    /**
     * Respuesta de éxito con logging de negocio
     */
    public static function successResponse(
        int $httpCode,
        string $message,
        mixed $data = null,
        ?Request $request = null,
        ?array $metadata = null
    ): ApiResponseResource {
        $originCaller = self::resolveCaller();

        // $traceCode = TraceCodeMaker::fetchOrCreateTraceCode(
        //     'API',
        //     $httpCode,
        //     $originCaller['method'],
        //     $originCaller['class'],
        //     $message
        // );

        $response = new ApiResponseResource((object) [
            'resultCode'    => 'SUCCESS',
            'resultMessage' => $message,
            'traceCode'     => null,
            'result'        => $data,
            'httpCode'      => $httpCode
        ]);

        // Log de éxito
        // self::logSuccess($httpCode, $message, $request, $metadata);

        return $response;
    }

    /**
     * Determina si se debe auditar la respuesta
     */
    private static function shouldAudit(int $httpCode, ?string $service): bool
    {
        // Auditar errores y respuestas importantes
        return $httpCode >= 400 || $httpCode < 200 || $service === 'API';
    }

    /**
     * Registra la respuesta en auditoría
     */
    private static function auditResponse(Request $request, ApiResponseResource $response, ?array $metadata = null): void
    {
        try {
            $auditService = self::getAuditService();
            if (!$auditService) return;

            $responseData = $response->resolve();
            $httpCode = $responseData['httpCode'] ?? 200;

            // $auditService->log(
            //     action: $httpCode < 400 ? AuditAction::API_RESPONSE : AuditAction::ERROR,
            //     subjectType: SubjectType::API_ENDPOINT,
            //     subjectId: $request->method() . ':' . $request->path(),
            //     request: $request,
            //     metadata: array_merge($metadata ?? [], [
            //         'response_code' => $httpCode,
            //         'trace_code' => $responseData['traceCode'] ?? null,
            //         'result_code' => $responseData['resultCode'] ?? null,
            //     ])
            // );
        } catch (\Throwable $e) {
            // No fallar si la auditoría falla
            Log::error('Failed to audit response', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Registra error en auditoría
     */
    private static function auditError(\Throwable $exception, Request $request, ?array $metadata = null): void
    {
        try {
            $auditService = self::getAuditService();
            if (!$auditService) return;

            $auditService->logError(
                exception: $exception,
                request: $request,
                metadata: $metadata
            );
        } catch (\Throwable $e) {
            // No fallar si la auditoría falla
            Log::error('Failed to audit error', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Log de respuesta
     */
    private static function logResponse(
        int $httpCode,
        ?string $service,
        ?string $resultMessage,
        ?string $resultCode,
        ?Request $request,
        ?array $metadata = null
    ): void {
        try {
            $loggingService = self::getLoggingService();
            if (!$loggingService) return;

            $level = $httpCode >= 400 ? 'error' : 'info';
            $message = "Response: {$service} - {$resultCode} - {$resultMessage}";

            $loggingService->log($level, $message, array_merge($metadata ?? [], [
                'http_code' => $httpCode,
                'service' => $service,
                'result_code' => $resultCode,
            ]), $request);
        } catch (\Throwable $e) {
            // No fallar si el logging falla
            Log::error('Failed to log response', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Log de error
     */
    private static function logError(
        int $httpCode,
        string $message,
        ?\Throwable $exception = null,
        ?Request $request = null,
        ?array $metadata = null
    ): void {
        try {
            $loggingService = self::getLoggingService();
            if (!$loggingService) return;

            $loggingService->error($message, array_merge($metadata ?? [], [
                'http_code' => $httpCode,
            ]), $request, $exception);
        } catch (\Throwable $e) {
            // No fallar si el logging falla
            Log::error('Failed to log error', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Log de éxito
     */
    private static function logSuccess(
        int $httpCode,
        string $message,
        ?Request $request = null,
        ?array $metadata = null
    ): void {
        try {
            $loggingService = self::getLoggingService();
            if (!$loggingService) return;

            $loggingService->info($message, array_merge($metadata ?? [], [
                'http_code' => $httpCode,
            ]), $request);
        } catch (\Throwable $e) {
            // No fallar si el logging falla
            Log::error('Failed to log success', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Obtiene el servicio de auditoría (lazy loading)
     */
    private static function getAuditService(): ?AuditService
    {
        if (self::$auditService === null) {
            try {
                self::$auditService = app(AuditService::class);
            } catch (\Throwable $e) {
                return null;
            }
        }

        return self::$auditService;
    }

    /**
     * Obtiene el servicio de logging (lazy loading)
     */
    private static function getLoggingService(): ?EnhancedLoggingService
    {
        if (self::$loggingService === null) {
            try {
                self::$loggingService = app(EnhancedLoggingService::class);
            } catch (\Throwable $e) {
                return null;
            }
        }

        return self::$loggingService;
    }

    private static function resolveCaller(): array
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
        $caller = $trace[2] ?? null;

        return [
            'class' => $caller['class'] ?? 'UnknownClass',
            'method' => $caller['function'] ?? 'unknownMethod',
        ];
    }
}
