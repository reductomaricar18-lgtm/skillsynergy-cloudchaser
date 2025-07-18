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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Laravel Advanced - SkillSynergy</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
            overflow-x: hidden;
        }

        /* Animated Background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.3) 0%, transparent 50%);
            z-index: -1;
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .lesson-card {
            display: flex;
            flex-direction: column;
            height: 100vh;
            max-width: 1200px;
            margin: 20px auto;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            position: relative;
        }

        .lesson-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2, #667eea);
            background-size: 200% 100%;
            animation: gradient 3s ease infinite;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .lesson-scrollable {
            flex: 1;
            overflow-y: auto;
            background: #f8f9fa;
            padding: 30px;
            position: relative;
        }

        .lesson-section { 
            display: none; 
            animation: fadeIn 0.5s ease;
        }
        
        .lesson-section.active { 
            display: block; 
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .lesson-section h2 {
            font-size: 2.2rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 15px;
        }

        .lesson-section h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 2px;
        }

        .lesson-section h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #4a5568;
            margin: 30px 0 20px 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .lesson-section h3::before {
            content: '🚀';
            font-size: 1.3rem;
        }

        .lesson-section p {
            line-height: 1.8;
            margin-bottom: 20px;
            color: #4a5568;
            font-size: 1.1rem;
        }

        .lesson-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            border-top: 1px solid #e2e8f0;
            padding: 25px 40px;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.05);
            position: relative;
        }

        .lesson-nav button {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 15px 30px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .lesson-nav button:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .lesson-nav button:disabled {
            background: #cbd5e0;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        #otherTopicsDropdown {
            display: none;
            position: absolute;
            bottom: 60px;
            right: 20px;
            min-width: 250px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            z-index: 10;
            padding: 10px 0;
            backdrop-filter: blur(20px);
        }

        #otherTopicsDropdown .topic-btn {
            display: block;
            width: 100%;
            border: none;
            background: none;
            padding: 12px 20px;
            text-align: left;
            font-size: 1rem;
            color: #333;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        #otherTopicsDropdown .topic-btn:hover {
            background: linear-gradient(135deg, #f0f4ff, #e6f3ff);
            color: #667eea;
            transform: translateX(5px);
        }

        /* Enhanced Code Blocks */
        .code-block { 
            background: linear-gradient(135deg, #2d3748, #1a202c);
            color: #e2e8f0; 
            padding: 30px; 
            border-radius: 15px; 
            margin: 25px 0; 
            overflow-x: auto; 
            font-family: 'Fira Code', 'Courier New', monospace; 
            font-size: 15px; 
            line-height: 1.6;
            position: relative;
            border: 1px solid #4a5568;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .code-block::before {
            content: '💻';
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 18px;
        }

        .code-comment { color: #68d391; font-style: italic; }
        .code-keyword { color: #f6ad55; font-weight: 600; }
        .code-string { color: #fbb6ce; }
        .code-number { color: #90cdf4; font-weight: 600; }
        .code-function { color: #81e6d9; }
        .code-class { color: #d6bcfa; }

        /* Enhanced Info Boxes */
        .example-box { 
            background: linear-gradient(135deg, #f0fff4, #c6f6d5);
            border: 2px solid #68d391; 
            border-radius: 15px; 
            padding: 30px; 
            margin: 25px 0;
            position: relative;
            overflow: hidden;
        }

        .example-box::before {
            content: '✅';
            position: absolute;
            top: 20px;
            right: 25px;
            font-size: 28px;
            opacity: 0.3;
        }

        .example-box h4 { 
            color: #2f855a; 
            margin-top: 0; 
            margin-bottom: 20px;
            font-size: 1.4rem;
            font-weight: 700;
        }

        .tip-box { 
            background: linear-gradient(135deg, #e6fffa, #b2f5ea);
            border: 2px solid #81e6d9; 
            border-radius: 15px; 
            padding: 30px; 
            margin: 25px 0;
            position: relative;
        }

        .tip-box::before {
            content: '💡';
            position: absolute;
            top: 20px;
            right: 25px;
            font-size: 28px;
            opacity: 0.3;
        }

        .tip-box strong { 
            color: #2c7a7b;
            font-weight: 600;
        }

        /* Enhanced Lists */
        .lesson-section ul, .lesson-section ol { 
            margin: 25px 0; 
            padding-left: 0;
            list-style: none;
        }

        .lesson-section li { 
            background: white;
            margin: 12px 0;
            padding: 18px 25px;
            border-radius: 12px;
            border-left: 4px solid #667eea;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .lesson-section li:hover {
            transform: translateX(8px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .lesson-section li::before {
            content: '🚀';
            font-size: 20px;
        }

        .lesson-section strong { 
            color: #2d3748; 
            font-weight: 600;
        }

        .highlight { 
            background: linear-gradient(135deg, #fef5e7, #fed7aa);
            padding: 4px 8px; 
            border-radius: 6px; 
            font-weight: bold;
            color: #c05621;
        }

        /* Interactive Elements */
        .interactive-link {
            display: inline-block;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 12px 25px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            margin: 15px 0;
        }

        .interactive-link:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #5a67d8, #6b46c1);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .lesson-card {
                margin: 10px;
                height: calc(100vh - 20px);
            }

            .lesson-scrollable {
                padding: 20px;
            }

            .lesson-section h2 {
                font-size: 1.8rem;
            }

            .lesson-nav {
                flex-direction: column;
                gap: 15px;
            }

            .lesson-nav button {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="lesson-card">
      <div class="lesson-scrollable">
        <div class="lesson-section active" data-index="0">
          <h2>Service Providers</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          class="interactive-link">
            <i class="fas fa-code"></i>
            Try Online Compiler
          </a>
          <p>Service providers are the central place of all Laravel application bootstrapping.</p>
          <div class="code-block">
<span class="code-comment">// Create a service provider</span>
php artisan make:provider CustomServiceProvider

<span class="code-comment">// Register in config/app.php</span>
'providers' => [
    // ...
    App\Providers\CustomServiceProvider::class,
],
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use service providers to bind classes into the service container.
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Events & Listeners</h2>
          <p>Events allow you to subscribe and listen for various actions in your app.</p>
          <div class="code-block">
<span class="code-comment">// Create event and listener</span>
php artisan make:event UserRegistered
php artisan make:listener SendWelcomeEmail --event=UserRegistered

<span class="code-comment">// Fire event</span>
event(new UserRegistered($user));
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Register events and listeners in <code>EventServiceProvider</code>.
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Queues & Jobs</h2>
          <p>Queues allow you to defer the processing of a time-consuming task, such as sending an email.</p>
          <div class="code-block">
<span class="code-comment">// Create a job</span>
php artisan make:job ProcessPodcast

<span class="code-comment">// Dispatch a job</span>
ProcessPodcast::dispatch($podcast);
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Configure your queue driver in <code>.env</code> and run <code>php artisan queue:work</code>.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Advanced Eloquent</h2>
          <p>Use scopes, mutators, and accessors for powerful model logic.</p>
          <div class="code-block">
<span class="code-comment">// Local scope</span>
<span class="code-keyword">public function</span> scopeActive($query) {
    <span class="code-keyword">return</span> $query->where('active', 1);
}

<span class="code-comment">// Accessor</span>
<span class="code-keyword">public function</span> getFullNameAttribute() {
    <span class="code-keyword">return</span> $this->first_name . ' ' . $this->last_name;
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use global scopes for default query constraints.
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Policies & Gates</h2>
          <p>Control user authorization logic using policies and gates.</p>
          <div class="code-block">
<span class="code-comment">// Create a policy</span>
php artisan make:policy PostPolicy --model=Post

<span class="code-comment">// In controller</span>
$this->authorize('update', $post);
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Register policies in <code>AuthServiceProvider</code>.
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>API Authentication (Passport & Sanctum)</h2>
          <p>Secure your APIs using Laravel Passport or Sanctum for token-based authentication.</p>
          <div class="code-block">
<span class="code-comment">// Install Sanctum</span>
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate

<span class="code-comment">// Use HasApiTokens in User model</span>
use Laravel\Sanctum\HasApiTokens;

<span class="code-comment">// Protect routes</span>
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use Passport for OAuth2 and Sanctum for simple SPA/mobile API auth.
          </div>
        </div>
        <div class="lesson-section" data-index="6">
          <h2>Testing (Feature & Unit)</h2>
          <p>Write tests to ensure your application works as expected.</p>
          <div class="code-block">
<span class="code-comment">// Feature test example</span>
php artisan make:test UserTest

<span class="code-comment">// In test</span>
public function test_user_can_register() {
    $response = $this->post('/register', [
        'name' => 'Test',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);
    $response->assertStatus(302);
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>php artisan test</code> to run all tests.
          </div>
        </div>
        <div class="lesson-section" data-index="7">
          <h2>Performance Optimization</h2>
          <ul>
            <li>Use caching (routes, views, queries).</li>
            <li>Optimize database queries (eager loading, indexes).</li>
            <li>Queue heavy tasks.</li>
            <li>Profile with Laravel Telescope or Xdebug.</li>
            <li>Use config and route caching in production.</li>
          </ul>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Run <code>php artisan config:cache</code> and <code>php artisan route:cache</code> for best performance.
          </div>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="backToLessonsBtn" onclick="window.close(); window.opener.focus();" style="background: #6c757d; margin-right: 10px;">
          <i class="fas fa-arrow-left"></i> Back to Lessons
        </button>
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #ff512f; position: relative; z-index: 11;">Other Laravel Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Service Providers</button>
          <button class="topic-btn" data-index="1">Events & Listeners</button>
          <button class="topic-btn" data-index="2">Queues & Jobs</button>
          <button class="topic-btn" data-index="3">Advanced Eloquent</button>
          <button class="topic-btn" data-index="4">Policies & Gates</button>
          <button class="topic-btn" data-index="5">API Authentication (Passport & Sanctum)</button>
          <button class="topic-btn" data-index="6">Testing (Feature & Unit)</button>
          <button class="topic-btn" data-index="7">Performance Optimization</button>
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