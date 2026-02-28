<aside class="main-sidebar sidebar-light-teal elevation-4">
    <a href="/" class="brand-link">
        <div class="text-center">
            <span class="brand-text font-weight-bold">Unu Course</span>
        </div>
    </a>

    <div class="sidebar ">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @role('admin')
                    <li class="nav-header">DASHBOARD</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ active('admin.dashboard') }} ">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="icon icon-tabler icon-tabler-brand-tabler nav-icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="1.25" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M8 9l3 3l-3 3"></path>
                                <line x1="13" y1="15" x2="16" y2="15"></line>
                                <rect x="4" y="4" width="16" height="16" rx="4"></rect>
                            </svg>
                            <p>
                                Dashboard
                            </p>
                        </a>
                    </li>
                    <li class="nav-header">MASTER DATA</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.category.index') }}" class="nav-link {{ active('admin.category*') }} ">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-category-2 nav-icon"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="1.25" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M14 4h6v6h-6z"></path>
                                <path d="M4 14h6v6h-6z"></path>
                                <circle cx="17" cy="17" r="3"></circle>
                                <circle cx="7" cy="7" r="3"></circle>
                            </svg>
                            <p>
                                Category
                            </p>
                        </a>
                    </li>
                    <li class="nav-item {{ Route::is('admin.course*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ active('admin.course*') }}">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="icon icon-tabler icon-tabler-device-laptop nav-icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="1.25" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <line x1="3" y1="19" x2="21" y2="19"></line>
                                <rect x="5" y="6" width="14" height="10" rx="1">
                                </rect>
                            </svg>
                            <p>
                                Course
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.course.index') }}"
                                    class="nav-link {{ active('admin.course.index') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-corner-down-right-double nav-icon"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="1.25"
                                        stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M4 5v6a3 3 0 0 0 3 3h7"></path>
                                        <path d="M10 10l4 4l-4 4m5 -8l4 4l-4 4"></path>
                                    </svg>
                                    <p>List Course</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.course.create') }}"
                                    class="nav-link {{ active('admin.course.create') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-corner-down-right-double nav-icon"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="1.25"
                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M4 5v6a3 3 0 0 0 3 3h7"></path>
                                        <path d="M10 10l4 4l-4 4m5 -8l4 4l-4 4"></path>
                                    </svg>
                                    <p>Add New Course</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.showcase.index') }}" class="nav-link {{ active('admin.showcase*') }} ">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="icon icon-tabler icon-tabler-source-code nav-icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="1.25" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M14.5 4h2.5a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-10a3 3 0 0 1 -3 -3v-5"></path>
                                <path d="M6 5l-2 2l2 2"></path>
                                <path d="M10 9l2 -2l-2 -2"></path>
                            </svg>
                            <p>
                                Showcase
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.review.index') }}" class="nav-link {{ active('admin.review.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="icon icon-tabler icon-tabler-message-2 nav-icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="1.25" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path
                                    d="M12 20l-3 -3h-2a3 3 0 0 1 -3 -3v-6a3 3 0 0 1 3 -3h10a3 3 0 0 1 3 3v6a3 3 0 0 1 -3 3h-2l-3 3">
                                </path>
                                <line x1="8" y1="9" x2="16" y2="9"></line>
                                <line x1="8" y1="13" x2="14" y2="13"></line>
                            </svg>
                            <p>
                                Review
                            </p>
                        </a>
                    </li>
                    <li class="nav-header">LEARN</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.mycourse') }}" class="nav-link {{ active('member.mycourse*') }} ">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-devices nav-icon"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="1.25"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <rect x="13" y="8" width="8" height="12" rx="1">
                                </rect>
                                <path d="M18 8v-3a1 1 0 0 0 -1 -1h-13a1 1 0 0 0 -1 1v12a1 1 0 0 0 1 1h9"></path>
                                <line x1="16" y1="9" x2="18" y2="9"></line>
                            </svg>
                            <p>
                                My Course
                            </p>
                        </a>
                    </li>
                    <li class="nav-header">Transaction</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.transaction.index') }}"
                            class="nav-link {{ active('admin.transaction*') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-receipt nav-icon"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="1.25"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path
                                    d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2m4 -14h6m-6 4h6m-2 4h2">
                                </path>
                            </svg>
                            <p>
                                Transaction
                            </p>
                        </a>
                    </li>
                    </li>
                    <li class="nav-header">User Management</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.user.index') }}" class="nav-link {{ active('admin.user.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users nav-icon"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="1.25"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                <path d="M21 21v-2a4 4 0 0 0 -3 -3.85"></path>
                            </svg>
                            <p>
                                Users
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.user.profile') }}" class="nav-link {{ active('admin.user.profile') }}">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="icon icon-tabler icon-tabler-user-circle nav-icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="1.25" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <circle cx="12" cy="12" r="9"></circle>
                                <circle cx="12" cy="10" r="3"></circle>
                                <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855"></path>
                            </svg>
                            <p>
                                Profile
                            </p>
                        </a>
                    </li>
                    <li class="nav-header">PERSONAL WEBSITE</li>
                    <li class="nav-item {{ Route::is('member.website*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ active('member.website*') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-world nav-icon"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="1.25" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <circle cx="12" cy="12" r="9"></circle>
                                <line x1="3.6" y1="9" x2="20.4" y2="9"></line>
                                <line x1="3.6" y1="15" x2="20.4" y2="15"></line>
                                <path d="M11.5 3a17 17 0 0 0 0 18"></path>
                                <path d="M12.5 3a17 17 0 0 1 0 18"></path>
                            </svg>
                            <p>Personal Website <i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('member.website.index') }}" class="nav-link {{ active('member.website.index') }}">
                                    <i class="far fa-circle nav-icon"></i><p>Dashboard</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('member.website.profile') }}" class="nav-link {{ active('member.website.profile') }}">
                                    <i class="far fa-circle nav-icon"></i><p>Profile</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('member.website.skills') }}" class="nav-link {{ active('member.website.skills') }}">
                                    <i class="far fa-circle nav-icon"></i><p>Skills</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('member.website.services') }}" class="nav-link {{ active('member.website.services') }}">
                                    <i class="far fa-circle nav-icon"></i><p>Services</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('member.website.experience') }}" class="nav-link {{ active('member.website.experience') }}">
                                    <i class="far fa-circle nav-icon"></i><p>Experience</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('member.website.education') }}" class="nav-link {{ active('member.website.education') }}">
                                    <i class="far fa-circle nav-icon"></i><p>Education</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('member.website.portfolio') }}" class="nav-link {{ active('member.website.portfolio') }}">
                                    <i class="far fa-circle nav-icon"></i><p>Portfolio</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('member.website.testimonials') }}" class="nav-link {{ active('member.website.testimonials') }}">
                                    <i class="far fa-circle nav-icon"></i><p>Testimonials</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('member.website.social') }}" class="nav-link {{ active('member.website.social') }}">
                                    <i class="far fa-circle nav-icon"></i><p>Social Links</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('member.website.settings') }}" class="nav-link {{ active('member.website.settings') }}">
                                    <i class="far fa-circle nav-icon"></i><p>Settings</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endrole
                @role('member|author')
                    <li class="nav-header">DASHBOARD</li>
                    <li class="nav-item">
                        <a href="{{ route('member.dashboard') }}" class="nav-link {{ active('member.dashboard') }} ">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="icon icon-tabler icon-tabler-brand-tabler nav-icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="1.25" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M8 9l3 3l-3 3"></path>
                                <line x1="13" y1="15" x2="16" y2="15"></line>
                                <rect x="4" y="4" width="16" height="16" rx="4">
                                </rect>
                            </svg>
                            <p>
                                Dashboard
                            </p>
                        </a>
                    </li>
                    @role('author')
                        <li class="nav-header">MASTER DATA</li>
                        <li class="nav-item {{ Route::is('member.course*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ active('member.course*') }}">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-device-laptop nav-icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="1.25" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <line x1="3" y1="19" x2="21" y2="19"></line>
                                    <rect x="5" y="6" width="14" height="10" rx="1">
                                    </rect>
                                </svg>
                                <p>
                                    Course
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('member.course.index') }}"
                                        class="nav-link {{ active('member.course.index') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-corner-down-right-double nav-icon"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="1.25"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M4 5v6a3 3 0 0 0 3 3h7"></path>
                                            <path d="M10 10l4 4l-4 4m5 -8l4 4l-4 4"></path>
                                        </svg>
                                        <p>List Course</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('member.course.create') }}"
                                        class="nav-link {{ active('member.course.create') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-corner-down-right-double nav-icon"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="1.25"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M4 5v6a3 3 0 0 0 3 3h7"></path>
                                            <path d="M10 10l4 4l-4 4m5 -8l4 4l-4 4"></path>
                                        </svg>
                                        <p>Add New Course</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcan
                    <li class="nav-header">LEARN</li>
                    <li class="nav-item">
                        <a href="{{ route('member.mycourse') }}" class="nav-link {{ active('member.mycourse*') }} ">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-devices nav-icon"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="1.25"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <rect x="13" y="8" width="8" height="12" rx="1">
                                </rect>
                                <path d="M18 8v-3a1 1 0 0 0 -1 -1h-13a1 1 0 0 0 -1 1v12a1 1 0 0 0 1 1h9"></path>
                                <line x1="16" y1="9" x2="18" y2="9"></line>
                            </svg>
                            <p>
                                My Course
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('member.showcase.index') }}"
                            class="nav-link {{ active('member.showcase*') }}">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="icon icon-tabler icon-tabler-source-code nav-icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="1.25" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M14.5 4h2.5a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-10a3 3 0 0 1 -3 -3v-5"></path>
                                <path d="M6 5l-2 2l2 2"></path>
                                <path d="M10 9l2 -2l-2 -2"></path>
                            </svg>
                            <p>
                                Showcase
                            </p>
                        </a>
                    </li>
                    <li class="nav-header">TRANSACTION</li>
                    <li class="nav-item">
                        <a href="{{ route('member.transaction.index') }}"
                            class="nav-link {{ active('member.transaction*') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-receipt nav-icon"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="1.25"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path
                                    d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2m4 -14h6m-6 4h6m-2 4h2">
                                </path>
                            </svg>
                            <p>
                                Transaction
                            </p>
                        </a>
                    </li>
                    <li class="nav-header">PERSONAL WEBSITE</li>
                    <li class="nav-item {{ Route::is('member.website*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ active('member.website*') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-world nav-icon"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="1.25" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <circle cx="12" cy="12" r="9"></circle>
                                <line x1="3.6" y1="9" x2="20.4" y2="9"></line>
                                <line x1="3.6" y1="15" x2="20.4" y2="15"></line>
                                <path d="M11.5 3a17 17 0 0 0 0 18"></path>
                                <path d="M12.5 3a17 17 0 0 1 0 18"></path>
                            </svg>
                            <p>Personal Website <i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('member.website.index') }}" class="nav-link {{ active('member.website.index') }}">
                                    <i class="far fa-circle nav-icon"></i><p>Dashboard</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('member.website.profile') }}" class="nav-link {{ active('member.website.profile') }}">
                                    <i class="far fa-circle nav-icon"></i><p>Profile</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('member.website.skills') }}" class="nav-link {{ active('member.website.skills') }}">
                                    <i class="far fa-circle nav-icon"></i><p>Skills</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('member.website.services') }}" class="nav-link {{ active('member.website.services') }}">
                                    <i class="far fa-circle nav-icon"></i><p>Services</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('member.website.experience') }}" class="nav-link {{ active('member.website.experience') }}">
                                    <i class="far fa-circle nav-icon"></i><p>Experience</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('member.website.education') }}" class="nav-link {{ active('member.website.education') }}">
                                    <i class="far fa-circle nav-icon"></i><p>Education</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('member.website.portfolio') }}" class="nav-link {{ active('member.website.portfolio') }}">
                                    <i class="far fa-circle nav-icon"></i><p>Portfolio</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('member.website.testimonials') }}" class="nav-link {{ active('member.website.testimonials') }}">
                                    <i class="far fa-circle nav-icon"></i><p>Testimonials</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('member.website.social') }}" class="nav-link {{ active('member.website.social') }}">
                                    <i class="far fa-circle nav-icon"></i><p>Social Links</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('member.website.settings') }}" class="nav-link {{ active('member.website.settings') }}">
                                    <i class="far fa-circle nav-icon"></i><p>Settings</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-header">CONFIGURATION</li>
                    <li class="nav-item">
                        <a href="{{ route('member.profile.index') }}"
                            class="nav-link {{ active('member.profile.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="icon icon-tabler icon-tabler-user-circle nav-icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="1.25" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <circle cx="12" cy="12" r="9"></circle>
                                <circle cx="12" cy="10" r="3"></circle>
                                <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855"></path>
                            </svg>
                            <p>
                                Profile
                            </p>
                        </a>
                    </li>
                @endrole
                <li class="nav-header">SYSTEM</li>
                <li class="nav-item">
                    <a href="#" class="nav-link"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-logout nav-icon"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="1.25"
                            stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2">
                            </path>
                            <path d="M7 12h14l-3 -3m0 6l3 -3"></path>
                        </svg>
                        <p>
                            Sign Out
                        </p>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
