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
        .accent-glow { box-shadow: 0 0 20px color-mix(in srgb, var(--accent) 40%, transparent); }
        html { scroll-behavior: smooth; }
        @if($website->font_family === 'serif') body { font-family: Georgia, serif; }
        @elseif($website->font_family === 'mono') body { font-family: 'Courier New', monospace; }
        @else body { font-family: system-ui, sans-serif; } @endif
        .gradient-text {
            background: linear-gradient(135deg, var(--accent), #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .card-dark { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); }
    </style>
</head>
<body class="bg-gray-950 text-gray-100 min-h-screen">

{{-- Navbar --}}
<nav class="fixed top-0 left-0 right-0 bg-gray-950/80 backdrop-blur-md border-b border-white/10 z-50">
    <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
        <span class="font-bold gradient-text text-xl">{{ $website->profile?->full_name ?? $user->name }}</span>
        <div class="flex gap-6 text-sm text-gray-400">
            @foreach(['about','skills','portfolio','contact'] as $s)
            <a href="#{{ $s }}" class="hover:accent-text transition-colors capitalize">{{ ucfirst($s) }}</a>
            @endforeach
        </div>
    </div>
</nav>

{{-- Hero --}}
<section id="about" class="min-h-screen flex items-center relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-gray-950 via-gray-900 to-gray-950"></div>
    <div class="absolute top-1/3 right-1/4 w-96 h-96 rounded-full opacity-10 accent-bg blur-3xl"></div>
    <div class="relative max-w-6xl mx-auto px-6 pt-24 pb-16 flex flex-col md:flex-row items-center gap-16">
        <div class="flex-1">
            <div class="inline-flex items-center gap-2 accent-bg/20 accent-text px-4 py-1.5 rounded-full text-sm font-semibold mb-6">
                <span class="w-2 h-2 rounded-full accent-bg animate-pulse"></span>
                Available for work
            </div>
            <h1 class="text-6xl font-black mb-4 leading-tight">
                Hi, I'm <span class="gradient-text">{{ $website->profile?->full_name ?? $user->name }}</span>
            </h1>
            <h2 class="text-2xl text-gray-400 font-medium mb-5">{{ $website->profile?->role_title }}</h2>
            <p class="text-gray-400 text-lg leading-relaxed mb-8 max-w-xl">{{ $website->profile?->short_bio }}</p>
            <div class="flex gap-4 flex-wrap">
                @if($website->profile?->cv_file)
                <a href="{{ $website->profile->cv_file }}" target="_blank" class="accent-bg text-white px-6 py-3 rounded-xl font-bold hover:opacity-90 accent-glow transition">
                    <i class="fas fa-download mr-2"></i>Download CV
                </a>
                @endif
                <a href="#portfolio" class="border accent-border accent-text px-6 py-3 rounded-xl font-bold hover:accent-bg hover:text-white transition">
                    View Work
                </a>
            </div>
            <div class="flex gap-5 mt-8">
                @foreach($website->socialLinks as $link)
                <a href="{{ $link->url }}" target="_blank" class="text-gray-500 hover:accent-text text-2xl transition hover:-translate-y-1">
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
        <div class="flex-shrink-0 relative">
            <div class="w-64 h-64 rounded-3xl overflow-hidden accent-glow border-2 accent-border">
                <img src="{{ $website->profile->avatar }}" class="w-full h-full object-cover">
            </div>
        </div>
        @endif
    </div>
</section>

{{-- Skills --}}
@if($website->skills->count())
<section id="skills" class="py-20">
    <div class="max-w-6xl mx-auto px-6">
        <h3 class="text-4xl font-black mb-2">Tech <span class="gradient-text">Stack</span></h3>
        <p class="text-gray-400 mb-10">Technologies I work with</p>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($website->skills as $skill)
            <div class="card-dark rounded-2xl p-5 hover:accent-border hover:border-opacity-50 transition group">
                <div class="flex justify-between items-start mb-3">
                    <span class="font-bold">{{ $skill->name }}</span>
                    <span class="text-xs accent-text font-mono">{{ $skill->percentage }}%</span>
                </div>
                <div class="bg-white/10 rounded-full h-1.5">
                    <div class="accent-bg h-1.5 rounded-full transition-all duration-700" style="width: {{ $skill->percentage }}%"></div>
                </div>
                <span class="text-xs text-gray-500 mt-2 block uppercase tracking-wider">{{ $skill->level }}</span>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Services --}}
@if($website->services->count())
<section class="py-20 bg-gray-900/50">
    <div class="max-w-6xl mx-auto px-6">
        <h3 class="text-4xl font-black mb-10">What I <span class="gradient-text">Do</span></h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($website->services as $service)
            <div class="card-dark rounded-2xl p-6 hover:accent-border transition group">
                @if($service->icon)
                <div class="w-14 h-14 accent-bg/20 accent-text rounded-xl flex items-center justify-center mb-4 text-2xl group-hover:accent-bg group-hover:text-white transition">
                    <i class="{{ $service->icon }}"></i>
                </div>
                @endif
                <h4 class="font-bold text-xl mb-3">{{ $service->title }}</h4>
                <p class="text-gray-400 text-sm leading-relaxed">{{ $service->description }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Experience --}}
