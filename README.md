# Laravel Nullable Request Service
Package for filling an empty parameter with a request parameter that has a nullable validation rule.

## Installation

You can install the package via composer: 

```bash
composer require nrikiji/laravel-nullable-request
```

The package will automatically register itself.

## Usage
```php
// $request->post() => [ "text_value" => "xxxxx"]

$validated_params = $request->validate([
  'text_value' => 'required',
  'check_values' => 'nullable',
]);

/*
$validated_params => [
  "text_value" => "xxxxx",
  "check_values" => null // this package for setting the value
]
*/
```

## Consept

You should work with validated data when dealing with request parameters in Laravel.    

```php
public function action(Request $request)
{
    // This is a validated parameter
    $validated_params = $request->validated();
    
    // This includes unverified parameters (as well as unexpected ones)
    $novalidated_params = $request->post();
}
```

This is no small matter. Consider, for example, a screen with a form that spans two pages.  
If you send an unintended parameter attack to action2, the value obtained by action1 may be overwritten.  
So getting the value from the validated (or validate) method is essential.

```php
public function action1(Request $request)
{
    $novalidated_params1 = $request->post(); // [ "value1" => "xxxxx" ]
    $request->session()->put('params1', $novalidated_params1);
    ・・・
}

public function action2(Request $request)
{
    $novalidated_params2 = $request->post();
    // $novalidated_params2 is [ "value1" => "attack value", "value2" => "xxxxx" ]
    
    $novalidated_params = array_merge($novalidated_params1, $novalidated_params2);
    // $novalidated_params is [ "value1" => "attack value", "value2" => "xxxxx" ]
}

```

Next, we define the following validation rules.  
It turns out that items that are not included in the request parameters will not be included in the return value, even if they are defined as nullable in the validation rules. We developed this little package because we thought it would be very unwieldy.

```php
// $request->post() => [ "text_value" => "xxxxx"]

$validated_params = $request->validate([
  'text_value' => 'required',
  'check_values' => 'nullable',
]);

// You can get this value from the validate method
// $validated_params is [ "text_value" => "xxxxx" ]

// But the value I want is this one.
// $validated_params is [ "text_value" => "xxxxx", "check_values" => null ]

```


