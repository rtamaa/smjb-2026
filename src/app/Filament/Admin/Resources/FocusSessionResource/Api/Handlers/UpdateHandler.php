<?php
namespace App\Filament\Admin\Resources\FocusSessionResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Admin\Resources\FocusSessionResource;
use App\Filament\Admin\Resources\FocusSessionResource\Api\Requests\UpdateFocusSessionRequest;

class UpdateHandler extends Handlers {
    public static string | null $uri = '/{id}';
    public static string | null $resource = FocusSessionResource::class;

    public static function getMethod()
    {
        return Handlers::PUT;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }


    /**
     * Update FocusSession
     *
     * @param UpdateFocusSessionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(UpdateFocusSessionRequest $request)
    {
        $id = $request->route('id');

        $model = static::getModel()::find($id);

        if (!$model) return static::sendNotFoundResponse();

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Update Resource");
    }
}