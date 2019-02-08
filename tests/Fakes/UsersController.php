<?php

namespace ArgentCrusade\CrudKit\Tests\Fakes;

use ArgentCrusade\CrudKit\Http\Controllers\CrudController;
use Illuminate\Http\Request;

class UsersController extends CrudController
{
    public function resource()
    {
        return new UsersResource();
    }

    public function transformer()
    {
        return new UserTransformer();
    }

    public function index(Request $request)
    {
        $items = $this->crudManager()->list($request);

        return response()->json(
            fractal($items->paginate(), $this->transformer())
        );
    }

    public function create(StoreUserRequest $request)
    {
    }
}
