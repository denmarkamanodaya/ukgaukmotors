<?php

namespace Quantum\base\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Quantum\base\Http\Requests\Admin\UpdateCommerceSettingsRequest;
use Quantum\base\Services\CommerceSettingsService;

class CommerceSettings extends Controller
{

    /**
     * @var CommerceSettingsService
     */
    private $commerceSettingsService;

    public function __construct(CommerceSettingsService $commerceSettingsService)
    {
        $this->commerceSettingsService = $commerceSettingsService;
    }

    public function index()
    {
        return view('base::admin.Commerce.Settings.index');
    }

    public function update(UpdateCommerceSettingsRequest $request)
    {
        $this->commerceSettingsService->update($request);
        return view('base::admin.Commerce.Settings.index');
    }
}