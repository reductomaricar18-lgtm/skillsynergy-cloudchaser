<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Intermediate - SkillSynergy</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%); min-height: 100vh; }
        .lesson-card { display: flex; flex-direction: column; height: 100%; min-height: 100px; max-height: 400px; position: relative; }
        .lesson-scrollable { flex: 1 1 auto; overflow-y: auto; background: #f8f9fa; padding: 16px; border-radius: 0 0 0 0; margin-bottom: 0; min-height: 160px; height: auto; position: relative; max-height: 400px; }
        .lesson-section { display: none; }
        .lesson-section.active { display: block; }
        .lesson-nav { display: flex; justify-content: space-between; align-items: center; background: #fff; border-top: 5px solid #e2e8f0; padding: 16px 48px 16px 48px; box-shadow: 0 -2px 8px rgba(0,0,0,0.04); z-index: 2; flex: 0 0 auto; box-sizing: border-box; position: relative; bottom: 0; left: 0; right: 0; }
        .lesson-nav button { background: #ff9800; color: white; border: none; border-radius: 4px; padding: 8px 18px; font-size: 14px; cursor: pointer; transition: background 0.2s; margin: 0 16px; }
        .lesson-nav button:disabled { background: #b0b0b0; cursor: not-allowed; }
        .lesson-nav button:hover:not(:disabled) { background: #e65100; }
        #otherTopicsDropdown { display: none; position: absolute; bottom: 48px; right: 0; min-width: 220px; background: #fff; border: 1px solid #e2e8f0; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); z-index: 10; padding: 6px 0; }
        #otherTopicsDropdown .topic-btn { display: block; width: 100%; border: none; background: none; padding: 10px 20px; text-align: left; font-size: 1rem; color: #333; cursor: pointer; transition: background 0.2s; }
        #otherTopicsDropdown .topic-btn:hover { background: #fff3e0; color: #ff9800; }
        .code-block { background: #263238; color: #fffde7; padding: 20px; border-radius: 8px; margin: 15px 0; overflow-x: auto; font-family: 'Courier New', monospace; font-size: 14px; line-height: 1.6; }
        .code-comment { color: #81c784; }
        .code-keyword { color: #ffd54f; }
        .code-string { color: #ffb74d; }
        .code-number { color: #4fc3f7; }
        .example-box { background: #fffde7; border: 1px solid #ffe082; border-radius: 8px; padding: 20px; margin: 15px 0; }
        .example-box h4 { color: #f9a825; margin-top: 0; }
        .tip-box { background: #fff3e0; border: 1px solid #ffb74d; border-radius: 8px; padding: 15px; margin: 15px 0; }
        .tip-box strong { color: #e65100; }
        ul, ol { margin: 15px 0; padding-left: 30px; }
        li { margin: 8px 0; }
        strong { color: #263238; }
        .highlight { background: #fff3e0; padding: 2px 6px; border-radius: 4px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="lesson-card">
      <div class="lesson-scrollable">
        <div class="lesson-section active" data-index="0">
          <h2>Eloquent Relationships</h2>
          <p>Define relationships between models: <strong>One to One</strong>, <strong>One to Many</strong>, <strong>Many to Many</strong>, and <strong>Polymorphic</strong>.</p>
          <div class="code-block">
<span class="code-comment">// One to Many (User has many Posts)</span>
<span class="code-keyword">class</span> User <span class="code-keyword">extends</span> Model {
    <span class="code-keyword">public function</span> posts() {
        <span class="code-keyword">return</span> $this->hasMany(Post::class);
    }
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>with()</code> for eager loading relationships.
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Query Builder</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
          <p>Laravel's Query Builder provides a convenient, fluent interface to create and run database queries.</p>
          <div class="code-block">
<span class="code-comment">// Get all users with more than 100 points</span>
$users = DB::table('users')->where('points', '>', 100)->get();
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>pluck()</code>, <code>count()</code>, <code>join()</code>, and <code>aggregate</code> methods for advanced queries.
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Middleware</h2>
          <p>Middleware filters HTTP requests entering your application (e.g., authentication, logging).</p>
          <div class="code-block">
<span class="code-comment">// Creating middleware</span>
php artisan make:middleware CheckAge

<span class="code-comment">// In app/Http/Middleware/CheckAge.php</span>
<span class="code-keyword">public function</span> handle($request, Closure $next) {
    if ($request->age <= 18) {
        <span class="code-keyword">return</span> redirect('home');
    }
    <span class="code-keyword">return</span> $next($request);
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Register middleware in <code>app/Http/Kernel.php</code>.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Form Validation</h2>
          <p>Validate user input easily using Laravel's validation system.</p>
          <div class="code-block">
<span class="code-comment">// In controller</span>
$request->validate([
    'title' => 'required|max:255',
    'email' => 'required|email',
]);
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>@error('field')</code> in Blade to show validation errors.
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Authentication Basics</h2>
          <p>Laravel provides built-in authentication scaffolding.</p>
          <div class="code-block">
<span class="code-comment">// Install Laravel Breeze (simple auth)</span>
composer require laravel/breeze --dev
php artisan breeze:install
npm install && npm run dev
php artisan migrate
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>Auth::user()</code> to get the currently authenticated user.
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Resource Controllers</h2>
          <p>Resource controllers make CRUD operations easy and organized.</p>
          <div class="code-block">
<span class="code-comment">// Create a resource controller</span>
php artisan make:controller PostController --resource

<span class="code-comment">// In routes/web.php</span>
Route::resource('posts', PostController::class);
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Resource controllers map to standard CRUD routes automatically.
          </div>
        </div>
        <div class="lesson-section" data-index="6">
          <h2>Blade Templating</h2>
          <p>Blade is Laravel's powerful templating engine. Use layouts, includes, and components for DRY code.</p>
          <div class="code-block">
<span class="code-comment">// layouts/app.blade.php</span>
<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
</head>
<body>
    @yield('content')
</body>
</html>

<span class="code-comment">// In your view</span>
@extends('layouts.app')
@section('title', 'Page Title')
@section('content')
    <h1>Hello, Laravel!</h1>
@endsection
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>@include</code> for partials and <code>@component</code> for reusable UI blocks.
          </div>
        </div>
        <div class="lesson-section" data-index="7">
          <h2>API Basics</h2>
          <p>Build APIs using routes, controllers, and resources.</p>
          <div class="code-block">
<span class="code-comment">// In routes/api.php</span>
Route::get('users', [UserController::class, 'index']);

<span class="code-comment">// Return JSON from controller</span>
return response()->json(User::all());
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>php artisan make:resource</code> for API resource formatting.
          </div>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #ff9800; position: relative; z-index: 11;">Other Laravel Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Eloquent Relationships</button>
          <button class="topic-btn" data-index="1">Query Builder</button>
          <button class="topic-btn" data-index="2">Middleware</button>
          <button class="topic-btn" data-index="3">Form Validation</button>
          <button class="topic-btn" data-index="4">Authentication Basics</button>
          <button class="topic-btn" data-index="5">Resource Controllers</button>
          <button class="topic-btn" data-index="6">Blade Templating</button>
          <button class="topic-btn" data-index="7">API Basics</button>
        </div>
      </div>
    </div>
    <script>
    (function() {
        const sections = document.querySelectorAll('.lesson-section');
        let current = 0;
        const prevBtn = document.getElementById('prevLessonBtn');
        const nextBtn = document.getElementById('nextLessonBtn');
        const pageInfo = document.getElementById('lessonPageInfo');
        function showSection(idx) {
            sections.forEach((sec, i) => {
                sec.classList.toggle('active', i === idx);
            });
            prevBtn.disabled = idx === 0;
            nextBtn.disabled = idx === sections.length - 1;
            pageInfo.textContent = `Section ${idx + 1} of ${sections.length}`;
            current = idx;
        }
        prevBtn.onclick = function() {
            if (current > 0) {
                current--;
                showSection(current);
            }
        };
        nextBtn.onclick = function() {
            if (current < sections.length - 1) {
                current++;
                showSection(current);
            }
        };
        showSection(current);
        document.getElementById('otherTopicsBtn').onclick = function() {
            var dropdown = document.getElementById('otherTopicsDropdown');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        };
        document.querySelectorAll('.topic-btn').forEach(function(btn) {
            btn.onclick = function() {
                var idx = parseInt(btn.getAttribute('data-index'));
                showSection(idx);
                document.getElementById('otherTopicsDropdown').style.display = 'none';
            };
        });
        document.addEventListener('click', function(e) {
            var dropdown = document.getElementById('otherTopicsDropdown');
            var btn = document.getElementById('otherTopicsBtn');
            if (!dropdown.contains(e.target) && e.target !== btn) {
                dropdown.style.display = 'none';
            }
        });
    })();
    </script>
</body>
</html> 