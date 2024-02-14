<?php

namespace app\controllers;

use app\base\Request;
use app\base\Response;
use app\models\ListContact;
use Exception;

final class DefaultController extends Controller
{
    /**
     * @throws Exception
     */
    public function index(): Response
    {
        /** @var ListContact[] $contacts */
        $contacts = ListContact::query()
            ->orderBy('sort_order')
            ->orderBy('id', 'desc')
            ->all();
        return $this->render(['contacts' => $contacts]);
    }

    /**
     * @throws Exception
     */
    public function add(Request $request): Response
    {
        $model = ListContact::create($request->input());

        $request->setContentType(Request::CONTENT_TYPE_JSON);

        return $this->render(['model' => $model->toArray()]);
    }

    /**
     * @throws Exception
     */
    public function sort(Request $request): void
    {
        foreach ($request->input() as $id => $sortOrder) {
            ListContact::query()->update($id, ['sort_order' => $sortOrder]);
        }

        $request->setContentType(Request::CONTENT_TYPE_JSON);

        http_response_code(204);
        $this->render();
    }

    /**
     * @throws Exception
     */
    public function delete(int $id): void
    {
        ListContact::delete($id);
        http_response_code(204);
        $this->render();
    }
}
