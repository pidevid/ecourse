<?php

namespace App\Http\Controllers\Member;

use App\Models\Video;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Requests\VideoRequest;
use App\Http\Controllers\Controller;

class VideoController extends Controller
{
    public function index($slug)
    {
        $course = Course::where('slug', $slug)->first();
        $videos = Video::where('course_id', $course->id)->orderBy('episode')->get();

        return view('member.video.index', compact('videos', 'course'));
    }

    public function create($slug)
    {
        $course = Course::where('slug', $slug)->first();

        $usedEpisodes = Video::where('course_id', $course->id)
            ->orderBy('episode')
            ->pluck('episode')
            ->toArray();

        $nextEpisode = count($usedEpisodes) > 0 ? max($usedEpisodes) + 1 : 1;

        return view('member.video.create', compact('course', 'usedEpisodes', 'nextEpisode'));
    }

    public function store($slug, Request $request)
    {
        $course = Course::where('slug', $slug)->first();

        $request->validate([
            'name'       => 'required|string|max:255',
            'episode'    => [
                'required',
                'integer',
                'min:1',
                Rule::unique('videos', 'episode')->where('course_id', $course->id),
            ],
            'video_code' => 'required|string|max:255',
            'teori'      => 'required|string',
        ], [
            'episode.unique' => 'Episode ini sudah ada! Hapus atau edit episode yang lama terlebih dahulu.',
        ]);

        $course->videos()->create([
            'name'       => $request->name,
            'episode'    => $request->episode,
            'intro'      => $request->intro,
            'video_code' => $request->video_code,
            'teori'      => $request->teori,
            'status'     => 'pending',
        ]);

        return redirect(route('member.video.index', $course))->with('toast_success', 'Episode berhasil dibuat!');
    }

    public function edit($slug, Video $video)
    {
        $course = Course::where('slug', $slug)->first();

        $usedEpisodes = Video::where('course_id', $course->id)
            ->where('id', '!=', $video->id)
            ->orderBy('episode')
            ->pluck('episode')
            ->toArray();

        $allEpisodes = Video::where('course_id', $course->id)
            ->orderBy('episode')
            ->pluck('episode')
            ->toArray();

        // Next episode = max used + 1, excluding current
        $nextEpisode = count($allEpisodes) > 0 ? max($allEpisodes) + 1 : 1;

        return view('member.video.edit', compact('course', 'video', 'usedEpisodes', 'nextEpisode'));
    }

    public function update(Request $request, $slug, Video $video)
    {
        $course = Course::where('slug', $slug)->first();

        $request->validate([
            'name'       => 'required|string|max:255',
            'episode'    => [
                'required',
                'integer',
                'min:1',
                Rule::unique('videos', 'episode')
                    ->where('course_id', $course->id)
                    ->ignore($video->id),
            ],
            'video_code' => 'required|string|max:255',
            'teori'      => 'required|string',
        ], [
            'episode.unique' => 'Episode ini sudah dipakai episode lain! Pilih nomor yang berbeda.',
        ]);

        $video->update([
            'name'       => $request->name,
            'episode'    => $request->episode,
            'intro'      => $request->intro,
            'video_code' => $request->video_code,
            'teori'      => $request->teori,
            'status'     => 'pending',
        ]);

        return redirect(route('member.video.index', $course))->with('toast_success', 'Episode berhasil diupdate!');
    }

    public function destroy(Video $video)
    {
        $video->delete();
        return back()->with('toast_success', 'Episode berhasil dihapus!');
    }
}
