<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>React Basics - SkillSynergy</title>
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
          <h2>Introduction to React</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
          <p>React is a popular JavaScript library for building user interfaces, especially single-page applications. It was developed by Facebook and is widely used for its component-based architecture and efficient rendering.</p>
          <h3>Why React?</h3>
          <ul>
            <li><strong>Component-Based:</strong> Build encapsulated components that manage their own state</li>
            <li><strong>Declarative:</strong> Design simple views for each state in your application</li>
            <li><strong>Virtual DOM:</strong> Efficient updates and rendering</li>
            <li><strong>Strong Community:</strong> Large ecosystem and support</li>
            <li><strong>Reusable:</strong> Components can be reused across projects</li>
          </ul>
          <div class="tip-box">
            <strong>💡 Tip:</strong> React is ideal for dynamic, interactive web applications.
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Setting Up React</h2>
          <p>You can set up a React project using Create React App or other build tools like Vite or Next.js.</p>
          <h3>Using Create React App</h3>
          <div class="code-block">
npx create-react-app my-app
cd my-app
npm start
          </div>
          <h3>Folder Structure</h3>
          <div class="code-block">
my-app/
  node_modules/
  public/
  src/
    App.js
    index.js
  package.json
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> You need Node.js and npm installed to use Create React App.
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>JSX Syntax</h2>
          <p>JSX is a syntax extension for JavaScript that looks similar to HTML. It is used to describe what the UI should look like in React components.</p>
          <h3>Example JSX</h3>
          <div class="code-block">
const element = <h1>Hello, world!</h1>;
          </div>
          <h3>Embedding Expressions</h3>
          <div class="code-block">
const name = 'Alice';
const greeting = <h1>Hello, {name}!</h1>;
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> JSX must have one parent element. Use a &lt;div&gt; or &lt;React.Fragment&gt; if needed.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Components</h2>
          <p>Components are the building blocks of React applications. They can be functional or class-based.</p>
          <h3>Functional Component</h3>
          <div class="code-block">
function Welcome(props) {
  return <h1>Hello, {props.name}</h1>;
}
          </div>
          <h3>Class Component</h3>
          <div class="code-block">
class Welcome extends React.Component {
  render() {
    return <h1>Hello, {this.props.name}</h1>;
  }
}
          </div>
          <h3>Using Components</h3>
          <div class="code-block">
<Welcome name="Alice" />
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Props and State</h2>
          <p>Props are inputs to components, while state is managed within the component.</p>
          <h3>Props Example</h3>
          <div class="code-block">
function Greeting(props) {
  return <h1>Hello, {props.name}!</h1>;
}
          </div>
          <h3>State Example (useState Hook)</h3>
          <div class="code-block">
import React, { useState } from 'react';

function Counter() {
  const [count, setCount] = useState(0);
  return (
    <div>
      <p>You clicked {count} times</p>
      <button onClick={() => setCount(count + 1)}>
        Click me
      </button>
    </div>
  );
}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Props are read-only. Use state for data that changes over time.
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Handling Events</h2>
          <p>React uses camelCase for event handlers and passes functions as event handlers.</p>
          <h3>Event Handling Example</h3>
          <div class="code-block">
function MyButton() {
  function handleClick() {
    alert('Button clicked!');
  }
  return (
    <button onClick={handleClick}>
      Click me
    </button>
  );
}
          </div>
        </div>
        <div class="lesson-section" data-index="6">
          <h2>Conditional Rendering</h2>
          <p>Render different UI elements based on conditions.</p>
          <h3>Example</h3>
          <div class="code-block">
function Greeting(props) {
  if (props.isLoggedIn) {
    return <h1>Welcome back!</h1>;
  } else {
    return <h1>Please sign up.</h1>;
  }
}
          </div>
        </div>
        <div class="lesson-section" data-index="7">
          <h2>Lists and Keys</h2>
          <p>Render lists of elements using the <code>map</code> function. Keys help React identify which items have changed.</p>
          <h3>Example</h3>
          <div class="code-block">
