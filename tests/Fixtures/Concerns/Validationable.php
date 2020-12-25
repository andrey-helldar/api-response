<?php

namespace Tests\Fixtures\Concerns;

use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Tests\Fixtures\Entities\Response;

/** @mixin \Tests\Fixtures\Concerns\Responsable */
trait Validationable
{
    /**
     * @param  array  $data
     *
     * @throws \Illuminate\Validation\ValidationException
     *
     * @return \Tests\Fixtures\Entities\Response
     */
    protected function validationResponse(array $data): Response
    {
        return $this->response(
            $this->validateException($data)
        );
    }

    /**
     * @param  array  $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validation(array $data): ValidatorContract
    {
        return Validator::make($data, [
            'foo' => ['required'],
            'bar' => ['integer'],
            'baz' => ['sometimes', 'url'],
        ]);
    }

    /**
     * @param  array  $data
     *
     * @throws \Illuminate\Validation\ValidationException
     *
     * @return array|\Illuminate\Validation\ValidationException
     */
    protected function validateException(array $data)
    {
        $validator = $this->validation($data);

        return $validator->fails()
            ? new ValidationException($validator)
            : $validator->validated();
    }
}
