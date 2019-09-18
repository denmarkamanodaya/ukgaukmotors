<?php

function inputError($errors, $input)
{
    if($errors->has($input))
    {
        $errorHtml = "<script>formErrors.push('$input');</script>
                        <span class='help-block validation-error-label' for='$input'>".$errors->first($input)."</span>";
        return $errorHtml;
    }
    return false;
}