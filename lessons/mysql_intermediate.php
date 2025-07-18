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
    <title>MySQL Intermediate - SkillSynergy</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #2980b9 0%, #6dd5fa 100%);
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
          <h2>JOINs in MySQL</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
          <p>JOINs combine rows from two or more tables based on a related column.</p>
          <div class="code-block">
<span class="code-comment">-- INNER JOIN</span>
SELECT employees.name, departments.dept_name
FROM employees
INNER JOIN departments ON employees.dept_id = departments.id;

<span class="code-comment">-- LEFT JOIN</span>
SELECT employees.name, departments.dept_name
FROM employees
LEFT JOIN departments ON employees.dept_id = departments.id;

<span class="code-comment">-- RIGHT JOIN</span>
SELECT employees.name, departments.dept_name
FROM employees
RIGHT JOIN departments ON employees.dept_id = departments.id;

<span class="code-comment">-- FULL JOIN (not directly supported in MySQL, use UNION)</span>
SELECT employees.name, departments.dept_name
FROM employees
LEFT JOIN departments ON employees.dept_id = departments.id
UNION
SELECT employees.name, departments.dept_name
FROM employees
RIGHT JOIN departments ON employees.dept_id = departments.id;
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use JOINs to fetch related data from multiple tables efficiently.
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>GROUP BY and HAVING</h2>
          <p>GROUP BY groups rows that have the same values. HAVING filters groups after aggregation.</p>
          <div class="code-block">
<span class="code-comment">-- GROUP BY with aggregate</span>
SELECT dept_id, COUNT(*) as num_employees
FROM employees
GROUP BY dept_id;

<span class="code-comment">-- HAVING</span>
SELECT dept_id, COUNT(*) as num_employees
FROM employees
GROUP BY dept_id
HAVING num_employees > 5;
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> HAVING is like WHERE, but for groups.
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Aggregate Functions</h2>
          <p>Aggregate functions perform calculations on multiple rows and return a single value.</p>
          <div class="code-block">
<span class="code-comment">-- Common aggregate functions</span>
SELECT COUNT(*) FROM employees;
SELECT AVG(salary) FROM employees;
SELECT SUM(salary) FROM employees;
SELECT MIN(salary), MAX(salary) FROM employees;
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use aggregate functions with GROUP BY for powerful reports.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Subqueries</h2>
          <p>Subqueries are queries nested inside another query.</p>
          <div class="code-block">
<span class="code-comment">-- Subquery in WHERE</span>
SELECT name FROM employees
WHERE dept_id = (SELECT id FROM departments WHERE dept_name = 'Sales');

<span class="code-comment">-- Subquery in FROM</span>
SELECT AVG(salary) FROM (SELECT salary FROM employees WHERE dept_id = 2) as sales_salaries;
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Subqueries can be used in SELECT, FROM, or WHERE clauses.
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Indexes</h2>
          <p>Indexes speed up data retrieval but can slow down writes.</p>
          <div class="code-block">
<span class="code-comment">-- Creating an index</span>
CREATE INDEX idx_name ON employees(name);

<span class="code-comment">-- Dropping an index</span>
DROP INDEX idx_name ON employees;
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use indexes on columns that are frequently searched or used in JOINs.
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Basic Transactions</h2>
          <p>Transactions ensure a group of SQL statements are executed as a single unit.</p>
          <div class="code-block">
START TRANSACTION;
UPDATE accounts SET balance = balance - 100 WHERE id = 1;
UPDATE accounts SET balance = balance + 100 WHERE id = 2;
COMMIT;

<span class="code-comment">-- Rollback example</span>
START TRANSACTION;
UPDATE accounts SET balance = balance - 100 WHERE id = 1;
UPDATE accounts SET balance = balance + 100 WHERE id = 2;
ROLLBACK;
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use transactions for operations that must be all-or-nothing (atomic).
          </div>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other MySQL Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">JOINs</button>
          <button class="topic-btn" data-index="1">GROUP BY & HAVING</button>
          <button class="topic-btn" data-index="2">Aggregate Functions</button>
          <button class="topic-btn" data-index="3">Subqueries</button>
          <button class="topic-btn" data-index="4">Indexes</button>
          <button class="topic-btn" data-index="5">Transactions</button>
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