<?php

/**
 * Get first name
 * @param $name
 * @return mixed
 */
function string_first_name($name)
{
    $name = explode(' ', $name);
    return $name[0];
}

/**
 * Preprare roles for comma list
 * @param $roles
 * @return string
 */
function nice_roles($roles)
{
    $roleList = '';
    foreach($roles as $role)
    {
        $roleList .= roleImage($role['name']) . ',';
    }
    return rtrim($roleList, ',');
}

/**
 * Prepare users for select
 * @param $users
 * @return array
 */
function niceUserSelect($users)
{
    $select = [];
    foreach($users as $user)
    {
        $select[$user->id] = $user->profile->first_name . ' '. $user->profile->last_name .' ('. $user->username .')';
    }
    return $select;
}

/**
 * Prepare type links
 * @param $type
 * @param null $user
 * @return string
 */
function urlActionType($type, $user = null)
{
    $type = urlencode($type);
    if($user) return url('/admin/actions/'.$user->username.'/?type='.$type);
    return url('/admin/actions?type='.$type);
}

/**
 * Prepare role images
 * @param $role
 * @return string
 */
function roleImage($role)
{
    switch (ucfirst($role)) {
        case "Admin":
            return '<i class="far fa-gavel"></i> Admin';
            break;
        case "Student":
            return '<i class="far fa-mortar-board"></i> Student';
            break;
        case "Parent":
            return '<i class="far fa-users"></i> Parent';
            break;
        case "Teacher":
            return '<i class="far fa-briefcase"></i> Teacher';
            break;
    }
}

/**
 * unserialize if needed
 * @param $original
 * @return mixed
 */
function maybe_unserialize($original ) {
    if ( is_serialized( $original ) ) // don't attempt to unserialize data that wasn't serialized going in
        return @unserialize( $original );
    return $original;
}


/**
 * check if serialized
 * @param $data
 * @param bool|true $strict
 * @return bool
 */
function is_serialized($data, $strict = true ) {
    // if it isn't a string, it isn't serialized.
    if ( ! is_string( $data ) ) {
        return false;
    }
    $data = trim( $data );
    if ( 'N;' == $data ) {
        return true;
    }
    if ( strlen( $data ) < 4 ) {
        return false;
    }
    if ( ':' !== $data[1] ) {
        return false;
    }
    if ( $strict ) {
        $lastc = substr( $data, -1 );
        if ( ';' !== $lastc && '}' !== $lastc ) {
            return false;
        }
    } else {
        $semicolon = strpos( $data, ';' );
        $brace     = strpos( $data, '}' );
        // Either ; or } must exist.
        if ( false === $semicolon && false === $brace )
            return false;
        // But neither must be in the first X characters.
        if ( false !== $semicolon && $semicolon < 3 )
            return false;
        if ( false !== $brace && $brace < 4 )
            return false;
    }
    $token = $data[0];
    switch ( $token ) {
        case 's' :
            if ( $strict ) {
                if ( '"' !== substr( $data, -2, 1 ) ) {
                    return false;
                }
            } elseif ( false === strpos( $data, '"' ) ) {
                return false;
            }
        // or else fall through
        case 'a' :
        case 'O' :
            return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
        case 'b' :
        case 'i' :
        case 'd' :
            $end = $strict ? '$' : '';
            return (bool) preg_match( "/^{$token}:[0-9.E-]+;$end/", $data );
    }
    return false;
}

/**
 * Change camelcase to human readable
 * @param $string
 * @return mixed|string
 */
function camelToReadable($string)
{
    $output = preg_replace(array('/(?<=[^A-Z])([A-Z])/', '/(?<=[^0-9])([0-9])/'), ' $0', $string);
    $output = ucwords($output);
    return $output;
}

/**
 * change string to camelcase
 * @param $str
 * @param array $noStrip
 * @return mixed|string
 */
function camelCase($str, array $noStrip = [])
{
    // non-alpha and non-numeric characters become spaces
    $str = preg_replace('/[^a-z0-9' . implode("", $noStrip) . ']+/i', ' ', $str);
    $str = trim($str);
    // uppercase the first character of each word
    $str = ucwords($str);
    $str = str_replace(" ", "", $str);
    $str = lcfirst($str);

    return $str;
}

function previewUrl($page)
{
    $url = ENV('APP_URL');
    if($page->area != 'public')
    {
        $url .= '/'.$page->area;
    }
    $url .= '/'.$page->route;
    return $url;
}
