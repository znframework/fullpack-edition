<?php
//--------------------------------------------------------------------------------------------------
// Routes
//--------------------------------------------------------------------------------------------------
//
// Author     : Ozan UYKUN <ozanbote@windowslive.com> | <ozanbote@gmail.com>
// Site       : www.znframework.com
// License    : The MIT License
// Copyright  : Copyright (c) 2012-2016, ZN Framework
//
//--------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------
// Usage
//--------------------------------------------------------------------------------------------------
//
// method: Sayfada hangi yöntem veya yöntemlerin geçerli olacağı.
// change: Eskisinin ne ile değiştirileceği. Yani yeni URI bilgisi.
// uri   : Hangi controller/method için kullanılacak.
//
//--------------------------------------------------------------------------------------------------

Route::change('installation')->uri('login/installation');
Route::change('lockscreen')->uri('login/lockscreen');
Route::change('logout')->uri('login/logout');
Route::change('profile')->uri('users/profile');

