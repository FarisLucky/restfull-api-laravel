<?php


namespace App\Services;


use App\Http\Resources\JSONAPICollection;
use App\Http\Resources\JSONAPIIdentifierResource;
use App\Http\Resources\JSONAPIResource;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Psy\Util\Json;
use Spatie\QueryBuilder\QueryBuilder;

class JSONAPIService
{

    /*
     * -----------
     * BASE RESOURCE OPERATION
     * -----------
     */

    /**
     * Method ini digunakan untuk mengambil semua resource
     * Method ini biasanya ditempatkan di method store dalam Controller
     *
     * @param Model $model
     * @return JSONAPICollection
     */
    public function fetchResourcesIndex(string $modelClass, string $type)
    {
        $model = QueryBuilder::for($modelClass)
            ->allowedSorts(config("jsonapispec.resources.{$type}.allowedSorts"))
            ->allowedIncludes(config("jsonapispec.resources.{$type}.allowedIncludes"))
            ->get();
        return new JSONAPICollection($model);
    }

    /**
     * Method ini digunakan untuk membuat resource baru
     * Method ini biasanya ditempatkan di method store dalam Controller
     *
     * @param string $modelClass
     * @param array $attributes
     * @return \Illuminate\Http\JsonResponse
     */
    public function createResource(string $modelClass, array $attributes, array $relationships = null)
    {
        $model = $modelClass::create($attributes);
        if ($relationships) {
            $this->handleRelationship($relationships, $model);
        }
        return (new JSONAPIResource($model))
            ->response()
            ->header('Location', route("{$model->type()}.show", [
                Str::singular($model->type()) => $model,
            ]));
    }

    /**
     * Method ini digunakan untuk mengambil satu resource
     * Method ini biasanya ditempatkan di method store dalam Controller
     *
     * @param $model
     * @param int $id
     * @param string $type
     * @return JSONAPIResource
     */
    public function fetchResource($model, int $id = 0, string $type = '')
    {
        if ($model instanceof Model) {
            return new JSONAPIResource($model);
        }
        $query = QueryBuilder::for($model::where('id', $id))
            ->allowedIncludes(config("jsonapispec.resources.{$type}.allowedIncludes"))
            ->firstOrFail();
        return new JSONAPIResource($query);
    }

    /**
     * Method ini digunakan untuk memperbarui resource
     * Method ini biasanya ditempatkan di method update dalam controller
     *
     * @param Model $model
     * @param array $attributes
     * @return JSONAPIResource
     */
    public function updateResource(Model $model, array $attributes)
    {
        $model->update($attributes);
        return new JSONAPIResource($model);
    }

    /**
     * Method ini digunakan untuk menghapys resource
     * Method ini biasanya ditempatkan di method destroy dalam controller
     *
     * @param Model $model
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function deleteResource(Model $model)
    {
        $model->delete();
        return response(null, 204);
    }

    /*
     * ---------------------
     * MODEL RELATED SERVICE
     * ---------------------
     */

    /**
     * @param $model
     * @param $relationship
     * @return JSONAPICollection|JSONAPIResource
     */
    public function fetchRelated($model, $relationship)
    {
        if ($model instanceof Model) {
            return new JSONAPIResource($model->$relationship);
        }
        return new JSONAPICollection($model->$relationship);
    }

    /*
     * --------------------------
     * MODEL RELATIONSHIP SERVICE
     * --------------------------
     */

    /**
     * @param $model
     * @param string $relationship
     * @return JSONAPIIdentifierResource|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function fetchRelationship($model, string $relationship)
    {
        if ($model instanceof Model) {
            return new JSONAPIIdentifierResource($model->$relationship);
        }
        return JSONAPIIdentifierResource::collection($model->$relationship);
    }

    /**
     * @param $model
     * @param string $relationship
     * @param int $ids
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function updateToManyRelationships($model, string $relationship, int $ids)
    {
        $foreignKey = $model->$relationship()->getForeignKeyName();
        $relatedModel = $model->$relationship()->getRelated();
        $relatedModel->newQuery()->findOrFails($ids);
        $relatedModel->newQuery()->where($foreignKey, $model->id)
            ->update([
                $foreignKey => null,
            ]);
        $relatedModel->newQuery()->where('id', $ids)
            ->update([
                $foreignKey => $model->id,
            ]);
        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param $model
     * @param string $relationship
     * @param $ids
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function updateManyToManyRelationships($model, string $relationship, $ids)
    {
        $model->$relationship()->sync($ids);
        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function updateToOneRelationship($model, string $relationship, $id)
    {
        $relatedModel = $model->$relationship()->getRelated();
        $model->$relationship()->dissociate();
        if ($id) {
            $newModel = $relatedModel->newQuery()->findOrFail($id);
            $model->$relationship()->associate($newModel);
        }
        try {
            $model->save();
        } catch (\Exception $e) {
            Log::error("Pussing",[$e]);
        }
        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function handleRelationship(array $relationships, $model): void
    {
        foreach ($relationships as $relationship => $contents) {
            $this->updateToOneRelationship($model, $relationship, $contents['data']['id']);
        }
        $model->load(array_keys($relationships));
    }
}
