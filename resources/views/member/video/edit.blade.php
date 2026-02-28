@extends('layouts.backend.app', ['title' => 'Episode'])

@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-10">
            <form action="{{ route('member.video.update', [$course->slug, $video->id]) }}" method="POST" id="video-form">
                @csrf
                @method('PUT')
                <x-card-form title="EDIT EPISODE" :url="route('member.video.index', $course->id)" titleBtn="Update Episode">

                    {{-- Title --}}
                    <x-input title="Title" name="name" type="text" placeholder="Enter episode title" :value="$video->name" />

                    <div class="row">
                        {{-- Episode Number --}}
                        <div class="col-6">
                            <div class="form-group">
                                <label for="episode">
                                    Episode
                                    <span class="badge badge-info ml-1">Saat ini: Eps {{ $video->episode }}</span>
                                </label>
                                <input
                                    type="number"
                                    id="episode"
                                    name="episode"
                                    class="form-control @error('episode') is-invalid @enderror"
                                    placeholder="Nomor episode"
                                    value="{{ old('episode', $video->episode) }}"
                                    min="1"
                                    required>
                                @error('episode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="episode-warning" class="text-danger small mt-1" style="display:none;">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Episode ini sudah dipakai episode lain! Pilih nomor yang berbeda.
                                </div>
                            </div>

                            {{-- List episode yang sudah ada (selain episode saat ini) --}}
                            @if(count($usedEpisodes) > 0)
                            <div class="form-group">
                                <label class="text-muted small">Episode lain yang sudah ada (tidak bisa dipilih):</label>
                                <div>
                                    @foreach($usedEpisodes as $ep)
                                        <span class="badge badge-dark mr-1 mb-1" style="font-size:0.8rem;" title="Episode {{ $ep }} sudah dipakai episode lain">
                                            Ep {{ $ep }} <i class="fas fa-lock ml-1" style="font-size:0.65rem;"></i>
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            @else
                            <div class="form-group">
                                <small class="text-muted"><i class="fas fa-info-circle mr-1"></i>Tidak ada episode lain. Bebas pilih nomor berapa saja!</small>
                            </div>
                            @endif
                        </div>

                        {{-- Video Code --}}
                        <div class="col-6">
                            <x-input title="Video Code" name="video_code" type="text" placeholder="Enter video code" :value="$video->video_code" />
                        </div>

                        {{-- Teori Materi - CKEditor --}}
                        <div class="col-12">
                            <div class="form-group">
                                <label for="teori">Teori Materi</label>
                                <textarea id="teori" name="teori" class="form-control @error('teori') is-invalid @enderror">{{ old('teori', $video->teori) }}</textarea>
                                @error('teori')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Type --}}
                    <div class="form-group">
                        <label>Type</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="intro" value="0" @checked($video->intro == 0)>
                            <label class="form-check-label">Free</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="intro" value="1" @checked($video->intro == 1)>
                            <label class="form-check-label">Premium</label>
                        </div>
                    </div>

                </x-card-form>
            </form>
        </div>
    </div>
@endsection

@push('js')
<script>
const usedEpisodes  = @json($usedEpisodes);
const currentEpisode = {{ $video->episode }};
const episodeInput   = document.getElementById('episode');
const warning        = document.getElementById('episode-warning');
const submitBtn      = document.querySelector('#video-form button[type="submit"]');

function checkEpisode() {
    const val = parseInt(episodeInput.value, 10);
    // Only block if val matches another episode (not the current one — already excluded from usedEpisodes)
    if (!isNaN(val) && usedEpisodes.includes(val)) {
        warning.style.display = 'block';
        episodeInput.classList.add('is-invalid');
        if (submitBtn) submitBtn.disabled = true;
    } else {
        warning.style.display = 'none';
        episodeInput.classList.remove('is-invalid');
        if (submitBtn) submitBtn.disabled = false;
    }
}
episodeInput.addEventListener('input', checkEpisode);
checkEpisode();

// CKEditor 5 + Custom Upload Adapter
class UploadAdapter {
    constructor(loader) { this.loader = loader; }
    upload() {
        return this.loader.file.then(file => new Promise((resolve, reject) => {
            const xhr  = new XMLHttpRequest();
            const data = new FormData();
            data.append('upload', file);
            data.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            xhr.open('POST', '{{ route("member.ckeditor.upload") }}', true);
            xhr.responseType = 'json';
            xhr.addEventListener('load', () => {
                if (xhr.response && xhr.response.url) {
                    resolve({ default: xhr.response.url });
                } else {
                    reject(xhr.response ? (xhr.response.message || 'Upload gagal.') : 'Upload gagal.');
                }
            });
            xhr.addEventListener('error', () => reject('Upload gagal.'));
            xhr.send(data);
        }));
    }
    abort() {}
}

function UploadAdapterPlugin(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = loader => new UploadAdapter(loader);
}

ClassicEditor.create(document.querySelector('#teori'), {
    extraPlugins: [UploadAdapterPlugin],
    toolbar: {
        items: [
            'heading', '|',
            'bold', 'italic', 'underline', 'strikethrough', '|',
            'link', '|',
            'bulletedList', 'numberedList', '|',
            'outdent', 'indent', '|',
            'uploadImage', 'insertTable', 'blockQuote', 'mediaEmbed', 'codeBlock', '|',
            'undo', 'redo',
        ],
        shouldNotGroupWhenFull: true,
    },
    image: {
        toolbar: ['imageTextAlternative', '|', 'imageStyle:inline', 'imageStyle:block', 'imageStyle:side'],
    },
    table: {
        contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells', 'tableProperties', 'tableCellProperties'],
    },
    mediaEmbed: { previewsInData: true },
}).then(editor => {
    document.getElementById('video-form').addEventListener('submit', () => {
        document.getElementById('teori').value = editor.getData();
    });
}).catch(err => console.error('CKEditor error:', err));
</script>
@endpush
