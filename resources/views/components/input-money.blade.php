@props(['name' => '', 'value' => ''])
{{-- 
@php
    $wholeValue = floor($value);
    $decimalValue = number_format($value, 2, '.', '');
    $decimalValue = substr($decimalValue, -2);
@endphp

<div class="input-group money-group-con">
    <span class="input-group-text">₱</span>

    <input type="hidden" class="hiddenMoneyInp" name="{{ $name }}" value="{{ $value }}" />

    <input type="text" class="form-control text-end whole-part" value="{{ $wholeValue }}" placeholder="0"
        oninput="updateHiddenValue(this)" />
    <input type="text" class="input-group-text decimal-part" style="width:50px" value="{{ $decimalValue }}"
        maxlength="2" oninput="updateHiddenValue(this)" placeholder="00" />
</div> --}}
