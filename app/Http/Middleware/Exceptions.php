<?php

namespace App\Http\Middleware;

use App\Builder\JsonResponseBuilder;
use App\Service\Mail\MailException;
use Closure;
use Exception;
use Illuminate\Validation\ValidationException;
use PDOException;
use Throwable;

class Exceptions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (empty($response->exception)) {
            return $response;
        }

        /** @var \Exception $exception */
        $exception = $response->exception;

        $responseBuilder = new JsonResponseBuilder();

        if ($exception instanceof MailException) {
            return $responseBuilder->build(
                true,
                'Houve um erro ao enviar o e-mail.',
                $exception->getMessage(),
                null,
                $exception,
                500
            );
        } elseif ($exception instanceof PDOException) {
            return $responseBuilder->build(
                true,
                'Houve um erro ao salvar a mensagem.',
                $exception->getMessage(),
                null,
                $exception,
                500
            );
        } elseif ($exception instanceof ValidationException) {
            /** @var ValidationException $exception */

            $userMessage = '';
            foreach ($exception->errors() as $item => $errors) {
                foreach ($errors as $error) {
                    $userMessage .= "{$error}\n";
                }
            }
            $userMessage = substr($userMessage, 0, -1);

            return $responseBuilder->build(
                true,
                $userMessage,
                $exception->getMessage(),
                null,
                $exception,
                400
            );
        } elseif ($exception instanceof Exception || $exception instanceof Throwable) {
            return $responseBuilder->build(
                true,
                'Houve um erro no sistema, tente novamente, caso o erro persista entre em contato com o administrador do sistema.',
                $exception->getMessage(),
                null,
                $exception,
                500
            );
        }

        return $response;
    }
}
