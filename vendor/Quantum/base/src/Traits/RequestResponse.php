<?php

namespace Quantum\base\Traits;

use Illuminate\Http\JsonResponse;
use Laracasts\Flash\Flash;
use Redirect;

trait RequestResponse {

    /**
     * @param array $errors
     * @return $this|JsonResponse
     */
    public function response(array $errors)
    {
        if ($this->ajax())
        {
            return new JsonResponse($errors, 422);
        }

        flash('There was a problem with your input.')->error();
        //return $this->redirector->to($this->getRedirectUrl())
        return Redirect::back()
            ->withInput($this->except($this->dontFlash))
            ->withErrors($errors, $this->errorBag);
    }

}