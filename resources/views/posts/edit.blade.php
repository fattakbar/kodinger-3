@extends('layouts.dashboard', ['title' => 'Edit Konten'])

@section('dash_content')
    <div class="flex items-center py-12">
        <div class="md:w-1/2 md:mx-auto">
        	@include('posts.form', ['action' => route('post.update', $post->id), 'method' => 'PUT'])
		</div>
	</div>
@stop

