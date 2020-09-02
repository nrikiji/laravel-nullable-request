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
                $rules2 = $v;
                if (is_string($rules2)) {
                    $rules2 = explode('|', $rules2);
                }
                if (!in_array('nullable', $rules2)) {
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
