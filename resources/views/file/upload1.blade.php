<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Облако</title>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">

    <?php
        if($folder->root === 1)
            $folder_url = route('root_folder');
        else    
            $folder_url = route('folder_parent',$folder);    

        $urls = [ $folder_url , Auth::user()->login  ,route('logout') , Auth::user()->size ];
        $posent =  (Auth::user()->use_size /  ((Auth::user()->size)*1048576))*100;
    ?>
        <my-upload :folder="{{ $folder->id }}" :routeroot="{{ json_encode($urls) }}" :totaluser="{{ (Auth::user()->size)*1048576 }}" :usesize="{{ Auth::user()->use_size }}" 
        :procent={{ $posent }}>
        </my-upload>
    </div>
</body>
</html>
