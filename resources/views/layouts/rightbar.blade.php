@inject('tag', 'Services\TagService')

<div class="off-canvasify rightbar" id="rightbar">
    <div class="mx-auto p-4 sm:p-0 sm:mx-0">
        <h4 class="font-bold mb-3 text-indigo-600">Topik Populer</h4>
        <div class="bg-white rounded border-2 border-gray-200">
            @foreach($tag->popular(5) as $tag)
            <a class="flex items-center hover:bg-gray-100 px-5 py-4 border-b border-gray-100" href="@route('search', ['tag' => $tag->name])">
                <div class="text-sm text-gray-600 font-semibold truncate">{{ '#' . $tag->name }}</div>
            </a>
            @endforeach
        </div>

        <div class="py-6 border-t border-gray-200 text-sm">
            <div class="container mx-auto">
                <div class="-mx-2 mb-2">
                	@include('layouts.links')
                </div>
                <div class="text-gray-600 font-light">Copyright &copy; Kodinger</div>
            </div>
        </div>
    </div>
</div>