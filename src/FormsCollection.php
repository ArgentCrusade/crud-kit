<?php

namespace ArgentCrusade\CrudKit;

use ArgentCrusade\CrudKit\Exceptions\Forms\NotSupportingFormException;
use Illuminate\Http\Request;

class FormsCollection
{
    /**
     * Get the create form.
     *
     * @param Request $request
     * @param mixed   $resource = null
     *
     * @throws NotSupportingFormException
     *
     * @return mixed
     */
    public function create(Request $request, $resource = null)
    {
        throw new NotSupportingFormException('Current collection has no support for the create form.');
    }

    /**
     * Get the edit form.
     *
     * @param mixed $resource
     *
     * @throws NotSupportingFormException
     *
     * @return mixed
     */
    public function edit($resource)
    {
        throw new NotSupportingFormException('Current collection has no support for the edit form.');
    }

    /**
     * Get the delete form.
     *
     * @param mixed $resource
     *
     * @throws NotSupportingFormException
     *
     * @return mixed
     */
    public function delete($resource)
    {
        throw new NotSupportingFormException('Current collection has no support for the delete form.');
    }
}
