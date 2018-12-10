@if(Route::currentRouteName() != 'main')
<div class="pager">
  @yield('pager', '')
</div>
@endif
<div class="content">
  @yield('content', '')
</div>
