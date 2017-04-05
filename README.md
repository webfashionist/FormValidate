# FormValidate
Basic form validation in PHP


## Usage

1. Include the class from `src/classes/Form.class.php` wherever you need the PHP form validator.

2. Create the rules array for your existing form:


```php
$rules = [];
$rules["input_name"] = [
    "required" => true,
    "minlength" => 3,
    "maxlength" => 100,
    "alphabetical" => true,
    "label" => "name",
    ];
```

Existing rule keys are:

- `required` *boolean* - If set to true, the field must not be empty when submitting the form
- `empty` *boolean* - If set to true, the field must be empty when submitting the form
- `minlength` *integer* - Sets a minimal length for the field value
- `maxlength` *integer* - Sets a maximal length for the field value
- `alphabetical` *boolean* - If set to true, the field must only include alpabetical letters (a-z or A-Z), have a minimal length of 2 and may contain spaces
- `number` *boolean* - If set to true, the field value must be numeric
- `email` *boolean* - If set to true, the field value must match an email address. `FILTER_VALIDATE_EMAIL` is used for validation
- `phone` *boolean* - If set to true, the field value must match a phone number with a length between 3 to 18 characters. The characters can include a `+` in the beginning `-`, `space` or parantheses (`(` and `)`).
- `label` *string* - Sets the label contained in the error message


3. Initialize the form validator with the `$_POST` data and the created rules:

```php
use webfashion\Form;
$Form = new Form($_POST, $rules);
```

4. `$Form->validate()` returns `true` if the validation has been successfully done. If errors occur during the validation, `$Form->getErrors()` will return an `array` with at least one entry.