@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <div class="flex py-12 -mx-6 justify-center">
            <div class="w-8/12 px-6">
            	<form method="post">
	            	<div class="p-8 bg-white shadow-md rounded">
	            		<h4 class="text-lg font-bold text-gray-600 border-b-2 border-gray-200 pb-4 mb-6">Sarankan Penyuntingan</h4>

	            		<p class="mb-2 text-gray-600">Post Terkait</p>
	            		<a href="{{ route('single', $post->slug) }}" class="flex items-center bg-gray-100 hover:bg-gray-200 hover:border-gray-300 border border-gray-200 mb-8 p-3 rounded">
	            			<div class="bg-cover w-16 h-16 rounded" style="background-image: url({{ nl_array_first($post->images) }});"></div>	
	            			<div class="ml-4">
	            				<h4 class="font-semibold text-indigo-600">{{ $post->title }}</h4>
	            				<p class="text-sm text-gray-600 mt-1">{{ truncate($post->content, 60) }}</p>
	            			</div>
	            		</a>

	            		<p class="mb-2 text-gray-600">Rekomendasikan link {{ key2str($col) }}</p>
	            		<div class="bg-gray-100 border border-gray-200 rounded p-4">
	            			<div id="links">
	            				<div class="list">
			            		</div>
		            		</div>
		            		<div class="hidden">
		            			<div id="link-item" class="bg-white shadow rounded mb-4 text-sm text-blue-500 flex">
		            				<div class="hidden link-id"></div>
		            				<input tabindex="2" type="text" name="{{ $col }}[]" value="" placeholder="Contoh: https://kodinger.com/tutorial-javascript" class="url w-full py-3 px-4 rounded outline-none">
		            				<div class="link-delete uppercase font-semibold bg-red-500 text-white px-4 flex items-center cursor-pointer hover:bg-red-600 rounded-tr rounded-br">Hapus</div>
		            			</div>
		            		</div>
	            			<div id="link-add" tabindex="1" class="bg-white shadow rounded py-3 px-4 text-sm text-blue-500 text-center cursor-pointer hover:bg-indigo-600 hover:text-white">Tambah URL</div>
	            		</div>

						@button([
							'class' => 'w-full mt-8 pointer-events-none opacity-75 submit-btn'
						])
							Kirim
						@endbutton
	            	</div>
	            </form>
            </div>
        </div>
    </div>
@stop

@push('js')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/list.js/1.5.0/list.min.js"></script>
	<script>
        var ID = function () {
          return Math.random().toString(36).substr(2, 9);
        }

		var options = {
			item: 'link-item',
			valueNames: [
				'url',
				{ name: 'link-id', attr: 'data-id' }
			]
		};

		var values = [];

		var links = new List('links', options, values);

		let get_invalid = function() {
			let invalid = [];

			document.querySelectorAll('#links .list .url').forEach(function(input) {
				if(input.value.trim().length < 1 || validateUrl(input.value) == false) {
					invalid.push(input);
				}
			});

			return invalid;
		}

		let disableButtonCheck = function() {
			let invalid = get_invalid();

			let submit_btn = document.querySelector('.submit-btn');
			if(invalid.length < 1 && document.querySelector('#links .list').children.length > 0) {
				submit_btn.classList.remove('pointer-events-none');
				submit_btn.classList.remove('opacity-75');
			}else{
				submit_btn.classList.add('pointer-events-none');
				submit_btn.classList.add('opacity-75');				
			}
		}

		let validateUrl = function(value) {
			return /^(?:(?:(?:https?|ftp):)?\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:[/?#]\S*)?$/i.test(value);
		}

		let required = function(field) {
			field.focus();
			if(field.value.length == 0) 
				field.setAttribute('placeholder', 'Harap isi kolom ini dulu (Contoh: https://kodinger.com/tutorial-javascript)');
			else if(validateUrl(field.value) == false)
				field.setAttribute('placeholder', 'URL tidak valid. Mungkin berisi spasi atau karakter spesial');
		}

		let link_add = function() {
			let last = document.querySelector('#links .list');

			if(last.children.length > 0) {
				last = last.lastChild.querySelector('.url')
			}else{
				last = false;
			}

			let invalid = get_invalid();

			if(last && (last.value.trim().length == 0 || validateUrl(last.value) == false)) {
				required(last);
			}else if(invalid.length > 0) {
				required(invalid[0]);
			}else{
				links.add({
					'link-id': ID()
				});

				disableButtonCheck();

				document.querySelector('#links .list').lastChild.querySelector('.url').focus();
			}
		}

		document.getElementById('link-add').addEventListener('click', function(e) {
			link_add();

			e.preventDefault();
		});

		document.querySelector('#links').addEventListener('click', function(e) {
			if(e.target && e.target.classList.contains('link-delete')) {
				e.preventDefault();

				let id = e.target.parentNode.querySelector('.link-id').dataset.id;

				links.remove('link-id', id);

				disableButtonCheck();
			}
		});

		document.querySelector('#links').addEventListener('keydown', function(e) {
			if(e.target && e.target.classList.contains('url')) {
				let nodes = Array.prototype.slice.call(document.querySelector('#links .list').children),
					current = nodes.indexOf(e.target.parentNode);

				if(current == document.querySelector('#links .list').children.length - 1) {
					if(e.keyCode == 9) {
						e.preventDefault();
						
						link_add();
					}
				}
			}
		});

		document.querySelector('#links').addEventListener('keyup', function(e) {
			if(e.target && e.target.classList.contains('url')) {
				disableButtonCheck();
			}
		});
	</script>
@endpush