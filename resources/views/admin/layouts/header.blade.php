<!-- Navbar (Header) Starts-->
<nav class="navbar navbar-expand-lg navbar-light bg-faded header-navbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" data-toggle="collapse" class="navbar-toggle d-lg-none float-left"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button><span class="d-lg-none navbar-right navbar-collapse-toggle"><a aria-controls="navbarSupportedContent" href="javascript:;" class="open-navbar-container black"><i class="ft-more-vertical"></i></a></span>

        </div>
        <div class="navbar-container">
            <div id="navbarSupportedContent" class="collapse navbar-collapse">
                <ul class="navbar-nav">

                    <li class="dropdown nav-item"><a id="dropdownBasic3" href="#" data-toggle="dropdown" class="nav-link position-relative dropdown-toggle"><i class="ft-user font-medium-3 blue-grey darken-4"></i>
                            <p class="d-none">User Settings</p></a>
                        <div ngbdropdownmenu="" aria-labelledby="dropdownBasic3" class="dropdown-menu text-left dropdown-menu-right">
                            <a href="{{route('profile')}}" class="dropdown-item py-1"><i class="ft-edit mr-2"></i><span>Edit Profile</span></a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" class="dropdown-item">
                                <i class="ft-power mr-2"></i><span>Logout</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>

                </ul>
            </div>
        </div>
    </div>
</nav>
<!-- Navbar (Header) Ends-->
