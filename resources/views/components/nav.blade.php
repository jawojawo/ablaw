@auth
    <div class="sidenav bg-dark text-white flex-column d-flex" style="width:250px;position-relative">
        {{-- <div class='avatar-con mb-4'>
            <div class="avatar shadow"><img src="https://api.dicebear.com/5.x/adventurer/svg?seed=firstNmae" alt="avatar">
            </div>
            <div class="position-relative m-2 w-100 h-100">
                <div class="name text-center">
                    <a class="text-reset text-decoration-none" href="{{ route('user.show', auth()->user()->id) }}">
                        {{ auth()->user()->name }}
                    </a>

                </div>

            </div>
        </div> --}}
        <div class="bg-dark">
            <img class="w-100 px-5 pt-4 pb-2" src="{{ asset('img/invoice_logo.png') }}">
        </div>
        <a class="text-reset text-decoration-none " href="{{ route('user.show', auth()->user()->id) }}">
            <div class="d-flex gap-2 align-items-center pb-4 pt-4 px-2">
                <div>
                    <div class="text-bg-light rounded-circle d-flex align-items-center justify-content-center fw-bold"
                        style="width:50px;height:50px">
                        {{ abbreviateFirstLast(auth()->user()->name) }}
                    </div>
                </div>
                <div>
                    {{ auth()->user()->name }}
                    <div class="opacity-75 " style="font-size:12px;">{{ auth()->user()->role }}</div>
                </div>
            </div>
        </a>

        <ul class="nav flex-column h-100">
            {{-- <li class='nav-item '><a class="nav-link text-uppercase text-white position-relative "
                    href="{{ route('home') }}">
                    <span class="name">Dashboard</span>
                    <div
                        class=" position-absolute btn btn-secondary  end-0 top-0 bottom-0 m-1 d-flex justify-content-center flex-column px-3">
                        <i class="bi-house text-white"></i>
                    </div>
                </a>
            </li> --}}
            @can('view', getPermissionClass('Cases'))
                <li class='nav-item '><a class="nav-link text-uppercase text-white position-relative "
                        href="{{ route('case') }}">
                        <span class="name">Cases</span>
                        <div
                            class=" position-absolute btn btn-secondary  end-0 top-0 bottom-0 m-1 d-flex justify-content-center flex-column px-3">
                            <i class="bi-clipboard text-white"></i>
                        </div>
                    </a>
                </li>
            @endcan
            @can('view', getPermissionClass('Case Billings'))
                <li class='nav-item '><a class="nav-link text-uppercase text-white position-relative "
                        href="{{ route('billing') }}">
                        <span class="name">Bills</span>
                        <div
                            class=" position-absolute btn btn-secondary  end-0 top-0 bottom-0 m-1 d-flex justify-content-center flex-column px-3">
                            <i class="bi-receipt text-white"></i>
                        </div>
                    </a>
                </li>
            @endcan
            @can('view', getPermissionClass('Case Billing Deposits'))
                <li class='nav-item '><a class="nav-link text-uppercase text-white position-relative "
                        href="{{ route('billing-deposit') }}">
                        <span class="name">Bill Payments</span>
                        <div
                            class=" position-absolute btn btn-secondary  end-0 top-0 bottom-0 m-1 d-flex justify-content-center flex-column px-3">
                            <i class="bi-cash-stack text-white"></i>
                        </div>
                    </a>
                </li>
            @endcan
            @can('view', getPermissionClass('Clients'))
                <li class='nav-item '><a class="nav-link text-uppercase text-white position-relative "
                        href="{{ route('client') }}">
                        <span class="name">Clients</span>
                        <div
                            class=" position-absolute btn btn-secondary  end-0 top-0 bottom-0 m-1 d-flex justify-content-center flex-column px-3">
                            <i class="bi-person text-white"></i>
                        </div>
                    </a>
                </li>
            @endcan
            @can('view', getPermissionClass('Associates'))
                <li class='nav-item '><a class="nav-link text-uppercase text-white position-relative "
                        href="{{ route('associate') }}">
                        <span class="name">Associates</span>
                        <div
                            class=" position-absolute btn btn-secondary  end-0 top-0 bottom-0 m-1 d-flex justify-content-center flex-column px-3">
                            <i class="bi-people text-white"></i>
                        </div>
                    </a>
                </li>
            @endcan
            @can('view', getPermissionClass('Office Expenses'))
                <li class='nav-item '><a class="nav-link text-uppercase text-white position-relative "
                        href="{{ route('officeExpense') }}">
                        <span class="name">Office Expenses</span>
                        <div
                            class=" position-absolute btn btn-secondary  end-0 top-0 bottom-0 m-1 d-flex justify-content-center flex-column px-3">
                            <i class="bi bi-receipt"></i>
                        </div>
                    </a>
                </li>
            @endcan
            @can('view', getPermissionClass('Custom Events'))
                <li class='nav-item '><a class="nav-link text-uppercase text-white position-relative "
                        href="{{ route('customEvent') }}">
                        <span class="name">Custom Events</span>
                        <div
                            class=" position-absolute btn btn-secondary  end-0 top-0 bottom-0 m-1 d-flex justify-content-center flex-column px-3">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                    </a>
                </li>
            @endcan
            <li class='nav-item '><a class="nav-link text-uppercase text-white position-relative "
                    href="{{ route('courtBranch') }}">
                    <span class="name">Court Branches</span>
                    <div
                        class=" position-absolute btn btn-secondary  end-0 top-0 bottom-0 m-1 d-flex justify-content-center flex-column px-3">
                        <i class="bi-geo-alt text-white"></i>
                    </div>
                </a>
            </li>
            @can('viewAny', App\Models\User::class)
                <li class='nav-item '><a class="nav-link text-uppercase text-white position-relative "
                        href="{{ route('user') }}">
                        <span class="name">Users</span>
                        <div
                            class=" position-absolute btn btn-secondary  end-0 top-0 bottom-0 m-1 d-flex justify-content-center flex-column px-3">
                            <i class="bi-person-circle text-white"></i>
                        </div>
                    </a>
                </li>
            @endcan
            {{-- <li class='nav-item '>
                <a class="nav-link text-uppercase text-white position-relative " href="#settingsSubNav"
                    data-bs-toggle="collapse">
                    <span class="name">settings</span>
                    <div
                        class=" position-absolute btn btn-secondary end-0 top-0 bottom-0 m-1 d-flex justify-content-center flex-column px-3">
                        <i class="bi-gear text-white "></i>
                    </div>
                </a>
                <div id="settingsSubNav" class="collapse">
                    <ul class="nav flex-column  ">
                        <li class='nav-item '><a class="nav-link text-capitalize text-white position-relative"
                                href="{{ route('settings.adminFeeCategory') }}">
                                <span class="name ps-4">Deductable Expenses</span>

                            </a></li>

                    </ul>
                </div>
            </li> --}}



            <li class="logout nav-item mt-auto">
                <form action="{{ route('logout') }}" method="POST" class="position-relative ">
                    @csrf
                    <button type="submit" class="btn btn-link nav-link text-start text-uppercase text-white  w-100">
                        <span class="name"> logout</span>
                        <div
                            class=" position-absolute btn btn-danger  end-0 top-0 bottom-0 m-1 d-flex justify-content-center flex-column px-3">
                            <i class="bi-power text-white"></i>
                        </div>
                    </button>
                </form>
            </li>
        </ul>
    </div>

@endauth
