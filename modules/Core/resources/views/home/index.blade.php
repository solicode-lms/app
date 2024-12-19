@extends('layouts.public')
@section('title', curd_index_title('PkgBlog::category'))
@section('content')
  <!-- Hero Section -->
  <section class="bg-blue-600 text-white py-16">
    <div class="container mx-auto px-4 text-center">
      <h2 class="text-4xl font-bold mb-4">Bienvenue sur Mon Blog</h2>
      <p class="text-lg">Découvrez des articles inspirants et des idées innovantes pour enrichir votre quotidien.</p>
    </div>
  </section>

  <!-- Articles -->
  <main class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <!-- Article Card -->
      <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <img src="https://via.placeholder.com/400x200" alt="Article Image" class="w-full h-48 object-cover">
        <div class="p-4">
          <h3 class="text-xl font-bold text-gray-800">Titre de l'article</h3>
          <p class="text-gray-600 mt-2">Un petit aperçu du contenu de l'article pour intriguer le lecteur.</p>
          <a href="#" class="inline-block mt-4 text-blue-500 hover:underline">Lire plus</a>
        </div>
      </div>
      <!-- Répétez cette carte pour d'autres articles -->
      <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <img src="https://via.placeholder.com/400x200" alt="Article Image" class="w-full h-48 object-cover">
        <div class="p-4">
          <h3 class="text-xl font-bold text-gray-800">Un autre article</h3>
          <p class="text-gray-600 mt-2">Un résumé captivant qui donne envie de lire davantage.</p>
          <a href="#" class="inline-block mt-4 text-blue-500 hover:underline">Lire plus</a>
        </div>
      </div>
      <!-- Exemple supplémentaire -->
      <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <img src="https://via.placeholder.com/400x200" alt="Article Image" class="w-full h-48 object-cover">
        <div class="p-4">
          <h3 class="text-xl font-bold text-gray-800">Encore un autre article</h3>
          <p class="text-gray-600 mt-2">Découvrez quelque chose de nouveau et passionnant.</p>
          <a href="#" class="inline-block mt-4 text-blue-500 hover:underline">Lire plus</a>
        </div>
      </div>
    </div>
  </main>
@endsection