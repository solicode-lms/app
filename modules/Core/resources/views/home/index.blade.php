@extends('layouts.public')
@section('title', "Soli-LMS")
@section('content')
  <!-- Hero Section -->
  <section class="bg-blue-600 text-white py-16">
    <div class="container mx-auto px-4 text-center">
      <h2 class="text-4xl font-bold mb-4">Bienvenue sur SoliLMS</h2>
      <p class="text-lg">SoliLMS est une plateforme innovante de gestion de l'apprentissage en ligne, conçue pour simplifier l'enseignement et offrir des expériences d'apprentissage personnalisées. Découvrez comment nous transformons l'éducation avec des outils intuitifs et des fonctionnalités puissantes.</p>
    </div>
  </section>

  <!-- Articles -->
  <main class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      
      <!-- Article Card -->
      <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <img src="{{ asset('images/public/bloc1.webp') }}" alt="Article Image" class="w-full h-48 object-cover">
        <div class="p-4">
          <h3 class="text-xl font-bold text-gray-800">Pédagogie Active et Suivi Individualisé</h3>
          <p class="text-gray-600 mt-2">SoliLMS guide les formateurs dans l'application des méthodes de pédagogie active. Il permet un suivi personnalisé de chaque apprenant, détectant leurs difficultés et proposant des solutions adaptées.</p>
          <a href="#" class="inline-block mt-4 text-blue-500 hover:underline" style="display: none">Lire plus</a>
        </div>
      </div>
      
 

 

       <!-- Bloc 2 : Gestion des projets et évaluation -->
      <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <img src="{{ asset('images/public/bloc2.webp') }}" alt="Article Image" class="w-full h-48 object-cover">
        <div class="p-4">
          <h3 class="text-xl font-bold text-gray-800">Gestion et Validation des Projets</h3>
          <p class="text-gray-600 mt-2">La plateforme peut générer automatiquement des briefs de projets, assurer leur validation par les formateurs, et centraliser les évaluations pour une meilleure gestion pédagogique.</p>
        <a href="#" class="inline-block mt-4 text-blue-500 hover:underline"  style="display: none">Lire plus</a>
        </div>
      </div>
       
          <!-- Bloc 3 : Gestion des notes et appréciations -->
          <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <img src="{{ asset('images/public/bloc3.webp') }}" alt="Article Image" class="w-full h-48 object-cover">
            <div class="p-4">
              <h3 class="text-xl font-bold text-gray-800">Notes et Appréciations Personnalisées</h3>
              <p class="text-gray-600 mt-2">Avec SoliLMS, chaque formation dispose d'un système d'appréciation précis. Les notes des modules et projets sont gérées efficacement après les évaluations des formateurs.</p>
              <a href="#" class="inline-block mt-4 text-blue-500 hover:underline"  style="display: none">Lire plus</a>
            </div>
          </div>
    

    </div>
  </main>
@endsection