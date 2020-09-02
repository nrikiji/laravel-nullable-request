<?php

namespace Nrikiji\NullableRequest;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Validator;

class NullableRequestProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app['validator']->resolver(function ($translator, $data, $rules, $messages, $customAttributes) {
            foreach ($rules as $k => $v) {
                if (!in_array('nullable', explode('|', $v))) {
                    continue;
                }
                if (array_key_exists($k, $data)) {
                    continue;
                }
                $data[$k] = null;
            }
            return new Validator($translator, $data, $rules, $messages, $customAttributes);
        });
    }
}
