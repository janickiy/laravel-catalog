<?php

namespace App\Support;

use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class Form
{
    /**
     * Opens an HTML form with CSRF and a spoofed HTTP method.
     *
     * @param array $options
     * @return HtmlString
     */
    public static function open(array $options = []): HtmlString
    {
        $method = strtoupper((string) ($options['method'] ?? 'POST'));
        $formMethod = in_array($method, ['GET', 'POST'], true) ? $method : 'POST';
        $attributes = [
            'method' => $formMethod,
            'action' => $options['url'] ?? $options['route'] ?? '#',
            'enctype' => ! empty($options['files']) ? 'multipart/form-data' : null,
        ];

        foreach ($options as $key => $value) {
            if (in_array($key, ['method', 'url', 'route', 'files'], true)) {
                continue;
            }

            $attributes[$key] = $value;
        }

        $html = '<form'.Html::attributes($attributes).'>';

        if ($formMethod !== 'GET') {
            $html .= csrf_field();
        }

        if (! in_array($method, ['GET', 'POST'], true)) {
            $html .= method_field($method);
        }

        return new HtmlString($html);
    }

    /**
     * Closes an HTML form.
     */
    public static function close(): HtmlString
    {
        return new HtmlString('</form>');
    }

    /**
     * Builds an HTML label for a form field.
     *
     * @param string $for
     * @param string $value
     * @param array $attributes
     * @return HtmlString
     */
    public static function label(string $for, string $value, array $attributes = []): HtmlString
    {
        return new HtmlString('<label'.Html::attributes(array_merge(['for' => $for], $attributes)).'>'.e($value).'</label>');
    }

    /**
     * Builds a text form field.
     *
     * @param string $name
     * @param mixed|null $value
     * @param array $attributes
     * @return HtmlString
     */
    public static function text(string $name, mixed $value = null, array $attributes = []): HtmlString
    {
        return self::input('text', $name, $value, $attributes);
    }

    /**
     * Builds a password field.
     *
     * @param string $name
     * @param array $attributes
     * @return HtmlString
     */
    public static function password(string $name, array $attributes = []): HtmlString
    {
        return self::input('password', $name, null, $attributes);
    }

    /**
     * Builds a hidden form field.
     *
     * @param string $name
     * @param mixed|null $value
     * @param array $attributes
     * @return HtmlString
     */
    public static function hidden(string $name, mixed $value = null, array $attributes = []): HtmlString
    {
        return self::input('hidden', $name, $value, $attributes);
    }

    /**
     * Builds a file upload field.
     *
     * @param string $name
     * @param array $attributes
     * @return HtmlString
     */
    public static function file(string $name, array $attributes = []): HtmlString
    {
        return self::input('file', $name, null, $attributes);
    }

    /**
     * Builds a checkbox field.
     *
     * @param string $name
     * @param mixed $value
     * @param mixed $checked
     * @param array $attributes
     * @return HtmlString
     */
    public static function checkbox(string $name, mixed $value = 1, mixed $checked = false, array $attributes = []): HtmlString
    {
        return self::checkable('checkbox', $name, $value ?? 1, (bool) $checked, $attributes);
    }

    /**
     * Builds a radio field.
     *
     * @param string $name
     * @param mixed|null $value
     * @param mixed $checked
     * @param array $attributes
     * @return HtmlString
     */
    public static function radio(string $name, mixed $value = null, mixed $checked = false, array $attributes = []): HtmlString
    {
        return self::checkable('radio', $name, $value, (bool) $checked, $attributes);
    }

    /**
     * Builds a textarea field.
     *
     * @param string $name
     * @param mixed|null $value
     * @param array $attributes
     * @return HtmlString
     */
    public static function textarea(string $name, mixed $value = null, array $attributes = []): HtmlString
    {
        $attributes = array_merge(['name' => $name, 'id' => $attributes['id'] ?? Str::slug($name, '_')], $attributes);

        return new HtmlString('<textarea'.Html::attributes($attributes).'>'.e((string) $value).'</textarea>');
    }

    /**
     * Builds a select field with option entries.
     *
     * @param string $name
     * @param iterable $list
     * @param mixed|null $selected
     * @param array $attributes
     * @return HtmlString
     */
    public static function select(string $name, iterable $list = [], mixed $selected = null, array $attributes = []): HtmlString
    {
        $placeholder = $attributes['placeholder'] ?? null;
        unset($attributes['placeholder']);

        $attributes = array_merge(['name' => $name, 'id' => $attributes['id'] ?? Str::slug($name, '_')], $attributes);
        $html = '<select'.Html::attributes($attributes).'>';

        if ($placeholder !== null) {
            $html .= '<option value="">'.e((string) $placeholder).'</option>';
        }

        foreach ($list as $value => $label) {
            $optionAttributes = ['value' => $value];
            if ((string) $value === (string) $selected) {
                $optionAttributes['selected'] = true;
            }

            $html .= '<option'.Html::attributes($optionAttributes).'>'.e((string) $label).'</option>';
        }

        return new HtmlString($html.'</select>');
    }


    /**
     * Builds a form submit button.
     *
     * @param string $value
     * @param array $attributes
     * @return HtmlString
     */
    public static function submit(string $value, array $attributes = []): HtmlString
    {
        return self::input('submit', '_submit', $value, $attributes);
    }


    /**
     * Builds a base input of the given type.
     *
     * @param string $type
     * @param string $name
     * @param mixed|null $value
     * @param array $attributes
     * @return HtmlString
     */
    private static function input(string $type, string $name, mixed $value = null, array $attributes = []): HtmlString
    {
        $attributes = array_merge([
            'type' => $type,
            'name' => $name,
            'id' => $attributes['id'] ?? Str::slug($name, '_'),
            'value' => $value,
        ], $attributes);

        if ($type === 'file' || $type === 'password') {
            unset($attributes['value']);
        }

        return new HtmlString('<input'.Html::attributes($attributes).'>');
    }

    /**
     * Builds a checkbox or radio input.
     *
     * @param string $type
     * @param string $name
     * @param mixed $value
     * @param bool $checked
     * @param array $attributes
     * @return HtmlString
     */
    private static function checkable(string $type, string $name, mixed $value, bool $checked, array $attributes = []): HtmlString
    {
        $attributes = array_merge([
            'type' => $type,
            'name' => $name,
            'id' => $attributes['id'] ?? Str::slug($name.'_'.(string) $value, '_'),
            'value' => $value,
            'checked' => $checked,
        ], $attributes);

        return new HtmlString('<input'.Html::attributes($attributes).'>');
    }
}