const numbers = [1, 2, 3, 4, 5];
const listItems = numbers.map((number) =>
  <li key={number}>{number}</li>
);

return (
  <ul>{listItems}</ul>
);
          </div>
        </div>
        <div class="lesson-section" data-index="8">
          <h2>Hooks</h2>
          <p>Hooks let you use state and other React features in functional components.</p>
          <h3>useState Example</h3>
          <div class="code-block">
import React, { useState } from 'react';

function Example() {
  const [count, setCount] = useState(0);
  return (
    <div>
      <p>You clicked {count} times</p>
      <button onClick={() => setCount(count + 1)}>
        Click me
      </button>
    </div>
  );
}
          </div>
          <h3>useEffect Example</h3>
          <div class="code-block">
import React, { useState, useEffect } from 'react';

function Example() {
  const [count, setCount] = useState(0);

  useEffect(() => {
    document.title = `You clicked ${count} times`;
  }, [count]);

  return (
    <div>
      <p>You clicked {count} times</p>
      <button onClick={() => setCount(count + 1)}>
        Click me
      </button>
    </div>
  );
}
          </div>
        </div>
        <div class="lesson-section" data-index="10">
          <h2>Other Programming Languages & Technologies</h2>
          <ul>
            <li><a href="lessons/c_basic.php" target="_blank">C Basics</a></li>
            <li><a href="lessons/c++_basic.php" target="_blank">C++ Basics</a></li>
            <li><a href="lessons/python_basic.php" target="_blank">Python Basics</a></li>
            <li><a href="lessons/css_basic.php" target="_blank">CSS Basics</a></li>
            <li><a href="lessons/html_basic.php" target="_blank">HTML Basics</a></li>
            <li><a href="lessons/cassandra_basic.php" target="_blank">Cassandra Basics</a></li>
            <li><a href="lessons/dynamodb_basic.php" target="_blank">DynamoDB Basics</a></li>
            <li><a href="lessons/laravel_basic.php" target="_blank">Laravel Basics</a></li>
            <li><a href="lessons/mongodb_basic.php" target="_blank">MongoDB Basics</a></li>
            <li><a href="lessons/mysql_basic.php" target="_blank">MySQL Basics</a></li>
            <li><a href="lessons/nodejs_basic.php" target="_blank">Node.js Basics</a></li>
            <li><a href="lessons/nosql_basic.php" target="_blank">NoSQL Basics</a></li>
            <li><a href="lessons/oracle_basic.php" target="_blank">Oracle Basics</a></li>
            <li><a href="lessons/php_basic.php" target="_blank">PHP Basics</a></li>
            <li><a href="lessons/postgresql_basic.php" target="_blank">PostgreSQL Basics</a></li>
            <li><a href="lessons/redis_basic.php" target="_blank">Redis Basics</a></li>
            <li><a href="lessons/relational_vs_nosql_basic.php" target="_blank">Relational vs NoSQL</a></li>
            <li><a href="lessons/sql_basic.php" target="_blank">SQL Basics</a></li>
            <li><a href="lessons/sqlserver_basic.php" target="_blank">SQL Server Basics</a></li>
          </ul>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Explore these technologies to broaden your development skills!
          </div>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other React Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Introduction to React</button>
          <button class="topic-btn" data-index="1">Setting Up React</button>
          <button class="topic-btn" data-index="2">JSX Syntax</button>
          <button class="topic-btn" data-index="3">Components</button>
          <button class="topic-btn" data-index="4">Props and State</button>
          <button class="topic-btn" data-index="5">Handling Events</button>
          <button class="topic-btn" data-index="6">Conditional Rendering</button>
          <button class="topic-btn" data-index="7">Lists and Keys</button>
          <button class="topic-btn" data-index="8">Hooks</button>
          <button class="topic-btn" data-index="9">Java Design Principles (For Comparison)</button>
          <button class="topic-btn" data-index="10">Other Programming Languages & Technologies</button>
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