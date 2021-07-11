<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="author" content="Eric Heinzl"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>

    <link href="{{ url('/css/tailwind.min.css') }}" rel="stylesheet"/>
    <link href="{{ url('/css/app.css') }}" rel="stylesheet"/>

    <link rel="icon" href="{{ url('/favicon.ico') }}" type="image/x-icon" />
    <link rel="shortcut icon" href="{{ url('/favicon.ico') }}" type="image/x-icon" />

    <title>Valet Dashboard</title>
</head>
<body class="bg-gradient-dark min-h-screen antialiased">

    <div class="text-center text-xs h-7 mt-1 align-middle bg-gradient-dark font-bold italic">
        &lt;/&gt; <?=phpversion();?>
    </div>

    <div class="font-sans mt-24 mx-auto container">
        <div class="grid grid-flow-row grid-cols-2 grid-rows-2 gap-10">

            <?php foreach($valet['paths'] as $path => $sites): ?>
                <div class="bg-gradient-gray-light rounded-lg shadow-lg">
                    <div class="w-full bg-gray-500 h-12 pt-3 text-center rounded-t-lg shadow-lg cursor-default">
                        <code class="font-mono font-bold text-gray-700">
                            {{ $path }}
                        </code>
                    </div>

                    <div class="py-6 px-4 select-none">

                        {{-- Sites found --}}
                        <?php if(count($sites) !== 0): ?>
                            <table class="w-full table-auto text-center">
                                <tbody>
                                    <?php foreach($sites as $site => $link): ?>
                                        <tr
                                            class="item border text-gray-900 font-mono font-bold border-gray-800 cursor-pointer "
                                            onclick="window.open('<?= $link ?>')"
                                        >
                                            <td class="py-3">
                                                {{ $site }}
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                        {{-- Not Sites Found --}}
                        <?php else: ?>
                            <div
                                class="flex items-center bg-gradient-blue text-white text-sm font-bold px-4 py-4 rounded-lg shadow"
                                role="alert"
                            >
                                <svg
                                    class="fill-current w-4 h-4 mr-2"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z"/>
                                </svg>
                                <p>No Sites Found.</p>
                            </div>
                        <?php endif; ?>

                    </div>

                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>