@if($website->experiences->count())
<section id="experience" class="py-20">
    <div class="max-w-6xl mx-auto px-6">
        <h3 class="text-4xl font-black mb-10">Work <span class="gradient-text">Experience</span></h3>
        <div class="space-y-6">
            @foreach($website->experiences as $exp)
            <div class="card-dark rounded-2xl p-6 flex gap-6 items-start">
                <div class="w-12 h-12 accent-bg/20 accent-text rounded-xl flex items-center justify-center flex-shrink-0 text-xl">
                    <i class="fas fa-briefcase"></i>
                </div>
                <div class="flex-1">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-1">
                        <h4 class="font-bold text-lg">{{ $exp->position }}</h4>
                        <span class="text-xs accent-text font-mono">
                            {{ $exp->start_date->format('M Y') }} — {{ $exp->is_current ? 'Present' : ($exp->end_date?->format('M Y') ?? '-') }}
                        </span>
                    </div>
                    <p class="text-gray-400 font-medium mb-2">{{ $exp->company }}</p>
                    @if($exp->description)<p class="text-gray-500 text-sm">{{ $exp->description }}</p>@endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Portfolio --}}
@if($website->portfolios->count())
<section id="portfolio" class="py-20 bg-gray-900/50">
    <div class="max-w-6xl mx-auto px-6">
        <h3 class="text-4xl font-black mb-10">Featured <span class="gradient-text">Projects</span></h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($website->portfolios as $item)
            <div class="card-dark rounded-2xl overflow-hidden group hover:accent-border transition">
                @if($item->image)
                <div class="overflow-hidden h-48 bg-gray-800">
                    <img src="{{ asset('storage/website/portfolio/'.$item->image) }}" alt="{{ $item->title }}"
                        class="w-full h-full object-cover group-hover:scale-110 transition duration-500 opacity-80 group-hover:opacity-100">
                </div>
                @endif
                <div class="p-5">
                    <h4 class="font-bold text-lg mb-2">{{ $item->title }}</h4>
                    @if($item->tech_stack)
                    <div class="flex flex-wrap gap-1.5 mb-3">
                        @foreach(explode(',', $item->tech_stack) as $tech)
                        <span class="accent-bg/20 accent-text text-xs px-2 py-0.5 rounded-full font-mono">{{ trim($tech) }}</span>
                        @endforeach
                    </div>
                    @endif
                    @if($item->description)<p class="text-gray-500 text-sm mb-3">{{ \Str::limit($item->description, 80) }}</p>@endif
                    @if($item->url)
                    <a href="{{ $item->url }}" target="_blank" class="text-sm accent-text font-semibold hover:underline">
                        <i class="fas fa-arrow-up-right-from-square mr-1"></i>View Project
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Testimonials --}}
@if($website->testimonials->count())
<section class="py-20">
    <div class="max-w-6xl mx-auto px-6">
        <h3 class="text-4xl font-black mb-10">Client <span class="gradient-text">Reviews</span></h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($website->testimonials as $t)
            <div class="card-dark rounded-2xl p-6">
                <div class="flex mb-4">@for($i=0;$i<5;$i++)<i class="fas fa-star accent-text text-sm mr-0.5"></i>@endfor</div>
                <p class="text-gray-300 italic mb-5">"{{ $t->content }}"</p>
                <div class="flex items-center gap-3">
                    @if($t->avatar)
                    <img src="{{ asset('storage/website/testimonials/'.$t->avatar) }}" class="w-10 h-10 rounded-full object-cover">
                    @else
                    <div class="w-10 h-10 rounded-full accent-bg flex items-center justify-center font-bold">{{ strtoupper(substr($t->client_name,0,1)) }}</div>
                    @endif
                    <div>
                        <p class="font-semibold text-sm">{{ $t->client_name }}</p>
                        @if($t->client_role)<p class="text-xs text-gray-500">{{ $t->client_role }}</p>@endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Contact --}}
<section id="contact" class="py-20 bg-gray-900/50">
    <div class="max-w-6xl mx-auto px-6 text-center">
        <h3 class="text-4xl font-black mb-4">Let's <span class="gradient-text">Connect</span></h3>
        <p class="text-gray-400 text-lg mb-10">Got a project? Let's talk.</p>
        <div class="flex flex-wrap justify-center gap-4">
            @if($website->profile?->email)
            <a href="mailto:{{ $website->profile->email }}" class="accent-bg text-white px-8 py-4 rounded-2xl font-bold hover:opacity-90 accent-glow transition">
                <i class="fas fa-envelope mr-2"></i>{{ $website->profile->email }}
            </a>
            @endif
        </div>
        <div class="flex justify-center gap-6 mt-10">
            @foreach($website->socialLinks as $link)
            <a href="{{ $link->url }}" target="_blank" class="text-gray-500 hover:accent-text text-2xl transition hover:-translate-y-1">
                <i class="{{ match($link->platform) { 'linkedin'=>'fab fa-linkedin','github'=>'fab fa-github','instagram'=>'fab fa-instagram','twitter'=>'fab fa-twitter','dribbble'=>'fab fa-dribbble','behance'=>'fab fa-behance',default=>'fas fa-globe' } }}"></i>
            </a>
            @endforeach
        </div>
    </div>
</section>

<footer class="border-t border-white/10 py-6 text-center text-gray-500 text-sm">
    &copy; {{ date('Y') }} {{ $website->profile?->full_name ?? $user->name }}
</footer>

</body>
</html>
