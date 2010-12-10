<?php
if (!function_exists('array_replace_recursive'))
{
  function array_replace_recursive($array, $array1)
  {
     if (!function_exists('recurse'))
     {
        function recurse($array, $array1)
        {
          foreach ($array1 as $key => $value)
          {
            // create new key in $array, if it is empty or not an array
            if (!isset($array[$key]) || (isset($array[$key]) && !is_array($array[$key])))
            {
              $array[$key] = array();
            }

            // overwrite the value in the base array
            if (is_array($value))
            {
              $value = recurse($array[$key], $value);
            }
            $array[$key] = $value;
          }
          return $array;
        }
     }
    // handle the arguments, merge one by one
    $args = func_get_args();
    $array = $args[0];
    if (!is_array($array))
    {
      return $array;
    }
    for ($i = 1; $i < count($args); $i++)
    {
      if (is_array($args[$i]))
      {
        $array = recurse($array, $args[$i]);
      }
    }
    return $array;
  }
}

// http://ru.php.net/manual/en/function.array-replace.php
if (!function_exists('array_replace'))
{
  function array_replace( array &$array, array &$array1, $filterEmpty=false )
  {
    $args = func_get_args();
    $count = func_num_args()-1;

    for ($i = 0; $i < $count; ++$i) {
      if (is_array($args[$i])) {
        foreach ($args[$i] as $key => $val) {
            if ($filterEmpty && empty($val)) continue;
            $array[$key] = $val;
        }
      }
      else {
        trigger_error(
          __FUNCTION__ . '(): Argument #' . ($i+1) . ' is not an array',
          E_USER_WARNING
        );
        return NULL;
      }
    }

    return $array;
  }
} 