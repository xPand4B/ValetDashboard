<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="author" content="Eric Heinzl"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>

    <link href="{{ url('/css/tailwind.min.css') }}" rel="stylesheet"/>
    <link href="{{ url('/css/app.css') }}" rel="stylesheet"/>

    <link rel="icon" type="image/png" sizes="16x16" href="{{ url('/img/favicon/favicon-16x16.png') }}"/>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ url('/img/favicon/favicon-32x32.png') }}"/>
    <link rel="shortcut icon" type="image/x-icon" href="{{ url('/img/favicon/favicon.ico') }}"/>

    <title>Valet Dashboard</title>
</head>
<body class="bg-discord-primary min-h-screen antialiased">

    {{-- Layout --}}
    <div class="flex flex-row">
        {{-- Sidebar --}}
        <div class="bg-discord-tertiary min-h-screen w-20 shadow-lg fixed">
            {{-- FABs --}}
            <div class="flex flex-col items-center mt-5 space-y-5">
                @include('components.fabs')
            </div>
        </div>

        {{-- Content --}}
        <div class="w-full ml-20">
            {{-- Topbar --}}
            @include('components.topbar')

            {{-- Sites --}}
            <div class="bg-discord-primary font-sans mx-auto">
                <div class="flex flex-wrap justify-evenly mt-10">
                    <?php foreach($valet['paths'] as $path => $sites): ?>
                        @include('components.path-card')
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>


