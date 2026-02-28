@extends('layouts.backend.app', ['title' => 'Video'])

@section('content')
    <a href="{{ route('admin.course.index') }}" class="btn btn-danger mb-3">
        <i class="fas fa-arrow-left mr-1"></i> GO BACK
    </a>
    <x-button-create title="ADD NEW EPISODE" :url="route('admin.video.create', $course->slug)" />

    <x-card title="LIST EPISODE - {{ $course->name }}">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th width="10">EPS</th>
                    <th>TITLE</th>
                    <th>TYPE</th>
                    <th>STATUS</th>
                    <th class="hidden sm:flex">TEXT</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($videos as $i => $video)
                    <tr>
                        <td>{{ $video->episode }}</td>
                        <td>{{ $video->name }}</td>
                        <td>
                            <span class="badge badge-{{ $video->intro == 1 ? 'danger' : 'primary' }}">
                                {{ $video->intro == 1 ? 'premium' : 'free' }}
                            </span>
                        </td>
                        <td>
                            @if($video->status === 'approved')
                                <span class="badge badge-success">Approved</span>
                            @elseif($video->status === 'rejected')
                                <span class="badge badge-danger">Rejected</span>
                            @else
                                <span class="badge badge-warning">Pending</span>
                            @endif
                        </td>
                        <td class="overflow-hidden overflow-ellipsis max-w-xs hidden sm:flex">
                            {{ \Illuminate\Support\Str::words($video->teori, 6, '....') }}
                        </td>
                        <td>
                            @if($video->status !== 'approved')
                            <form action="{{ route('admin.video.approve', $video->id) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-success btn-xs"><i class="fas fa-check"></i></button>
                            </form>
                            @endif
                            @if($video->status !== 'rejected')
                            <form action="{{ route('admin.video.reject', $video->id) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-secondary btn-xs"><i class="fas fa-times"></i></button>
                            </form>
                            @endif
                            <x-button-edit :url="route('admin.video.edit', [$course->slug, $video->id])" class="sm:mr-2" />
                            <x-button-delete :id="$video->id" :url="route('admin.video.destroy', $video->id)" class="sm:mr-2" />
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-card>
@endsection
