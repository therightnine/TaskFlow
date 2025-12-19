<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>TaskFlow</title>

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600&family=Inter:wght@400;500&display=swap" rel="stylesheet">

  <!-- Tailwind Config -->
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            vietnam: ['Be Vietnam Pro', 'sans-serif'],
            inter: ['Inter', 'sans-serif'],
          },
        },
      },
    }
  </script>
</head>

<body class="bg-white-100 font-vietnam">

  <!-- PAGE WRAPPER -->
  <div class="min-h-screen overflow-x-auto">
    <div class="relative  mx-auto bg-white">

      <!-- ================= NAVBAR ================= -->
      <header class="absolute top-0 left-0 w-full h-[80px] flex items-center px-[120px]">
        <!-- Logo as Image -->
        <div class="h-[50px] w-[150px]">
            <img src="{{ asset('images/Logo.png') }}" alt="TaskFlow Logo" class="h-full w-full object-contain">
        </div>

        <!-- Navigation (centered) -->
        <nav class="flex-1 flex justify-center gap-10 text-[16px]">
            <a href="#" class="text-black">Home</a>
            <a href="#" class="text-black">Features</a>
            <a href="#" class="text-black">Pricing</a>
            <a href="#" class="text-black">Contact</a>
        </nav>

        <!-- Buttons (right) -->
        <div class="flex gap-4">
            <button class="bg-cyan-500 text-white px-6 py-2 rounded-xl text-[16px]">
                Login
            </button>
            <button class="border border-cyan-500 text-cyan-500 bg-white px-6 py-2 rounded-xl text-[16px]">
                Sign Up
            </button>
        </div>
      </header>


      

      <!-- ================= HERO SECTION ================= -->
      <section class="relative h-[700px] pt-[180px] px-[120px]">

        <h1 class="text-[56px] font-medium leading-[72px] text-black max-w-[720px]">
          Planifiez Mieux, <br> Avancez Plus Vite <br> Avec TaskFlow.
        </h1>

        <p class="mt-6 text-[18px] leading-[28px] text-gray-600 max-w-[640px]">
          Votre espace pour créer, planifier et livrer.  
          <br> Une plateforme. Tous vos projets.
        </p>

        <div class="mt-10 flex gap-6">
          <button class="bg-cyan-500 text-white px-10 py-4 rounded-xl text-[16px]">
            Get Started
          </button>

          <button class="border border-black px-10 py-4 rounded-xl text-[16px]">
            Login
          </button>
        </div>

        <!-- Hero Image -->
        <div class="absolute right-[120px] top-[200px] w-[420px] h-[420px] rounded-xl overflow-hidden">
            <img src="{{ asset('images/hero.png') }}" alt="Hero Image" class="w-full h-full object-cover">
        </div>


      </section>

      <section class="py-12 px-8 bg-white flex justify-center">
        <div class=" rounded-xl overflow-hidden">
            <img src="{{ asset('images/features.png') }}" alt="Section Image" class="w-full h-full object-cover">
        </div>
    </section>


      <!-- ================= FEATURES ================= -->
      <section class="relative px-[120px] py-[120px] grid grid-cols-3 gap-[40px]">

        <div class="p-8 border rounded-2xl">
          <h3 class="text-xl font-medium mb-3">Gestion Simple</h3>
          <p class="text-gray-600">
            Organisez vos tâches et projets sans effort.
          </p>
        </div>

        <div class="p-8 border rounded-2xl">
          <h3 class="text-xl font-medium mb-3">Collaboration</h3>
          <p class="text-gray-600">
            Travaillez en équipe en temps réel.
          </p>
        </div>

        <div class="p-8 border rounded-2xl">
          <h3 class="text-xl font-medium mb-3">Suivi Intelligent</h3>
          <p class="text-gray-600">
            Visualisez l’avancement facilement.
          </p>
        </div>

      </section>

      <!-- ================= FOOTER ================= -->
      <footer class="bg-zinc-900 text-orange-50">
        <div class="max-w-[1440px] mx-auto px-8 py-16 grid grid-cols-5 gap-12">
            <!-- TaskFlow Column -->
            <div>
                <h3 class="text-lg font-semibold">TaskFlow</h3>
                <ul class="mt-5 space-y-2 text-base font-normal">
                    <li>Product</li>
                    <li>Home</li>
                    <li>Pricing</li>
                    <li>Customer Success</li>
                    <li>Trust & Security</li>
                    <li>Status</li>
                    <li>What’s New</li>
                </ul>
            </div>

            <!-- About Us Column -->
            <div>
                <h3 class="text-lg font-semibold">About Us</h3>
                <ul class="mt-5 space-y-2 text-base font-normal">
                    <li>Company</li>
                    <li>Leadership</li>
                    <li>Customer</li>
                    <li>Careers</li>
                    <li>Press</li>
                    <li>Sitemap</li>
                </ul>
            </div>

            <!-- TaskFlow Solutions Column -->
            <div>
                <h3 class="text-lg font-semibold">TaskFlow Solutions</h3>
                <ul class="mt-5 space-y-2 text-base font-normal">
                    <li>Project Management</li>
                    <li>Goal Management</li>
                    <li>Agile Management</li>
                    <li>Task Management</li>
                    <li>Increase Productivity</li>
                    <li>Work Management</li>
                    <li>Project Planning</li>
                    <li>All Teams</li>
                    <li>All Uses</li>
                </ul>
            </div>

            <!-- Resources Column -->
            <div>
                <h3 class="text-lg font-semibold">Resources</h3>
                <ul class="mt-5 space-y-2 text-base font-normal">
                    <li>Help Center</li>
                    <li>Forum</li>
                    <li>Support</li>
                    <li>Developers & API</li>
                    <li>Partners</li>
                    <li>Events</li>
                </ul>
            </div>

            <!-- Learn Column -->
            <div>
                <h3 class="text-lg font-semibold">Learn</h3>
                <ul class="mt-5 space-y-2 text-base font-normal">
                    <li>11 Leadership Styles</li>
                    <li>110 Icebreaker Lessons</li>
                    <li>Executive Summary</li>
                    <li>Imposter Syndrome</li>
                    <li>Prevent Team Burnout</li>
                </ul>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="bg-cyan-700 py-6 mt-10">
            <div class="max-w-[1440px] mx-auto px-8 flex flex-col md:flex-row justify-between items-center text-orange-50 text-lg font-normal space-y-4 md:space-y-0">
                <span>© 2025 TaskFlow, Inc.</span>
                <span>Terms & Privacy</span>
            </div>
        </div>
    </footer>

      

    </div>
  </div>

</body>
</html>









