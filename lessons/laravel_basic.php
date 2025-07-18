<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Basics - SkillSynergy</title>
    <style>
      body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .lesson-card { display: flex; flex-direction: column; height: 100%; min-height: 100px; max-height: 400px; position: relative; }
        .lesson-scrollable { flex: 1 1 auto; overflow-y: auto; background: #f8f9fa; padding: 16px; border-radius: 0 0 0 0; margin-bottom: 0; min-height: 160px; height: auto; position: relative; max-height: 400px; }
        .lesson-section { display: none; }
        .lesson-section.active { display: block; }
        .lesson-nav { display: flex; justify-content: space-between; align-items: center; background: #fff; border-top: 5px solid #e2e8f0; padding: 16px 48px 16px 48px; box-shadow: 0 -2px 8px rgba(0,0,0,0.04); z-index: 2; flex: 0 0 auto; box-sizing: border-box; position: relative; bottom: 0; left: 0; right: 0; }
        .lesson-nav button { background: #007bff; color: white; border: none; border-radius: 4px; padding: 8px 18px; font-size: 14px; cursor: pointer; transition: background 0.2s; margin: 0 16px; }
        .lesson-nav button:disabled { background: #b0b0b0; cursor: not-allowed; }
        .lesson-nav button:hover:not(:disabled) { background: #0056b3; }
        #otherTopicsDropdown { display: none; position: absolute; bottom: 48px; right: 0; min-width: 220px; background: #fff; border: 1px solid #e2e8f0; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); z-index: 10; padding: 6px 0; }
        #otherTopicsDropdown .topic-btn { display: block; width: 100%; border: none; background: none; padding: 10px 20px; text-align: left; font-size: 1rem; color: #333; cursor: pointer; transition: background 0.2s; }
        #otherTopicsDropdown .topic-btn:hover { background: #f0f4ff; color: #4a63ff; }
        .code-block { background: #2d3748; color: #e2e8f0; padding: 20px; border-radius: 8px; margin: 15px 0; overflow-x: auto; font-family: 'Courier New', monospace; font-size: 14px; line-height: 1.6; }
        .example-box { background: #e6fffa; border: 1px solid #81e6d9; border-radius: 8px; padding: 20px; margin: 15px 0; }
        .tip-box { background: #fef5e7; border: 1px solid #f6ad55; border-radius: 8px; padding: 15px; margin: 15px 0; }
        .tip-box strong { color: #c05621; }
        ul, ol { margin: 15px 0; padding-left: 30px; }
        li { margin: 8px 0; }
        strong { color: #2d3748; }
        .highlight { background: #fef5e7; padding: 2px 6px; border-radius: 4px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="lesson-card">
      <div class="lesson-scrollable">
        <div class="lesson-section active" data-index="0">
          <h2>Introduction to Laravel</h2>
          <p>Laravel is a popular open-source PHP web framework designed for building modern web applications. It provides an elegant syntax, powerful tools, and a robust ecosystem for rapid development.</p>
          <h3>Why Laravel?</h3>
          <ul>
            <li><strong>Elegant Syntax:</strong> Clean and expressive code structure</li>
            <li><strong>Built-in Tools:</strong> Authentication, routing, migrations, and more</li>
            <li><strong>MVC Architecture:</strong> Separation of concerns for maintainable code</li>
            <li><strong>Active Community:</strong> Extensive documentation and support</li>
            <li><strong>Modern Features:</strong> Eloquent ORM, Blade templating, queues, events, and more</li>
          </ul>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Laravel is ideal for both small and large-scale PHP projects.
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Installation & Setup</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
          <p>To get started with Laravel, you need Composer (a PHP dependency manager) installed on your system.</p>
          <h3>Install Laravel via Composer</h3>
          <div class="code-block">
composer create-project --prefer-dist laravel/laravel myApp
          </div>
          <h3>Directory Structure</h3>
          <ul>
            <li><strong>app/</strong> - Application logic</li>
            <li><strong>routes/</strong> - Route definitions</li>
            <li><strong>resources/views/</strong> - Blade templates</li>
            <li><strong>public/</strong> - Publicly accessible files</li>
            <li><strong>database/</strong> - Migrations, seeders, factories</li>
          </ul>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>php artisan serve</code> to start a local development server.
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Routing</h2>
          <p>Routing in Laravel allows you to define application endpoints and handle HTTP requests.</p>
          <h3>Basic Route Example</h3>
          <div class="code-block">
// routes/web.php
Route::get('/', function () {
    return view('welcome');
});
          </div>
          <h3>Route Parameters</h3>
          <div class="code-block">
Route::get('/user/{id}', function ($id) {
    return 'User '.$id;
});
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use named routes for easier URL generation and redirection.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Controllers</h2>
          <p>Controllers group related request handling logic into a single class.</p>
          <h3>Creating a Controller</h3>
          <div class="code-block">
php artisan make:controller UserController
          </div>
          <h3>Controller Example</h3>
          <div class="code-block">
// app/Http/Controllers/UserController.php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show($id)
    {
        return 'User '.$id;
    }
}
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Blade Templating</h2>
          <p>Blade is Laravel's powerful templating engine for building dynamic views.</p>
          <h3>Blade Syntax Example</h3>
          <div class="code-block">
{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
</head>
<body>
    <h1>Hello, {{ $name }}</h1>
</body>
</html>
          </div>
          <h3>Blade Directives</h3>
          <ul>
            <li><code>@if</code>, <code>@elseif</code>, <code>@else</code>, <code>@endif</code></li>
            <li><code>@foreach</code>, <code>@for</code>, <code>@while</code></li>
            <li><code>@include</code>, <code>@extends</code>, <code>@section</code>, <code>@yield</code></li>
          </ul>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Eloquent ORM</h2>
          <p>Eloquent is Laravel's built-in ORM for interacting with the database using models.</p>
          <h3>Defining a Model</h3>
          <div class="code-block">
php artisan make:model Post
          </div>
          <h3>Basic Usage</h3>
          <div class="code-block">
// app/Models/Post.php
$post = new Post;
$post->title = 'My Post';
$post->save();

$posts = Post::all();
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use Eloquent relationships for working with related data (hasOne, hasMany, belongsTo, etc).
          </div>
        </div>
        <div class="lesson-section" data-index="6">
          <h2>Artisan CLI</h2>
          <p>Artisan is Laravel's command-line interface for automating tasks.</p>
          <h3>Common Artisan Commands</h3>
          <ul>
            <li><code>php artisan list</code> - List all commands</li>
            <li><code>php artisan migrate</code> - Run database migrations</li>
            <li><code>php artisan db:seed</code> - Seed the database</li>
            <li><code>php artisan make:model ModelName</code> - Create a new model</li>
            <li><code>php artisan make:controller ControllerName</code> - Create a new controller</li>
          </ul>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other Laravel Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Introduction to Laravel</button>
          <button class="topic-btn" data-index="1">Installation & Setup</button>
          <button class="topic-btn" data-index="2">Routing</button>
          <button class="topic-btn" data-index="3">Controllers</button>
          <button class="topic-btn" data-index="4">Blade Templating</button>
          <button class="topic-btn" data-index="5">Eloquent ORM</button>
          <button class="topic-btn" data-index="6">Artisan CLI</button>
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