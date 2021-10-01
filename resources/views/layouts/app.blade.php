<!doctype html>
<html lang="en">
@include('layouts.head')
<body>
	<!-- remove it -->
		<!-- Google Tag Manager (noscript) -->
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-THQTXJ7" height="0" width="0"
		    style="display:none;visibility:hidden"></iframe></noscript>
		<!-- End Google Tag Manager (noscript) -->
		@include('layouts.nav')
		@include('layouts.sidebar')
	<!-- remove end -->
	<main class="content">
		@include('layouts.topbar')
		@include('layouts.msgmodal')
		@yield('content')

		@include('layouts.footer')
	</main>
	@include('layouts.footer_script')
</body>
</html>
