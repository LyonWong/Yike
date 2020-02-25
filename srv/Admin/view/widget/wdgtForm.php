<?php


namespace Admin;


class wdgtForm
{
    public static function submit()
    {
        return '
        <div class="form-group">
            <button type="submit" class="btn btn-sm default">查看</button>
        </div>
        ';
    }

    public static function options($list, $selected)
    {
        $html = '';
        foreach ($list as $key => $name) {
            if ($key === $selected) {
                $html .= "<option value='$key' selected='selected'>$name</option>";
            } else {
                $html .= "<option value='$key'>$name</option>";
            }
        }
        return $html;
    }

    public static function select($prefix, $name, $value, $list)
    {
        $options = [];
        foreach ($list as $item) {
            $options[$item] = wdgtLang::dict($item);
        }
        return \view::tpl('/component/form-select', [
            'prefix' => $prefix,
            'name' => $name,
            'value' => $value,
            'options' => $options
        ])->res();
    }

    public static function input($prefix, $name, $value, $placeholder = null)
    {
        return \view::tpl('/component/form-input', [
            'prefix' => $prefix,
            'name' => $name,
            'value' => $value,
            'placeholder' => $placeholder
        ])->res();
    }
}