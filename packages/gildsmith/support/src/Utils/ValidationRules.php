<?php

namespace Gildsmith\Support\Utils;

abstract class ValidationRules
{
    const string CODE = 'required|string|regex:/^[a-z0-9._]+$/';
}
