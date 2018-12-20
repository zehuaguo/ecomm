

<form action=" {{ route('search') }}" method="GET" class="search-form" style="text-align: right;">
    {{--<i class="fa fa-search search-icon"></i>--}}
    <input results="s" type="search" type="text" name="query" id="query" value="{{ request()->input('query') }}" class="search-box" placeholder="Search for product" >
</form>
