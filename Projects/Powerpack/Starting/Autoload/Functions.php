<?php

define('CONNECT_FILE', STORAGE_DIR . 'connect.json');

function flag(String $flag = NULL)
{
    return FILES_URL . 'flags/' . $flag . '.png'; 
}

function photo(String $photo = NULL)
{
    $path = FILES_DIR . ($p = 'photos/' . $photo);
    
    if( ! is_file($path) )
    {
        return THEMES_URL . 'img/user.png';
    }

    return FILES_URL . $p;
}

function active(String $active = NULL, String $return = 'active')
{
    if( $active === CURRENT_CFURI || $active === CURRENT_CONTROLLER )
    {
        return $return;
    }

    return NULL;
}

function status(String $output = NULL, String $type = 'info')
{
    if( $output === 'error' )
    {
        $output = Lang::error('error');
    }

    if( $output === 'success' )
    {
        $output = Lang::success('success');
    }

    if( ! empty($output) )
    {
        
        Redirect::delete(['error', 'success']);

        echo '<div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-'.$type.' alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <i class="fa fa-'.($type === 'info' ? 'check' : 'times').'-circle"></i> '.$output.'
                        </div>
                    </div>
                </div>';
    }
}