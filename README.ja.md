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
  "check_values" => null // ここに値をセットするためのパッケージです
]
*/
```

## Consept

Laravelでリクエストパラメータを扱う際は検証済みのデータを扱うべきです。  

```php
public function action(Request $request)
{
    // これは検証済みのパラメータです
    $validated_params = $request->validated();
    
    // これは未検証のパラメータも含まれます（予期せぬパラメータも含まれる）
    $novalidated_params = $request->post();
}
```

これは小さな問題ではありません。例えば2ページにまたがるフォームをもつ画面について考えてみます。  
action2に意図しないパラメータ攻撃を送信するとaction1で取得した値が上書きされてしまうことがあります。  
そこでvalidated（or validate）メソッドから値を取得することは必須であると言えます。

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

次に以下のようなバリデーションルールを定義します。  
バリデーションルールにnullableとしてでも定義した項目でも、リクエストパラメータに含まれていない項目は返り値に含まれないことがわかります。これがとても扱いずらいと思ったためこの小さなパッケージを開発しました。

```php
// $request->post() => [ "text_value" => "xxxxx"]

$validated_params = $request->validate([
  'text_value' => 'required',
  'check_values' => 'nullable',
]);

// validateメソッドからはこの値が取得できます
// $validated_params is [ "text_value" => "xxxxx" ]

// しかし私が欲しい値はこの値です
// $validated_params is [ "text_value" => "xxxxx", "check_values" => null ]

```


