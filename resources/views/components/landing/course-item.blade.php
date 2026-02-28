@props(['course'])

<a href="{{ route('course.show', $course->slug) }}" class="max-w-sm rounded overflow-hidden shadow-lg transition-transform duration-300 transform hover:scale-105 dark:bg-slate-800">
    <img class="w-full rounded-t-lg object-cover h-44 sm:h-48 md:h-56" src="{{ $course->image }}" alt="{{ $course->name }}">
    @auth
        @if(auth()->user()->hasRole('admin') && $course->status !== 'approved')
        <div class="px-3 pt-2">
            @if($course->status === 'pending')
                <span class="inline-block text-xs bg-yellow-100 text-yellow-800 font-semibold px-2 py-0.5 rounded-full">⏳ Pending Review</span>
            @else
                <span class="inline-block text-xs bg-red-100 text-red-800 font-semibold px-2 py-0.5 rounded-full">✕ Rejected</span>
            @endif
        </div>
        @endif
    @endauth
    <div class="px-6 py-4">
        <h1 class="font-bold text-lg sm:text-xl mb-2  dark:text-white hover:underline">
            {{ $course->name }}
        </h1>
        <p class="text-gray-500 text-sm sm:text-base">
            {{ implode(' ', array_slice(explode(' ', $course->description), 0, 5)) }}...
        </p>        
    </div>
    <div class="px-6 py-4">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 text-xs sm:text-sm my-2">
            <div class="text-slate-400 flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-list w-4 h-4" width="16"
                    height="16" viewBox="0 0 24 24" stroke-width="1.25" stroke="currentColor" fill="none"
                    stroke-linecap="round" stroke-linejoin="round">
                    <!-- ... (kode sebelumnya) ... -->
                </svg>
                {{ $course->videos_count }} Ep
            </div>
            <div class="text-slate-400 flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users w-4 h-4" width="16"
                    height="16" viewBox="0 0 24 24" stroke-width="1.25" stroke="currentColor" fill="none"
                    stroke-linecap="round" stroke-linejoin="round">
                    <!-- ... (kode sebelumnya) ... -->
                </svg>
                {{ $course->enrolled }} Member
            </div>
            <div class="text-slate-400 flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-message-2 w-4 h-4" width="16"
                    height="16" viewBox="0 0 24 24" stroke-width="1.25" stroke="currentColor" fill="none"
                    stroke-linecap="round" stroke-linejoin="round">
                    <!-- ... (kode sebelumnya) ... -->
                </svg>
                {{ $course->reviews_count }} Review
            </div>
        </div>
        <div class="flex justify-between items-center mt-2">
            <span class="text-xs sm:text-sm p-1 border bg-red-800 text-white rounded font-semibold border-red-600">
                Disc {{ $course->discount }}%
            </span>
            <div class="flex flex-col text-right md:text-left">
                <span class="line-through text-red-500 font-mono text-xs sm:text-sm">
                    <sup>Rp</sup>{{ moneyFormat($course->price) }}
                </span>
                <span class="text-green-500 font-mono text-xs sm:text-sm">
                    <sup>Rp</sup>{{ moneyFormat(discount($course->price, $course->discount)) }}
                </span>
            </div>
        </div>
    </div>
    <div class="px-6 pb-4">
        <div class="flex items-center gap-1 transition-transform transform hover:scale-105">
            <img class="w-6 h-6 rounded-full" src="{{ $course->user->avatar }}" alt="{{ $course->user->name }}">
            <div class="text-slate-400 font-medium text-xs sm:text-sm">{{ $course->user->name }}</div>
        </div>
    </div>
</a>
