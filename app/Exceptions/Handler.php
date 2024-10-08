<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof ValidationException && $request->wantsJson()) {
            return response()->json([
                'message' => "Une erreur s'est produite.",
                'errors' => $e->validator->getMessageBag()
            ], 422);
        }

        if ($e instanceof AuthorizationException) {
            return response()->json([
                'message' => "Vous n'avez pas le droit d'effectuer cette opération."
            ], 403);
        }

        if ($e instanceof NotFoundHttpException) {
            return response()->json([
                'message' => "Cet URL n'existe pas."
            ], 404);
        }

        return parent::render($request, $e);
    }
}
