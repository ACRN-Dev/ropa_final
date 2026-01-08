@extends('layouts.app')

@section('title', 'Review ROPA Record')

@section('content')
<div class="container mx-auto p-6 flex justify-center">

    <!-- Maintenance Alert with animation -->
    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-6 rounded shadow-lg w-full max-w-lg 
                animate-bounce-slow text-center"
         role="alert">
        <p class="font-bold text-lg mb-2"><i data-feather="alert-triangle" class="inline w-6 h-6 mr-2"></i> Maintenance Notice</p>
        <p>This feature is currently under maintenance. Some data may not be available.</p>
    </div>

</div>

<!-- Tailwind Custom Animation -->
<style>
@keyframes bounce-slow {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-10px); }
}
.animate-bounce-slow {
  animation: bounce-slow 2s infinite;
}
</style>
@endsection
