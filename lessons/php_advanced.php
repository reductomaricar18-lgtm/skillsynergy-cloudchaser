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
    <title>PHP Advanced - SkillSynergy</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
        }
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
        .code-comment { color: #68d391; }
        .code-keyword { color: #f6ad55; }
        .code-string { color: #fbb6ce; }
        .code-number { color: #90cdf4; }
        .example-box { background: #e6fffa; border: 1px solid #81e6d9; border-radius: 8px; padding: 20px; margin: 15px 0; }
        .example-box h4 { color: #2c7a7b; margin-top: 0; }
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
          <h2>Object-Oriented Programming (OOP)</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
          <p>PHP supports OOP with classes, inheritance, interfaces, and traits for reusable, modular code.</p>
          <div class="code-block">
<span class="code-comment">// Class and object</span>
class Animal {
    public $name;
    public function speak() {
        echo "I am an animal.";
    }
}

$dog = new Animal();
$dog->name = "Buddy";
$dog->speak();

<span class="code-comment">// Inheritance</span>
class Dog extends Animal {
    public function speak() {
        echo "Woof!";
    }
}
$pet = new Dog();
$pet->speak();

<span class="code-comment">// Interface</span>
interface Logger {
    public function log($msg);
}

class FileLogger implements Logger {
    public function log($msg) {
        file_put_contents("log.txt", $msg . "\n", FILE_APPEND);
    }
}

<span class="code-comment">// Trait</span>
trait Sharable {
    public function share() {
        echo "Shared!";
    }
}

class Post {
    use Sharable;
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use interfaces for contracts and traits for code reuse across classes.
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Namespaces & Autoloading</h2>
          <p>Namespaces prevent name conflicts. Autoloading loads classes automatically.</p>
          <div class="code-block">
<span class="code-comment">// Namespace</span>
namespace MyApp\Utils;
class Helper {}

<span class="code-comment">// Autoloading (PSR-4 example)</span>
spl_autoload_register(function($class) {
    include str_replace('\\', '/', $class) . '.php';
});
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use Composer for modern autoloading and dependency management.
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>PDO & Secure Database Access</h2>
          <p>Use PDO for secure, flexible database access with prepared statements.</p>
          <div class="code-block">
<span class="code-comment">// PDO connection and query</span>
$pdo = new PDO('mysql:host=localhost;dbname=test', 'user', 'pass');
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
$stmt->execute(['id' => 1]);
$user = $stmt->fetch();
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Always use prepared statements to prevent SQL injection.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Advanced Error Handling</h2>
          <p>Use custom exceptions and logging for robust error management.</p>
          <div class="code-block">
<span class="code-comment">// Custom exception</span>
class MyException extends Exception {}

try {
    throw new MyException("Custom error!");
} catch (MyException $e) {
    error_log($e->getMessage());
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>error_log()</code> for logging and <code>set_exception_handler()</code> for global exception handling.
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>REST APIs (Basics)</h2>
          <p>Build RESTful APIs using PHP for modern web and mobile apps.</p>
          <div class="code-block">
<span class="code-comment">// Simple REST endpoint</span>
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header('Content-Type: application/json');
    echo json_encode(["message" => "Hello, API!"]);
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use frameworks (Laravel, Slim) for advanced API development.
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Composer & Dependency Management</h2>
          <p>Composer is the standard tool for managing PHP dependencies and autoloading.</p>
          <div class="code-block">
<span class="code-comment">// composer.json example</span>
{
  "require": {
    "monolog/monolog": "^2.0"
  }
}

<span class="code-comment">// Install dependencies</span>
composer install
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use <code>composer require</code> to add packages and <code>autoload</code> for class loading.
          </div>
        </div>
        <div class="lesson-section" data-index="6">
          <h2>Best Practices</h2>
          <ul>
            <li>Follow PSR standards (PSR-1, PSR-4, etc.).</li>
            <li>Use namespaces and autoloading.</li>
            <li>Validate and sanitize all user input.</li>
            <li>Use prepared statements for database queries.</li>
            <li>Handle errors and exceptions gracefully.</li>
            <li>Write modular, reusable code.</li>
            <li>Document your code and use version control (Git).</li>
          </ul>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Advanced PHP is about writing secure, maintainable, and scalable applications.
          </div>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other PHP Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">OOP</button>
          <button class="topic-btn" data-index="1">Namespaces & Autoloading</button>
          <button class="topic-btn" data-index="2">PDO & Secure DB Access</button>
          <button class="topic-btn" data-index="3">Advanced Error Handling</button>
          <button class="topic-btn" data-index="4">REST APIs (Basics)</button>
          <button class="topic-btn" data-index="5">Composer & Dependency Management</button>
          <button class="topic-btn" data-index="6">Best Practices</button>
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