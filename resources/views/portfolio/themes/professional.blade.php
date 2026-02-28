<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $website->meta_title ?? ($website->profile?->full_name ?? $user->name) . ' — Portfolio' }}</title>
    <meta name="description" content="{{ $website->meta_description ?? $website->profile?->short_bio }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root { --accent: {{ $website->accent_color }}; }
        .accent-bg { background-color: var(--accent); }
        .accent-text { color: var(--accent); }
        .accent-border { border-color: var(--accent); }
        html { scroll-behavior: smooth; }
        @if($website->font_family === 'serif') body { font-family: Georgia, 'Times New Roman', serif; }
        @elseif($website->font_family === 'mono') body { font-family: 'Courier New', monospace; }
        @else body { font-family: 'Segoe UI', system-ui, sans-serif; } @endif
        .sidebar-sticky { position: sticky; top: 0; height: 100vh; overflow-y: auto; }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">

<div class="flex min-h-screen">
    {{-- Sidebar --}}
    <aside class="w-72 accent-bg text-white flex-shrink-0">
        <div class="sidebar-sticky p-8 flex flex-col">
            {{-- Avatar --}}
            <div class="text-center mb-8">
                @if($website->profile?->avatar)
                <img src="{{ $website->profile->avatar }}" alt="{{ $website->profile->full_name }}"
                    class="w-32 h-32 rounded-full object-cover border-4 border-white/40 mx-auto mb-4">
                @else
                <div class="w-32 h-32 rounded-full bg-white/20 flex items-center justify-center mx-auto mb-4 text-5xl font-black">
                    {{ strtoupper(substr($website->profile?->full_name ?? $user->name, 0, 1)) }}
                </div>
                @endif
                <h1 class="text-xl font-bold">{{ $website->profile?->full_name ?? $user->name }}</h1>
                <p class="text-white/80 text-sm mt-1">{{ $website->profile?->role_title }}</p>
            </div>

            {{-- Contact info --}}
            <div class="mb-8 space-y-2">
                @if($website->profile?->email)
                <div class="flex items-center gap-2 text-sm text-white/80">
                    <i class="fas fa-envelope w-4"></i>
                    <a href="mailto:{{ $website->profile->email }}" class="hover:text-white truncate">{{ $website->profile->email }}</a>
                </div>
                @endif
                @if($website->profile?->phone)
                <div class="flex items-center gap-2 text-sm text-white/80">
                    <i class="fas fa-phone w-4"></i>
                    <span>{{ $website->profile->phone }}</span>
                </div>
                @endif
                @if($website->profile?->location)
                <div class="flex items-center gap-2 text-sm text-white/80">
                    <i class="fas fa-map-marker-alt w-4"></i>
                    <span>{{ $website->profile->location }}</span>
                </div>
                @endif
            </div>

            {{-- Social --}}
            @if($website->socialLinks->count())
            <div class="mb-8">
                <h3 class="text-xs font-semibold uppercase tracking-widest text-white/60 mb-3">Connect</h3>
                <div class="flex flex-wrap gap-3">
                    @foreach($website->socialLinks as $link)
                    <a href="{{ $link->url }}" target="_blank" class="text-white/70 hover:text-white text-xl transition">
                        <i class="{{ match($link->platform) { 'linkedin'=>'fab fa-linkedin','github'=>'fab fa-github','instagram'=>'fab fa-instagram','twitter'=>'fab fa-twitter','dribbble'=>'fab fa-dribbble','behance'=>'fab fa-behance',default=>'fas fa-globe' } }}"></i>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Skills --}}
            @if($website->skills->count())
            <div class="mb-8">
                <h3 class="text-xs font-semibold uppercase tracking-widest text-white/60 mb-4">Skills</h3>
                <div class="space-y-3">
                    @foreach($website->skills->take(8) as $skill)
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span>{{ $skill->name }}</span>
                            <span class="text-white/70">{{ $skill->percentage }}%</span>
                        </div>
                        <div class="bg-white/20 rounded-full h-1.5">
                            <div class="bg-white h-1.5 rounded-full" style="width:{{ $skill->percentage }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($website->profile?->cv_file)
            <a href="{{ $website->profile->cv_file }}" target="_blank" class="mt-auto bg-white/20 hover:bg-white/30 text-white text-center py-3 rounded-lg font-semibold text-sm transition">
                <i class="fas fa-download mr-2"></i>Download CV
            </a>
            @endif
        </div>
    </aside>

    {{-- Main Content --}}
    <main class="flex-1 overflow-y-auto">
        {{-- About --}}
        <section id="about" class="bg-white border-b p-10">
            <p class="text-xs uppercase tracking-widest accent-text font-semibold mb-2">About Me</p>
            <h2 class="text-3xl font-bold mb-3">{{ $website->profile?->full_name ?? $user->name }}</h2>
            <p class="text-gray-600 leading-relaxed max-w-2xl">{{ $website->profile?->about_me ?? $website->profile?->short_bio }}</p>
        </section>

        {{-- Experience --}}
        @if($website->experiences->count())
        <section id="experience" class="bg-gray-50 border-b p-10">
            <h2 class="text-xl font-bold mb-6 flex items-center gap-2"><i class="fas fa-briefcase accent-text"></i>Work Experience</h2>
            <div class="space-y-5">
                @foreach($website->experiences as $exp)
                <div class="bg-white rounded-xl p-5 shadow-sm border-l-4 accent-border">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-1">
                        <h3 class="font-bold text-lg">{{ $exp->position }}</h3>
                        <span class="text-xs text-gray-400 font-mono">
                            {{ $exp->start_date->format('M Y') }} — {{ $exp->is_current ? 'Present' : ($exp->end_date?->format('M Y') ?? '-') }}
                        </span>
                    </div>
                    <p class="accent-text font-semibold text-sm mb-2">{{ $exp->company }}</p>
                    @if($exp->description)<p class="text-gray-500 text-sm">{{ $exp->description }}</p>@endif
                </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- Education --}}
        @if($website->educations->count())
        <section id="education" class="bg-white border-b p-10">
            <h2 class="text-xl font-bold mb-6 flex items-center gap-2"><i class="fas fa-graduation-cap accent-text"></i>Education</h2>
            <div class="space-y-4">
                @foreach($website->educations as $edu)
                <div class="border rounded-xl p-5">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-1">
                        <h3 class="font-bold">{{ $edu->institution }}</h3>
                        <span class="text-xs text-gray-400">{{ $edu->start_year }} — {{ $edu->end_year ?? 'Sekarang' }}</span>
                    </div>
                    <p class="accent-text font-semibold text-sm">{{ $edu->degree }} — {{ $edu->field }}</p>
                    @if($edu->description)<p class="text-gray-500 text-sm mt-1">{{ $edu->description }}</p>@endif
                </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- Services --}}
        @if($website->services->count())
        <section id="services" class="bg-gray-50 border-b p-10">
            <h2 class="text-xl font-bold mb-6 flex items-center gap-2"><i class="fas fa-cogs accent-text"></i>Services</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($website->services as $service)
                <div class="bg-white rounded-xl p-5 shadow-sm flex gap-4">
                    @if($service->icon)
                    <div class="w-12 h-12 accent-bg/10 accent-text rounded-lg flex items-center justify-center flex-shrink-0 text-xl">
                        <i class="{{ $service->icon }}"></i>
                    </div>
                    @endif
                    <div>
                        <h3 class="font-bold mb-1">{{ $service->title }}</h3>
                        <p class="text-gray-500 text-sm">{{ $service->description }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- Portfolio --}}
        @if($website->portfolios->count())
        <section id="portfolio" class="bg-white border-b p-10">
            <h2 class="text-xl font-bold mb-6 flex items-center gap-2"><i class="fas fa-folder-open accent-text"></i>Portfolio</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                @foreach($website->portfolios as $item)
                <div class="border rounded-xl overflow-hidden hover:shadow-md transition group">
                    @if($item->image)
                    <div class="overflow-hidden h-40">
                        <img src="{{ asset('storage/website/portfolio/'.$item->image) }}" alt="{{ $item->title }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </div>
                    @endif
                    <div class="p-4">
                        <h3 class="font-bold mb-1">{{ $item->title }}</h3>
                        @if($item->tech_stack)<p class="text-xs accent-text font-mono mb-2">{{ $item->tech_stack }}</p>@endif
                        @if($item->description)<p class="text-gray-500 text-sm mb-2">{{ \Str::limit($item->description, 80) }}</p>@endif
                        @if($item->url)
                        <a href="{{ $item->url }}" target="_blank" class="text-xs accent-text font-semibold hover:underline">
                            <i class="fas fa-external-link-alt mr-1"></i>View Project
                        </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- Testimonials --}}
        @if($website->testimonials->count())
        <section class="bg-gray-50 border-b p-10">
            <h2 class="text-xl font-bold mb-6 flex items-center gap-2"><i class="fas fa-quote-left accent-text"></i>Testimonials</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($website->testimonials as $t)
                <div class="bg-white rounded-xl p-5 shadow-sm border-l-4 accent-border">
                    <p class="text-gray-600 italic text-sm mb-4">"{{ $t->content }}"</p>
                    <div class="flex items-center gap-3">
                        @if($t->avatar)
                        <img src="{{ asset('storage/website/testimonials/'.$t->avatar) }}" class="w-9 h-9 rounded-full object-cover">
                        @else
                        <div class="w-9 h-9 rounded-full accent-bg text-white flex items-center justify-center font-bold text-sm">{{ strtoupper(substr($t->client_name,0,1)) }}</div>
                        @endif
                        <div>
                            <p class="font-semibold text-sm">{{ $t->client_name }}</p>
                            @if($t->client_role)<p class="text-xs text-gray-400">{{ $t->client_role }}</p>@endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- Footer --}}
        <footer class="bg-white p-6 text-center text-gray-400 text-sm">
            &copy; {{ date('Y') }} {{ $website->profile?->full_name ?? $user->name }}. All rights reserved.
        </footer>
    </main>
</div>

</body>
</html>
