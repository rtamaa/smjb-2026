<?php
namespace App\Filament\Admin\Resources\FocusSessionResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Admin\Resources\FocusSessionResource;
use App\Filament\Admin\Resources\FocusSessionResource\Api\Requests\CreateFocusSessionRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = FocusSessionResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create FocusSession
     *
     * @param CreateFocusSessionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateFocusSessionRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}