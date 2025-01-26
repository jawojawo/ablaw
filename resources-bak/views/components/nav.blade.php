@auth
    <div class="sidenav bg-dark text-white flex-column d-flex">

        <div class='avatar-con mb-4'>
            {{-- <div class="avatar shadow"><img src="https://api.dicebear.com/5.x/adventurer/svg?seed=firstNmae" alt="avatar">
            </div> --}}
            <div class="position-relative m-2 w-100 h-100">
                <div class="name text-center">{{ auth()->user()->name }}</div>

            </div>
        </div>

        <ul class="nav flex-column h-100">
            <li class='nav-item '><a class="nav-link text-uppercase text-white position-relative " href="{{ route('home') }}">
                    <span class="name"> Home</span>
                    <div
                        class=" position-absolute btn btn-secondary  end-0 top-0 bottom-0 m-1 d-flex justify-content-center flex-column px-3">
                        <i class="bi-house text-white"></i>
                    </div>
                </a>
            </li>
            <li class='nav-item '><a class="nav-link text-uppercase text-white position-relative "
                    href="{{ route('case') }}">
                    <span class="name">Cases</span>
                    <div
                        class=" position-absolute btn btn-secondary  end-0 top-0 bottom-0 m-1 d-flex justify-content-center flex-column px-3">
                        <i class="bi-clipboard text-white"></i>
                    </div>
                </a>
            </li>
            <li class='nav-item '><a class="nav-link text-uppercase text-white position-relative "
                    href="{{ route('billing') }}">
                    <span class="name">Billings</span>
                    <div
                        class=" position-absolute btn btn-secondary  end-0 top-0 bottom-0 m-1 d-flex justify-content-center flex-column px-3">
                        <i class="bi-cash-stack text-white"></i>
                    </div>
                </a>
            </li>
            <li class='nav-item '><a class="nav-link text-uppercase text-white position-relative "
                    href="{{ route('client') }}">
                    <span class="name">Clients</span>
                    <div
                        class=" position-absolute btn btn-secondary  end-0 top-0 bottom-0 m-1 d-flex justify-content-center flex-column px-3">
                        <i class="bi-person text-white"></i>
                    </div>
                </a>
            </li>
            <li class='nav-item '><a class="nav-link text-uppercase text-white position-relative "
                    href="{{ route('associate') }}">
                    <span class="name">Associates</span>
                    <div
                        class=" position-absolute btn btn-secondary  end-0 top-0 bottom-0 m-1 d-flex justify-content-center flex-column px-3">
                        <i class="bi-people text-white"></i>
                    </div>
                </a>
            </li>
            <li class='nav-item '><a class="nav-link text-uppercase text-white position-relative "
                    href="{{ route('courtBranch') }}">
                    <span class="name">Court Branches</span>
                    <div
                        class=" position-absolute btn btn-secondary  end-0 top-0 bottom-0 m-1 d-flex justify-content-center flex-column px-3">
                        <i class="bi-geo-alt text-white"></i>
                    </div>
                </a>
            </li>

            <li class='nav-item '>
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
                                <span class="name ps-4">Admin Fee Categories</span>

                            </a></li>

                    </ul>
                </div>
            </li>



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
