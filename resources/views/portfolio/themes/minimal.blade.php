<!DOCTYPE html>
<html lang="id" class="{{ $website->font_family === 'serif' ? 'font-serif' : ($website->font_family === 'mono' ? 'font-mono' : '') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $website->meta_title ?? ($website->profile?->full_name ?? $user->name) . ' — Portfolio' }}</title>
    <meta name="description" content="{{ $website->meta_description ?? $website->profile?->short_bio }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { theme: { extend: { colors: { accent: '{{ $website->accent_color }}' } } } }</script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root { --accent: {{ $website->accent_color }}; }
        .accent-bg { background-color: var(--accent); }
        .accent-text { color: var(--accent); }
        .accent-border { border-color: var(--accent); }
        html { scroll-behavior: smooth; }
        @if($website->font_family === 'serif') body { font-family: Georgia, serif; }
        @elseif($website->font_family === 'mono') body { font-family: 'Courier New', monospace; }
        @else body { font-family: 'Inter', system-ui, sans-serif; } @endif
    </style>
</head>
<body class="bg-white text-gray-800">

{{-- Navbar --}}
<nav class="fixed top-0 left-0 right-0 bg-white/90 backdrop-blur border-b z-50">
    <div class="max-w-5xl mx-auto px-6 py-3 flex justify-between items-center">
        <span class="font-bold accent-text text-lg">{{ $website->profile?->full_name ?? $user->name }}</span>
        <div class="flex gap-5 text-sm font-medium">
            @foreach(['about','skills','experience','portfolio','contact'] as $section)
            <a href="#{{ $section }}" class="hover:accent-text transition-colors capitalize">{{ ucfirst($section) }}</a>
            @endforeach
        </div>
    </div>
</nav>

{{-- Hero --}}
<section id="about" class="pt-28 pb-20 max-w-5xl mx-auto px-6 flex flex-col md:flex-row items-center gap-12">
    <div class="flex-1">
        <p class="accent-text font-semibold text-sm uppercase tracking-widest mb-2">Hello, I'm</p>
        <h1 class="text-5xl font-bold mb-3">{{ $website->profile?->full_name ?? $user->name }}</h1>
        <h2 class="text-2xl text-gray-500 mb-4">{{ $website->profile?->role_title }}</h2>
        <p class="text-gray-600 leading-relaxed mb-6">{{ $website->profile?->short_bio }}</p>
        <div class="flex gap-3 flex-wrap">
            @if($website->profile?->cv_file)
            <a href="{{ $website->profile->cv_file }}" target="_blank" class="accent-bg text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:opacity-90 transition">
                <i class="fas fa-download mr-2"></i>Download CV
            </a>
            @endif
            <a href="#contact" class="border-2 accent-border accent-text px-5 py-2.5 rounded-lg font-semibold text-sm hover:accent-bg hover:text-white transition">
                Hire Me
            </a>
        </div>
        {{-- Social --}}
        <div class="flex gap-4 mt-6">
            @foreach($website->socialLinks as $link)
            <a href="{{ $link->url }}" target="_blank" class="text-gray-400 hover:accent-text text-xl transition">
                <i class="{{ match($link->platform) {
                    'linkedin' => 'fab fa-linkedin', 'github' => 'fab fa-github',
                    'instagram' => 'fab fa-instagram', 'twitter' => 'fab fa-twitter',
                    'dribbble' => 'fab fa-dribbble', 'behance' => 'fab fa-behance',
                    default => 'fas fa-globe'
                } }}"></i>
            </a>
            @endforeach
        </div>
    </div>
    @if($website->profile?->avatar)
    <div class="flex-shrink-0">
        <img src="{{ $website->profile->avatar }}" alt="{{ $website->profile->full_name }}"
            class="w-56 h-56 rounded-full object-cover shadow-xl border-4 accent-border">
    </div>
    @endif
</section>

{{-- About --}}
@if($website->profile?->about_me)
<section class="bg-gray-50 py-16">
    <div class="max-w-5xl mx-auto px-6">
        <h3 class="text-3xl font-bold mb-6">About <span class="accent-text">Me</span></h3>
        <p class="text-gray-600 leading-relaxed text-lg max-w-3xl">{{ $website->profile->about_me }}</p>
        <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-6">
            @if($website->profile->email)
            <div><span class="text-xs uppercase text-gray-400">Email</span><p class="font-semibold">{{ $website->profile->email }}</p></div>
            @endif
            @if($website->profile->phone)
            <div><span class="text-xs uppercase text-gray-400">Phone</span><p class="font-semibold">{{ $website->profile->phone }}</p></div>
            @endif
            @if($website->profile->location)
            <div><span class="text-xs uppercase text-gray-400">Location</span><p class="font-semibold">{{ $website->profile->location }}</p></div>
            @endif
        </div>
    </div>
</section>
@endif

{{-- Skills --}}
@if($website->skills->count())
<section id="skills" class="py-16 max-w-5xl mx-auto px-6">
    <h3 class="text-3xl font-bold mb-8">My <span class="accent-text">Skills</span></h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($website->skills as $skill)
        <div>
            <div class="flex justify-between mb-1">
                <span class="font-semibold text-sm">{{ $skill->name }}</span>
                <span class="text-xs text-gray-400 uppercase">{{ $skill->level }} — {{ $skill->percentage }}%</span>
            </div>
            <div class="bg-gray-200 rounded-full h-2.5">
                <div class="accent-bg h-2.5 rounded-full" style="width: {{ $skill->percentage }}%"></div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif

