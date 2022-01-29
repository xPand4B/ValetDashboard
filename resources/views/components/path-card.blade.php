<div class="flex flex-col card-discord p-5 rounded mt-10 shadow">
    <div
        id="path-<?=array_search($path, array_keys($valet['paths']), true)?>"
        class="pb-4 font-mono font-bold text-white"
    >
        {{ $path }}
    </div>

    {{-- Sites found --}}
    <?php if(count($sites) !== 0): ?>
        <div class="flex flex-col select-none">
            <?php foreach($sites as $site => $info): ?>
                <div class="flex flex-row items-center mb-2">
                    {{-- Site link --}}
                    <div
                        class="py-2 px-5 flex-auto text-center font-normal item-discord transition duration-150 rounded-l <?=$info['isShopware'] ? null : 'rounded-r' ?>"
                        onclick="window.open('<?=$info['url']?>')"
                    >
                        {{ $site }}
                    </div>

                    {{-- Admin Button --}}
                    <?php if($info['isShopware']): ?>
                        <div
                            class="p-2 flex w-16 justify-center text-discord bg-indigo-800 hover:bg-indigo-900 italic rounded-r font-semibold cursor-pointer transition duration-150"
                            onclick="window.open('<?=$info['url']?>/admin')"
                        >
                            Admin
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

    {{-- Not Sites Found --}}
    <?php else: ?>
        <div
            class="flex items-center bg-indigo-700 text-white text-sm font-bold p-2 rounded italic"
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