{{-- Services --}}
@if($website->services->count())
<section class="bg-gray-50 py-16">
    <div class="max-w-5xl mx-auto px-6">
        <h3 class="text-3xl font-bold mb-8">My <span class="accent-text">Services</span></h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($website->services as $service)
            <div class="bg-white rounded-xl p-6 shadow-sm border hover:shadow-md transition">
                @if($service->icon)
                <i class="{{ $service->icon }} text-3xl accent-text mb-4"></i>
                @endif
                <h4 class="font-bold text-lg mb-2">{{ $service->title }}</h4>
                <p class="text-gray-500 text-sm">{{ $service->description }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Experience --}}
@if($website->experiences->count())
<section id="experience" class="py-16 max-w-5xl mx-auto px-6">
    <h3 class="text-3xl font-bold mb-8">Work <span class="accent-text">Experience</span></h3>
    <div class="relative border-l-2 accent-border pl-8 space-y-8">
        @foreach($website->experiences as $exp)
        <div class="relative">
            <div class="absolute -left-10 w-4 h-4 rounded-full accent-bg border-4 border-white shadow"></div>
            <div class="bg-gray-50 rounded-xl p-5">
                <p class="text-xs accent-text font-semibold uppercase tracking-wide mb-1">
                    {{ $exp->start_date->format('M Y') }} — {{ $exp->is_current ? 'Present' : ($exp->end_date?->format('M Y') ?? '-') }}
                </p>
                <h4 class="font-bold text-lg">{{ $exp->position }}</h4>
                <p class="text-gray-500 font-medium">{{ $exp->company }}</p>
                @if($exp->description)<p class="text-gray-600 text-sm mt-2">{{ $exp->description }}</p>@endif
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif

{{-- Education --}}
@if($website->educations->count())
<section class="bg-gray-50 py-16">
    <div class="max-w-5xl mx-auto px-6">
        <h3 class="text-3xl font-bold mb-8">My <span class="accent-text">Education</span></h3>
        <div class="space-y-5">
            @foreach($website->educations as $edu)
            <div class="bg-white rounded-xl p-5 shadow-sm border flex items-start gap-4">
                <div class="w-12 h-12 accent-bg rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-graduation-cap text-white text-xl"></i>
                </div>
                <div>
                    <h4 class="font-bold">{{ $edu->institution }}</h4>
                    <p class="text-gray-500 text-sm">{{ $edu->degree }} — {{ $edu->field }}</p>
                    <p class="text-xs text-gray-400">{{ $edu->start_year }} — {{ $edu->end_year ?? 'Sekarang' }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Portfolio --}}
@if($website->portfolios->count())
<section id="portfolio" class="py-16 max-w-5xl mx-auto px-6">
    <h3 class="text-3xl font-bold mb-8">My <span class="accent-text">Portfolio</span></h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($website->portfolios as $item)
        <div class="bg-white rounded-xl overflow-hidden shadow-sm border hover:shadow-lg transition group">
            @if($item->image)
            <div class="overflow-hidden h-44">
                <img src="{{ asset('storage/website/portfolio/'.$item->image) }}" alt="{{ $item->title }}"
                    class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
            </div>
            @endif
            <div class="p-5">
                <h4 class="font-bold text-lg mb-1">{{ $item->title }}</h4>
                @if($item->description)<p class="text-gray-500 text-sm mb-2">{{ \Str::limit($item->description, 80) }}</p>@endif
                @if($item->tech_stack)<p class="text-xs accent-text font-mono">{{ $item->tech_stack }}</p>@endif
                @if($item->url)
                <a href="{{ $item->url }}" target="_blank" class="mt-3 inline-block text-sm accent-text font-semibold hover:underline">
                    <i class="fas fa-external-link-alt mr-1"></i>Lihat Proyek
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
<section class="bg-gray-50 py-16">
    <div class="max-w-5xl mx-auto px-6">
        <h3 class="text-3xl font-bold mb-8">What <span class="accent-text">They Say</span></h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($website->testimonials as $t)
            <div class="bg-white rounded-xl p-6 shadow-sm border">
                <p class="text-gray-600 italic mb-4">"{{ $t->content }}"</p>
                <div class="flex items-center gap-3">
                    @if($t->avatar)
                    <img src="{{ asset('storage/website/testimonials/'.$t->avatar) }}" class="w-10 h-10 rounded-full object-cover">
                    @else
                    <div class="w-10 h-10 rounded-full accent-bg flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr($t->client_name,0,1)) }}
                    </div>
                    @endif
                    <div>
                        <p class="font-semibold text-sm">{{ $t->client_name }}</p>
                        @if($t->client_role)<p class="text-xs text-gray-400">{{ $t->client_role }}</p>@endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Contact --}}
<section id="contact" class="py-16 max-w-5xl mx-auto px-6 text-center">
    <h3 class="text-3xl font-bold mb-4">Get In <span class="accent-text">Touch</span></h3>
    <p class="text-gray-500 mb-8">Tertarik bekerja sama? Hubungi saya!</p>
    <div class="flex flex-wrap justify-center gap-4">
        @if($website->profile?->email)
        <a href="mailto:{{ $website->profile->email }}" class="accent-bg text-white px-6 py-3 rounded-lg font-semibold hover:opacity-90 transition">
            <i class="fas fa-envelope mr-2"></i>{{ $website->profile->email }}
        </a>
        @endif
        @if($website->profile?->phone)
        <a href="tel:{{ $website->profile->phone }}" class="border-2 accent-border accent-text px-6 py-3 rounded-lg font-semibold hover:accent-bg hover:text-white transition">
            <i class="fas fa-phone mr-2"></i>{{ $website->profile->phone }}
        </a>
        @endif
    </div>
</section>

{{-- Footer --}}
<footer class="bg-gray-900 text-gray-400 text-center py-6 text-sm">
    <p>&copy; {{ date('Y') }} {{ $website->profile?->full_name ?? $user->name }}. Built with <span class="accent-text">❤</span></p>
</footer>

</body>
</html>
